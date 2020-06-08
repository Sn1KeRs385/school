<template lang="pug">
  AdjacentModalContainer(@close="close")
    .goal-edit-modal__container
      .goal-edit-modal__back-to-profile
        MobileBackTo(@click.native="close") {{ $t('goal-edit-modal.back-to') }}
      .goal-edit-modal__close(@click="close")
        ModalCloseSVG
      .goal-edit-modal
        form.goal-edit-modal__form(@submit.prevent="submit")
          .goal-edit-modal__form-title {{ $t('goal-edit-modal.title') }}
          AchievementModalField(
            :label="$t('goal-edit-modal.category.label')"
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
            :label="$t('goal-edit-modal.name.label')"
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
            :label="$t('goal-edit-modal.condition.label')"
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
            :label="$t('goal-edit-modal.difficult.label')"
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
            :label="$t('goal-edit-modal.comment.label')"
            :tool-content="$t('achievement-modal.form-achievement.comment.hint')"
            :tool-class="'tool-tip__mark-container-modal-comment'"
            for-input="goal-complete-comment"
          )
            .goal-edit-modal__form-text-area
              TextArea(
                id="goal-complete-comment"
                v-model="goal.comment"
                :rules="getCommentRules"
                :validations.sync="comment.validations"
                :is-valid="commentIsValid"
                :disabled="formLoading"
              )

          AchievementModalField(
            :label="$t('goal-edit-modal.subcategories.label')"
            :tool-content="subcategoriesToolTip"
            :tool-class="'tool-tip__mark-container-modal-subcategories'"
          )
            .goal-edit-modal__form-subcategories
              Tag(
                v-for="subcategory in goal.subcategories"
                :key="subcategory.id"
              ) {{ subcategory.name }}

          AchievementModalField(
            :show-tool-tip="false"
          )
            .goal-edit-modal__form-submit
              Button(:submit="true") {{ $t('goal-edit-modal.submit') }}
</template>

<script>
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import AchievementModalField from '../AchievementModal/AchievementModalField/AchievementModalField.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'
import Multiselector from '../../Interactives/Inputs/Multiselector/Multiselector.vue'
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo.vue'
import TextInput from '../../Interactives/Inputs/TextInput/TextInput.vue'
import TextArea from '../../Interactives/Inputs/TextArea/TextArea.vue'
import FileInput from '../../Interactives/Inputs/FileInput/FileInput.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import Tag from '../../General/Tag/Tag.vue'
import { RequiredRule } from '../../../assets/js/validation-rules'
import { updateGoal } from '../../../plugins/api/progress'
import SuccessMediaModal from '../SuccessMediaModal/SuccessMediaModal'

export default {
  components: {
    SuccessMediaModal,
    AdjacentModalContainer,
    ModalCloseSVG,
    AchievementModalField,
    Multiselector,
    MobileBackTo,
    TextInput,
    TextArea,
    FileInput,
    Button,
    Tag,
  },
  props: {
    goal: {
      required: true,
      type: Object,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      formLoading: false,
      collectionNames: {
        image: 'progress_image',
        video: 'progress_video',
      },
      changedData: '',
      comment: {
        validations: [],
      },
    }
  },
  computed: {
    getCommentRules() {
      return [new RequiredRule(this.$t('goal-edit-modal.comment.messages.required'))]
    },
    commentIsValid() {
      return !this.comment.validations.filter(k => !k.isValid).length
    },
    formIsValid() {
      return this.commentIsValid
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
    submit() {
      setTimeout(async () => {
        if (!this.formIsValid) return
        this.formLoading = true
        const { data } = await updateGoal({
          id: this.goal.id,
          comment: this.goal.comment,
        })
        this.$emit('submit', data, this.$t('messages.goal-change'))
        this.close()
      })
    },
  },
}
</script>

<style lang="stylus">
@import "goal-edit-modal.styl"
</style>
