<template lang="pug">
  AdjacentModalContainer(@close="close")
    .achievement-edit-modal__container
      .achievement-edit-modal__back-to-profile
        MobileBackTo(@click.native="close") {{ $t('achievement-edit-modal.back-to') }}
      .achievement-edit-modal__close(@click="close")
        ModalCloseSVG
      .achievement-edit-modal(v-if="!isSuccess") 123
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
@import "left-menu-item-category-edit-modal.styl"
</style>
