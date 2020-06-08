<template lang="pug">
  AdjacentModalContainer(@close="close")
    .goal-complete-modal__container
      .goal-complete-modal__back-to-profile
        MobileBackTo(@click.native="close") {{ $t('goal-complete-modal.back-to') }}
      .goal-complete-modal__close(@click="close")
        ModalCloseSVG
      .goal-complete-modal(v-if="!isSuccess")
        form.goal-complete-modal__form(@submit.prevent="submit")
          .goal-complete-modal__form-title {{ $t('goal-complete-modal.title') }}
          AchievementModalField(
            :label="$t('goal-complete-modal.category.label')"
            :tool-content="$t('achievement-modal.form-goal.category.hint')"
            :tool-class="'tool-tip__mark-container-modal-category'"
            for-input="goal-complete-category"
          )
            TextInput(
              id="goal-complete-category"
              :value="goal.category_name"
              :editable="false"
              :disabled="true"
            )

          AchievementModalField(
            :label="$t('goal-complete-modal.name.label')"
            :tool-content="$t('achievement-modal.form-goal.name.hint')"
            :tool-class="'tool-tip__mark-container-modal-name'"
            for-input="goal-complete-name"
          )
            TextInput(
              id="goal-complete-name"
              :value="goal.title"
              :editable="false"
              :disabled="true"
            )

          AchievementModalField(
            :label="$t('goal-complete-modal.condition.label')"
            :tool-content="$t('achievement-modal.form-goal.condition.hint')"
            :tool-class="'tool-tip__mark-container-modal-condition align-self-center'"
            for-input="goal-complete-condition"
          )
            TextInput(
              id="goal-complete-condition"
              :value="goal.condition.title"
              :editable="false"
              :disabled="true"
            )

          AchievementModalField(
            :label="$t('goal-complete-modal.difficult.label')"
            :tool-content="goalDifficultContentToolTip"
            :tool-class="'tool-tip__mark-container-modal-difficult-goal'"
            for-input="goal-complete-difficult"
          )
            TextInput(
              id="goal-complete-difficult"
              :value="goal.difficulty.name"
              :editable="false"
              :disabled="true"
            )
          AchievementModalField(
            :label="$t('achievement-modal.form-achievement.dateProgress.label')"
            :tool-content="$t('achievement-modal.form-achievement.dateProgress.hint')"
            :tool-class="'tool-tip__mark-container-modal-dateProgress'"
            for-input="goal-complete-date"
          )
            DateSelect(
              id="goal-complete-date"
              :placeholder="$t('achievement-modal.form-achievement.comment.placeholder')"
              :date.sync="dateProgress"
              :yearFromNow="true"
              :is-valid="dateProgressIsFull"
              :validations.sync="date.validations"
            )
          AchievementModalField(
            :label="$t('goal-complete-modal.geo.label')"
            :tool-content="$t('achievement-modal.form-achievement.placement.hint')"
            :tool-class="'tool-tip__mark-container-modal-placement'"
            for-input="goal-complete-geo"
          )
            CityInput(
              id="goal-complete-geo"
              :placeholder="$t('goal-complete-modal.geo.placeholder')"
              :cities.sync="geo.options"
              :city.sync="goal.geo"
              :search.sync="geo.search"
              :is-open.sync="geo.isOpen"
              :rules="getGeoRules"
              :is-valid="geoIsValid"
              :validations.sync="geo.validations"
              :disabled="formLoading"
              :is-subject="true"
            )

          AchievementModalField(
            :label="$t('goal-complete-modal.comment.label')"
            :tool-content="$t('achievement-modal.form-achievement.comment.hint')"
            :tool-class="'tool-tip__mark-container-modal-comment'"
            for-input="goal-complete-comment"
          )
            .goal-complete-modal__form-text-area
              TextArea(
                id="goal-complete-comment"
                v-model="goal.comment"
                :rules="getCommentRules"
                :validations.sync="comment.validations"
                :is-valid="commentIsValid"
                :disabled="formLoading"
              )

          AchievementModalField(
            :label="$t('goal-complete-modal.subcategories.label')"
            :tool-content="subcategoriesToolTip"
            :tool-class="'tool-tip__mark-container-modal-subcategories'"
          )
            .goal-complete-modal__form-subcategories
              Tag(
                v-for="subcategory in goal.subcategories"
                :key="subcategory.id"
              ) {{ subcategory.name }}

          AchievementModalField(
            :tool-content="$t('achievement-modal.form-achievement.files.hint')"
            :tool-class="'tool-tip__mark-container-modal-files'"
            class="align-items-center"
          )
            FileInput(
              id="goal-complete-files"
              v-model="files.value"
              :rules="getFilesRules"
              :validations.sync="files.validations"
              :is-valid="filesIsValid"
              :collection-names="collectionNames"
              :disabled="formLoading"
              :loading.sync="files.loading"
            ) {{ $t('goal-complete-modal.files.title') }}

          AchievementModalField(
            :show-tool-tip="false"
          )
            .goal-complete-modal__form-submit
              Button(:submit="true") {{ $t('goal-complete-modal.submit') }}
                template(slot="before")
                  .goal-complete-modal__form-submit-checkbox
                    CheckBoxSVG
      SuccessMediaModal(
        v-else
        @submitSuccess="submitSuccess"
      )
</template>

<script>
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import AchievementModalField from '../AchievementModal/AchievementModalField/AchievementModalField.vue'
import CityInput from '../../Interactives/Inputs/CityInput/CityInput.vue'
import DateSelect from '../../Interactives/Inputs/DateSelect/DateSelect.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'
import Multiselector from '../../Interactives/Inputs/Multiselector/Multiselector.vue'
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo.vue'
import TextInput from '../../Interactives/Inputs/TextInput/TextInput.vue'
import TextArea from '../../Interactives/Inputs/TextArea/TextArea.vue'
import FileInput from '../../Interactives/Inputs/FileInput/FileInput.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import Tag from '../../General/Tag/Tag.vue'
import SuccessMediaModal from '../SuccessMediaModal/SuccessMediaModal.vue'
import { EmptyArrayRule, RequiredRule } from '../../../assets/js/validation-rules'
import CheckBoxSVG from '../../SVG/CheckBoxSVG.vue'
import ProgressConditions from '../../../assets/js/constatns/progress-conditions'
import { makeAchievement } from '../../../plugins/api/progress'
import { DateValue } from '../../../assets/js/helper-classes'
import ProgressDifficulties from '../../../assets/js/constatns/progress-diffculties'

export default {
  components: {
    AdjacentModalContainer,
    ModalCloseSVG,
    AchievementModalField,
    CityInput,
    DateSelect,
    Multiselector,
    MobileBackTo,
    TextInput,
    TextArea,
    FileInput,
    Button,
    Tag,
    SuccessMediaModal,
    CheckBoxSVG,
  },
  props: {
    goal: {
      required: true,
      type: Object,
    },
  },
  data() {
    this.$t.bind(this)
    let dateProgress = new Date().toISOString().substring(0, 10)
    const [year, month, day] = dateProgress.split('-')
    dateProgress = new DateValue({ year, month, day })
    return {
      isSuccess: false,
      formLoading: false,
      collectionNames: {
        image: 'progress_image',
        video: 'progress_video',
      },
      geo: {
        value: [],
        validations: [],
        search: '',
        options: [],
        loading: false,
        isOpen: false,
      },
      dateProgress,
      date: {
        validations: [],
      },
      comment: {
        validations: [],
      },
      files: {
        value: [],
        validations: [],
        loading: false,
      },
    }
  },
  computed: {
    getGeoRules() {
      return [new RequiredRule(this.$t('goal-complete-modal.geo.messages.required'))]
    },
    getCommentRules() {
      return [new RequiredRule(this.$t('goal-complete-modal.comment.messages.required'))]
    },
    getFilesRules() {
      if (this.goal.condition_id === ProgressConditions.PhotoOrVideo) {
        return [new EmptyArrayRule(this.$t('goal-complete-modal.files.messages.required'))]
      }
      return []
    },
    geoIsValid() {
      return !this.geo.validations.filter(k => !k.isValid).length
    },
    dateProgressIsFull() {
      return (
        (this.dateProgress.day === undefined || this.dateProgress.day !== null) &&
        (this.dateProgress.month === undefined || this.dateProgress.month !== null) &&
        (this.dateProgress.year === undefined || this.dateProgress.year !== null)
      )
    },
    dateProgressFormat() {
      // eslint-disable-next-line
      return `${this.dateProgress.year}-${this.dateProgress.month}-${this.dateProgress.day}`
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
    goalDifficultContentToolTip() {
      return `${this.$t('achievement-modal.form-goal.difficult.hint')}<br />${this.$t(
        'achievement-modal.form-goal.difficult.hint-easy',
      )}<br />${this.$t('achievement-modal.form-goal.difficult.hint-medium')}<br />${this.$t(
        'achievement-modal.form-goal.difficult.hint-hard',
      )}<br />${this.$t('achievement-modal.form-goal.difficult.hint-extra')}`
    },
  },
  methods: {
    close() {
      this.$emit('close')
    },
    submitSuccess() {
      const message =
        this.goal.difficulty_id === ProgressDifficulties.Simple
          ? this.$t('messages.achievement-success-create')
          : this.$t('messages.achievement-go-to-moderate')
      this.$emit('submit', message)
      this.close()
    },
    submit() {
      setTimeout(async () => {
        if (!this.formIsValid) return
        this.formLoading = true
        await makeAchievement({
          id: this.goal.id,
          is_target: 1,
          geo: this.goal.geo ? this.goal.geo.name : null,
          achieved_at: this.dateProgressFormat,
          comment: this.goal.comment,
          files_ids: this.files.value.map(k => k.id),
        })
        if (this.files.value.length !== 0) {
          this.isSuccess = true
        } else {
          this.submitSuccess()
        }
      })
    },
  },
}
</script>

<style lang="stylus">
@import "goal-complete-modal.styl"
</style>
