<template>
    <div>
        <label :class="'dropzone' + (dragEnter ? ' draggable ' : '')"
          @dragenter="dragEnter = true"
          @dragleave="dragEnter = false"
          @dragend="dragEnter = false"
          @drop="dragEnter = false"
        >
          
            <input
              type="file"

              :multiple="max_files > 1"
              
              :accept="accept"

              @change="selectFiles($event.target.files)"
              
              :disabled="value.length >= max_files"

              :class="'input-file' + (value.length < max_files ? ' cursor-pointer ' : '')"
              >
            
            <p v-if="value.length == 0" class="isInitial">
              Перетащите файлы для загрузки их на сервер<br> Или нажмите сюда
            </p>
            <div v-else class="isSaving">

              <div class="files">
                <div v-for="file in value"
                  :key="file.file.name"
                  :class="'file-card ' + (file.uploaded ? 'uploaded' : 'uploading')"
                >

                  <p>{{file.file.name}}</p>

                  <p v-if="file.status && file.status == 'WAITING_QUEUE'">Ожидание очереди на обработку...</p>
                  <p v-if="file.status && file.status == 'UPLOADING'">Загрузка на файловый сервер...</p>
                  <p v-if="file.status && file.status == 'CONVERTING'">Обработка...</p>
                  <p v-if="file.status && file.status == 'FINISHED'">Загрузка завершена. Файл доступен.</p>

                  <!-- <p>{{file.file.type}}</p> -->
                  <!-- <p>{{file.file.size}}</p> -->

                  <div v-if="file.uploaded" class="d-flex justify-content-end">
                    <b-button @click="deleteFile(file)" variant="danger" size="sm">
                      <i class="fa fa-trash"></i> Удалить
                    </b-button>
                  </div>
                  
                  <p v-else>{{file.progress}}%</p>

                </div>
              </div>

            </div>

        </label>
    </div>
</template>

<script>
import axios from "axios";

export default {
  name: "fileUploader",
  props: ["id", "multiple", "accept", "max_files", "value", "source"],
  data() {
    return {
      url: process.env.API_HOST + "admin/uploader/",
      isInitial: true,
      isSaving: false,
      dragEnter: false
    };
  },
  methods: {
    async selectFiles(files) {
      this.isInitial = false;
      this.isSaving = true;

      let allFilesCount = files.length + this.value.length;
      if (allFilesCount > this.max_files) {
        return;
      }

      await [...files].forEach(file => {
        this.value.push({
          file,
          progress: 0,
          uploaded: false
        });
      });

      this.uploadFiles();
    },

    async uploadFiles() {
      this.value.forEach(async file => {
        if (file.uploaded == false) {
          this.uploadFile(file);
        }
      });
    },

    async uploadFile(file) {
      let id = await this.RegisterFile(file.file.name);

      file.id = id;

      this.UploadPortion({
        file: file,
        from: 0,
        to: 0,
        options: {
          portion: 512 * 1024 // bytes per request
        }
      });
    },

    async RegisterFile(name) {
      let response = await axios.post(`${this.url}register`, {
        type: "register",
        name,
        collection_name: this.source
      });
      let { data } = response;
      return data.data.id;
    },

    async UploadPortion({ file, from, to, options }) {
      var reader = new FileReader();

      let that = this;

      reader.onloadend = async evt => {
        if (evt.target.readyState == FileReader.DONE) {
          if (file.file.size > from) { 
            let result = await axios({
              method: "PATCH",
              url: `${this.url}upload`,
              responseType: "blob",
              headers: {
                Accept: "*/*",
                "Content-Type": "application/x-binary; charset=x-user-defined",
                "Upload-Id": file.id,
                "Portion-From": from,
                "Portion-Size": options.portion
              },
              data: new Blob([this.convertToBinary(evt.target.result)], {
                type: "application/x-binary; charset=x-user-defined"
              })
            });

            from += options.portion;
            to = from + options.portion;

            var percentComplete = Math.round(to / (file.file.size / 100));
            if (percentComplete >= 100) {
              percentComplete = 100;
            }

            file.progress = percentComplete;

            this.UploadPortion({ file, from, to, options });
          } else {
            let finish = await axios({
              method: "PATCH",
              url: `${this.url}finish`,
              headers: {
                "Upload-Id": file.id
              }
            });
            file.uploaded = true;

            // that.value.push(file.id);
            // that.value.push(file);
            that.$emit("input", [...that.value]);
          }
        }
      };

      let blob = null;

      if (file.file.slice) {
        blob = file.file.slice(from, from + options.portion);
      } else {
        if (file.file.webkitSlice) {
          blob = file.file.webkitSlice(from, from + options.portion);
        } else {
          if (file.file.mozSlice) {
            blob = file.file.mozSlice(from, from + options.portion);
          }
        }
      }

      reader.readAsBinaryString(blob);
    },

    convertToBinary(datastr) {
      function byteValue(x) {
        return x.charCodeAt(0) & 0xff;
      }
      var ords = Array.prototype.map.call(datastr, byteValue);
      var ui8a = new Uint8Array(ords);
      return ui8a.buffer;
    },

    async deleteFile(file) {
      if (!confirm("Вы действительно хотите удалить файл?")) {
        return;
      }

      var idx = this.value.indexOf(file);

      await axios.delete(
        `${this.url}delete?id=${file.id}&storage=${file.storage ? true : false}`
      );

      if (idx != -1) {
        return this.value.splice(idx, 1);
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.dropzone {
  outline: 2px dashed grey;
  outline-offset: -10px;
  background: lightcyan;
  color: dimgray;
  padding: 10px 10px;
  width: 100%;
  min-height: 300px;
  position: relative;
  transition: all 0.1s;

  .isSaving,
  .isInitial {
    font-size: 1.2em;
    text-align: center;
    padding: 50px 0;

    display: flex;
    flex-direction: column;
    align-items: center;

    .progress {
      min-width: 30%;
      max-width: 50%;
    }
    .files {
      min-width: 90%;
      min-height: 150px;
      padding: 15px;

      display: flex;
      flex-flow: row wrap;
      justify-content: flex-start;

      .file-card {
        width: 128px;
        height: 128px;
        font-size: 8pt;
        margin-right: 10px;
        margin-bottom: 10px;
        padding-top: 10px;
        padding-bottom: 10px;
        background: rgba(245, 245, 245, 0.527);
        transition: all 1s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        z-index: 10;
      }
      .uploading {
        color: rgb(34, 34, 34);
        outline: 2px dashed grey;
      }
      .uploaded {
        color: rgb(0, 51, 20);
        background-color: rgba(0, 255, 191, 0.205);
        outline: 2px dashed rgb(0, 128, 64);
      }
    }
  }
  .input-file {
    position: absolute;
    opacity: 0;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    z-index: 5;
  }
}
.cursor-pointer {
  cursor: pointer !important;
}
.draggable {
  background: rgba(167, 201, 201, 1);
  background: -moz-linear-gradient(
    left,
    rgba(167, 201, 201, 1) 0%,
    rgba(224, 255, 255, 0.85) 50%,
    rgba(167, 201, 201, 0.7) 100%
  );
  background: -webkit-gradient(
    left top,
    right top,
    color-stop(0%, rgba(167, 201, 201, 1)),
    color-stop(50%, rgba(224, 255, 255, 0.85)),
    color-stop(100%, rgba(167, 201, 201, 0.7))
  );
  background: -webkit-linear-gradient(
    left,
    rgba(167, 201, 201, 1) 0%,
    rgba(224, 255, 255, 0.85) 50%,
    rgba(167, 201, 201, 0.7) 100%
  );
  background: -o-linear-gradient(
    left,
    rgba(167, 201, 201, 1) 0%,
    rgba(224, 255, 255, 0.85) 50%,
    rgba(167, 201, 201, 0.7) 100%
  );
  background: -ms-linear-gradient(
    left,
    rgba(167, 201, 201, 1) 0%,
    rgba(224, 255, 255, 0.85) 50%,
    rgba(167, 201, 201, 0.7) 100%
  );
  background: linear-gradient(
    to right,
    rgba(167, 201, 201, 1) 0%,
    rgba(224, 255, 255, 0.85) 50%,
    rgba(167, 201, 201, 0.7) 100%
  );
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a7c9c9', endColorstr='#a7c9c9', GradientType=1 );
  box-shadow: 2px 2px 2px 2px rgba(167, 201, 201, 1);
}
</style>

