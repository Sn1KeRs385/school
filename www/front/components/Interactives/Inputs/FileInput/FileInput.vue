<template lang="pug">
  .file-input__container
    Button(
      tag="label"
      :secondary="true"
      :disabled="disabled"
    )
      template(slot="before")
        .file-input__camera
          CameraSVG
      input.file-input.hidden(
        ref="input"
        type="file"
        :files="files"
        :accept="accept"
        :multiple="multiple"
        :disabled="disabled || !canAdd"
        @change="changeValue"
      )
      slot
    ValidationList(:validations="validations")
    .file-input__files(v-if="files.length")
      .file-input__file(
        v-for="file in files"
        :style="getStyleFile(file)"
      )
        .file-input__file-progress(v-if="file.progress < 100") {{ (file.progress).toFixed(0) }}%
        .file-input__file-remove(@click="removeFile(file)")
          CloseTagSVG

</template>

<script>
import Button from '../../Controls/Button/Button.vue'
import InputMixin from '../../../../assets/js/vue-mixins/input'
import CameraSVG from '../../../SVG/CameraSVG.vue'
import CloseTagSVG from '../../../SVG/CloseTagSVG.vue'
import ValidationList from '../ValidationList/ValidationList.vue'
import {
  uploaderRegister,
  uploaderUpload,
  uploaderFinish,
  uploaderDelete,
} from '../../../../plugins/api/uploader'

export default {
  components: {
    Button,
    CameraSVG,
    CloseTagSVG,
    ValidationList,
  },
  mixins: [InputMixin('getInput', 'files', 'changeFiles')],
  props: {
    accept: {
      type: String,
      default: 'image/x-png, image/jpeg, video/mp4, video/mov',
    },
    multiple: {
      type: Boolean,
      default: true,
    },
    collectionNames: {
      required: true,
      type: Object,
    },
    maxFiles: {
      type: Number,
      default: 10,
    },
    maxSize: {
      type: Number,
      default: 1024 * 1024 * 100,
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      countFiles: 1,
    }
  },
  computed: {
    getInput() {
      return this.$refs.input
    },
    canAdd() {
      return this.files.length < this.maxFiles
    },
    formatMaxSize() {
      return this.formatBytes(this.maxSize)
    },
  },
  methods: {
    getStyleFile(file) {
      if (file.type === 'image') {
        return {
          backgroundImage: `url(${file.url})`,
        }
      }
      return {}
    },
    formatBytes(bytes, decimals = 2) {
      const sizes = this.$t('file-input.sizes')
      if (bytes === 0) return `0 ${sizes[0]}`
      const k = 1024
      const dm = decimals < 0 ? 0 : decimals
      const i = Math.floor(Math.log(bytes) / Math.log(k))
      return `${parseFloat((bytes / k ** i).toFixed(dm))} ${sizes[i]}`
    },
    async uploadFile(file) {
      if (file.size > this.maxSize) {
        this.$emit('update:validations', [
          {
            isValid: false,
            message: this.$t('file-input.messages.size', {
              name: file.name,
              size: this.formatMaxSize,
            }),
            name: `${file.name}-max-size`,
          },
        ])
        return
      }
      const { id, type } = await this.uploadRegister(file)
      const currentFile = {
        id,
        file,
        chunk: 0,
        portion: 1024 * 1024,
        url: null,
        progress: 0,
        size: file.size,
        type,
      }
      if (type === 'image') {
        const { target } = await this.readFileAsync(file, 'readAsDataURL')
        currentFile.url = target.result
      }
      this.files.push(currentFile)
      await this.uploadPortion(currentFile)
    },
    async uploadRegister(file) {
      const [type] = file.type.split('/')
      const { data } = await uploaderRegister({
        collection_name: this.collectionNames[type],
        name: file.name,
      })
      return { ...data, type }
    },
    // eslint-disable-next-line no-unused-vars
    async uploadPortion(currentFile) {
      const { id, file, chunk, portion, size } = currentFile
      if (!id) {
        return
      }
      const blob = this.sliceFile(file, chunk, portion)
      const { target, loaded } = await this.readFileAsync(blob)
      if (size > chunk) {
        await uploaderUpload({
          uploadId: id,
          blob: this.convertToBinary(target.result),
          chunk,
        })
        currentFile.chunk = chunk + loaded
        currentFile.progress = (chunk / size) * 100
        await this.uploadPortion(currentFile)
      } else {
        await uploaderFinish({
          uploadId: id,
        })
        currentFile.progress = (chunk / size) * 100
      }
    },
    readFileAsync(file, typeLoad = 'readAsBinaryString') {
      return new Promise(resolve => {
        const reader = new FileReader()
        reader.onloadend = e => {
          resolve(e)
        }
        reader[typeLoad](file)
      })
    },
    sliceFile(file, chunk, portion) {
      let blob = null
      if (file.slice) {
        blob = file.slice(chunk, chunk + portion)
      } else if (file.webkitSlice) {
        blob = file.webkitSlice(chunk, chunk + portion)
      } else {
        blob = file.mozSlice(chunk, chunk + portion)
      }
      return blob
    },
    convertToBinary(chunk) {
      const ords = Array.prototype.map.call(chunk, k => {
        // eslint-disable-next-line no-bitwise
        return k.charCodeAt(0) & 0xff
      })
      const ui8a = new Uint8Array(ords)
      return new Blob([ui8a.buffer], {
        type: 'application/x-binary; charset=x-user-defined',
      })
    },
    async changeValue(e) {
      const { files } = e.target
      const promises = []
      if (files.length > this.maxFiles - this.files.length) {
        this.$emit('update:validations', [
          {
            name: 'max-files-count',
            message: this.$t('file-input.messages.count', {
              count: this.maxFiles,
            }),
            isValid: false,
          },
        ])
        return
      }
      this.$emit('update:validations', [])
      for (let i = 0; i < Math.min(files.length, this.maxFiles); i += 1) {
        promises.push(this.uploadFile(files[i]))
      }
      this.$emit('update:loading', true)
      await Promise.all(promises)
      e.target.type = ''
      e.target.type = 'file'
      this.$emit('update:loading', false)
    },
    async removeFile(file) {
      const index = this.files.findIndex(k => k.id === file.id)
      this.files.splice(index, 1)
      if (file.file) {
        await uploaderDelete(file.id)
      } else {
        this.$emit('removeFile', file)
      }
    },
  },
}
</script>

<style lang="stylus">
@import "file-input.styl";
</style>
