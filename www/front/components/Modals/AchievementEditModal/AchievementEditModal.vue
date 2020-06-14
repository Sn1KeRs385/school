<template lang="pug">
  AdjacentModalContainer(@close="close")
    .achievement-edit-modal__container
      .achievement-edit-modal__back-to-profile
        MobileBackTo(@click.native="close") {{ $t('achievement-edit-modal.back-to') }}
      .achievement-edit-modal__close(@click="close")
        ModalCloseSVG
      .achievement-edit-modal(v-if="!isSuccess")
        form.achievement-edit-modal__form(@submit.prevent="submit")
          .achievement-edit-modal__form-title {{ $t('achievement-edit-modal.title') }}
          AchievementModalField(
            :label="$t('achievement-edit-modal.category.label')"
            :tool-content="$t('achievement-modal.form-achievement.category.hint')"
            :tool-class="'tool-tip__mark-container-modal-category'"
            for-input="achievement-complete-category"
          )
            TextInput(
              id="achievement-complete-category"
              :value="achievement.category_name"
              :editable="false"
              :disabled="true"
            )

          AchievementModalField(
            :label="$t('achievement-edit-modal.name.label')"
            :tool-content="$t('achievement-modal.form-achievement.name.hint')"
            :tool-class="'tool-tip__mark-container-modal-name'"
            for-input="achievement-complete-name"
          )
            TextInput(
              id="achievement-complete-name"
              :value="achievement.title"
              :editable="false"
              :disabled="true"
            )

          AchievementModalField(
            :label="$t('achievement-edit-modal.condition.label')"
            :tool-content="$t('achievement-modal.form-achievement.condition.hint')"
            :tool-class="'tool-tip__mark-container-modal-condition'"
            for-input="achievement-complete-condition"
          )
            TextInput(
              id="achievement-complete-condition"
              :value="achievement.condition.title"
              :editable="false"
              :disabled="true"
            )

          AchievementModalField(
            :label="$t('achievement-edit-modal.difficult.label')"
            :tool-content="achievementDifficultContentToolTip"
            :tool-class="'tool-tip__mark-container-modal-difficult'"
            for-input="achievement-complete-difficult"
          )
            TextInput(
              id="achievement-complete-difficult"
              :value="achievement.difficulty.name"
              :editable="false"
              :disabled="true"
            )

          AchievementModalField(
            :label="$t('achievement-edit-modal.geo.label')"
            :tool-content="$t('achievement-modal.form-achievement.placement.hint-myself')"
            :tool-class="'tool-tip__mark-container-modal-placement-myself'"
            for-input="achievement-complete-geo"
          )
            CityInput(
              id="make-achievement-placement"
              :placeholder="$t('achievement-edit-modal.geo.placeholder')"
              :cities.sync="geo.options"
              :city.sync="achievement.geo"
              :search.sync="geo.search"
              :is-open.sync="geo.isOpen"
              :rules="getGeoRules"
              :dependence="achievement.condition.value"
              :is-valid="geoIsValid"
              :validations.sync="geo.validations"
              :disabled="formLoading"
              :is-subject="true"
            )
            <!--            TextInput(-->
            <!--              id="achievement-complete-geo"-->
            <!--              :placeholder="$t('achievement-edit-modal.geo.placeholder')"-->
            <!--              v-model="achievement.geo"-->
            <!--              :rules="getGeoRules"-->
            <!--              :validations.sync="geo.validations"-->
            <!--              :is-valid="geoIsValid"-->
            <!--              :disabled="formLoading"-->
            <!--            )-->

          AchievementModalField(
            :label="$t('achievement-edit-modal.comment.label')"
            :tool-content="$t('achievement-modal.form-achievement.comment.hint')"
            :tool-class="'tool-tip__mark-container-modal-comment'"
            for-input="achievement-complete-comment"
          )
            .achievement-edit-modal__form-text-area
              TextArea(
                id="achievement-complete-comment"
                v-model="achievement.comment"
                :rules="getCommentRules"
                :validations.sync="comment.validations"
                :is-valid="commentIsValid"
                :disabled="formLoading"
              )

          AchievementModalField(
            :label="$t('achievement-edit-modal.subcategories.label')"
            :tool-content="subcategoriesToolTip"
            :tool-class="'tool-tip__mark-container-modal-subcategories'"
          )
            .achievement-edit-modal__form-subcategories
              Tag(
                v-for="subcategory in achievement.subcategories"
                :key="subcategory.id"
              ) {{ subcategory.name }}

          AchievementModalField(
            :tool-content="$t('achievement-modal.form-achievement.files.hint')"
            :tool-class="'tool-tip__mark-container-modal-files'"
            class="align-items-center"
          )
            FileInput(
              id="achievement-complete-files"
              v-model="achievement.files"
              :rules="getFilesRules"
              :validations.sync="files.validations"
              :is-valid="filesIsValid"
              :collection-names="collectionNames"
              :disabled="formLoading"
              :loading.sync="files.loading"
              @removeFile="removeFile"
            ) {{ $t('achievement-edit-modal.files.title') }}

          AchievementModalField(
            :show-tool-tip="false"
          )
            .achievement-edit-modal__form-submit
              Button(:submit="true") {{ $t('achievement-edit-modal.submit') }}
      SuccessMediaModal(
        v-else
        @submitSuccess="submitSuccess"
      )
</template>

<script>
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import AchievementModalField from '../AchievementModal/AchievementModalField/AchievementModalField.vue'
import CityInput from '../../Interactives/Inputs/CityInput/CityInput.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'
import Multiselector from '../../Interactives/Inputs/Multiselector/Multiselector.vue'
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo.vue'
import TextInput from '../../Interactives/Inputs/TextInput/TextInput.vue'
import TextArea from '../../Interactives/Inputs/TextArea/TextArea.vue'
import FileInput from '../../Interactives/Inputs/FileInput/FileInput.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import Tag from '../../General/Tag/Tag.vue'
import { EmptyArrayRule, RequiredRule } from '../../../assets/js/validation-rules'
import ProgressConditions from '../../../assets/js/constatns/progress-conditions'
import { updateAchievement, removeMediaUserAchievement } from '../../../plugins/api/progress'
import SuccessMediaModal from '../SuccessMediaModal/SuccessMediaModal'

export default {
  components: {
    SuccessMediaModal,
    AdjacentModalContainer,
    ModalCloseSVG,
    AchievementModalField,
    CityInput,
    Multiselector,
    MobileBackTo,
    TextInput,
    TextArea,
    FileInput,
    Button,
    Tag,
  },
  props: {
    achievement: {
      required: true,
      type: Object,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      isSuccess: false,
      formLoading: false,
      collectionNames: {
        image: 'progress_image',
        video: 'progress_video',
      },
      updatedData: '',
      geo: {
        value: [],
        validations: [],
        search: '',
        options: [],
        loading: false,
        isOpen: false,
      },
      comment: {
        validations: [],
      },
      initFilesCount: this.achievement.files.length,
      files: {
        value: [],
        validations: [],
        loading: false,
      },
    }
  },
  computed: {
    getGeoRules() {
      return [new RequiredRule(this.$t('achievement-edit-modal.geo.messages.required'))]
    },
    getCommentRules() {
      return [new RequiredRule(this.$t('achievement-edit-modal.comment.messages.required'))]
    },
    getFilesRules() {
      if (this.achievement.condition_id === ProgressConditions.PhotoOrVideo) {
        return [new EmptyArrayRule(this.$t('achievement-edit-modal.files.messages.required'))]
      }
      return []
    },
    geoIsValid() {
      return !this.geo.validations.filter(k => !k.isValid).length
    },
    commentIsValid() {
      return !this.comment.validations.filter(k => !k.isValid).length
    },
    filesIsValid() {
      return !this.files.validations.filter(k => !k.isValid).length
    },
    formIsValid() {
      return this.geoIsValid && this.commentIsValid && this.filesIsValid && !this.files.loading
    },
    subcategoriesToolTip() {
      return this.$t('achievement-modal.form-achievement.subcategories.hint').join('<br />')
    },
    achievementDifficultContentToolTip() {
      return `${this.$t('achievement-modal.form-achievement.difficult.hint')}<br />${this.$t(
        'achievement-modal.form-achievement.difficult.hint-easy',
      )}<br />${this.$t('achievement-modal.form-achievement.difficult.hint-medium')}<br />${this.$t(
        'achievement-modal.form-achievement.difficult.hint-hard',
      )}<br />${this.$t('achievement-modal.form-achievement.difficult.hint-extra')}`
    },
  },
  methods: {
    close() {
      this.$emit('close', this.achievement)
    },
    submitSuccess() {
      this.$emit('submit', this.updatedData, this.$t('messages.achievement-change'))
      this.close()
    },
    submit() {
      setTimeout(async () => {
        if (!this.formIsValid) return
        this.formLoading = true
        const { data } = await updateAchievement({
          id: this.achievement.id,
          comment: this.achievement.comment,
          geo: this.achievement.geo ? this.achievement.geo.name : null,
          files_ids: this.achievement.files.filter(k => k.file).map(k => k.id),
        })
        this.achievement.files.forEach((k, i) => {
          k.id = data.files_ids[i]
        })
        data.files = this.achievement.files
        this.updatedData = data
        const newFiles = this.updatedData.files.length
        if (this.initFilesCount !== newFiles && newFiles !== 0) {
          this.isSuccess = true
        } else {
          this.submitSuccess()
        }
      })
    },
    async removeFile(file) {
      await removeMediaUserAchievement(file.id)
    },
  },
}
</script>

<style lang="stylus">
@import "achievement-edit-modal.styl"
</style>
