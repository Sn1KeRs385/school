<!--suppress ALL -->
<template lang="pug">
  form.make-goal-form(@submit.prevent="submit")
    AchievementModalField(
      :label="$t('achievement-modal.form-goal.name.label')"
      :tool-content="$t('achievement-modal.form-goal.name.hint')"
      :tool-class="'tool-tip__mark-container-modal-name'"
      for-input="make-goal-name"
    )
      Multiselector(
        id="make-goal-name"
        by-name="title"
        :options="name.options"
        :searchable="true"
        :search="name.search"
        :is-open.sync="name.isOpen"
        @update:search="changeNameSearch"
        @update:value="changeNameValue"
        :loading="name.loading"
        :with-toggle="false"
        :placeholder="$t('achievement-modal.form-goal.name.placeholder')"
        :rules="name.rules"
        :is-valid="nameIsValid"
        :validations.sync="name.validations"
        :search-validate="true"
      )
    AchievementModalField(
      v-if="showHintLink"
      :show-tool-tip="false"
      class="achievement-modal-field__notification-text"
    ) {{ $t('achievement-modal.form-achievement.name.messages.new-item') }}
      a(
        @click="toggleAchievementModal"
        class="achievement-modal-field__toggle-button"
      ) {{ $t('achievement-modal.form-achievement.name.messages.new-item-link') }}
    .make-goal-form__description(v-if="name.value")
      AchievementModalDescription(:progress="name.value")
    .make-goal-subform
      .make-goal-subform__info-goal(v-if="showGoalForm")
        .info-goal__container-heading
          .info-goal__heading {{ $t('achievement-modal.form-achievement.heading') }}
          .info-goal__subheading {{ $t('achievement-modal.form-achievement.subheading') }}
        AchievementModalField(
          :label="$t('achievement-modal.form-goal.category.label')"
          :tool-content="$t('achievement-modal.form-goal.category.hint')"
          :tool-class="'tool-tip__mark-container-modal-category'"
          for-input="make-goal-category"
        )
          Multiselector(
            id="make-goal-category"
            :options="getCategories"
            :is-open.sync="category.isOpen"
            :value.sync="category.value"
            :is-valid="categoryIsValid"
            :rules="category.rules"
            :validations.sync="category.validations"
            :placeholder="$t('achievement-modal.form-goal.category.placeholder')"
          )
        AchievementModalField(
          v-if="!name.value"
          :label="$t('achievement-modal.form-goal.description.label')"
          :tool-content="$t('achievement-modal.form-goal.description.hint')"
          :tool-class="'tool-tip__mark-container-modal-description'"
          for-input="make-achievement-description"
        )
          .make-achievement-form__text-area
            TextArea(
              id="make-achievement-description"
              :placeholder="$t('achievement-modal.form-goal.description.placeholder')"
              v-model="description.value"
              :rules="description.rules"
              :is-valid="descriptionIsValid"
              :validations.sync="description.validations"
            )
        AchievementModalField(
          v-if="!name.value"
          :label="$t('achievement-modal.form-goal.condition.label')"
          :tool-content="$t('achievement-modal.form-goal.condition.hint')"
          :tool-class="'tool-tip__mark-container-modal-condition'"
          for-input="make-goal-condition"
        )
          Multiselector(
            id="make-goal-condition"
            by-name="title"
            :options="getConditions"
            :is-open.sync="condition.isOpen"
            :value.sync="condition.value"
            :placeholder="$t('achievement-modal.form-goal.condition.placeholder')"
            :rules="condition.rules"
            :is-valid="conditionIsValid"
            :validations.sync="condition.validations"
          )
        AchievementModalField(
          :label="$t('achievement-modal.form-achievement.placement.label')"
          :tool-content="$t('achievement-modal.form-achievement.placement.hint')"
          :tool-class="'tool-tip__mark-container-modal-placement'"
          for-input="make-achievement-placement"
        )
          CityInput(
            id="make-achievement-placement"
            :placeholder="getGeoPlaceholder"
            :cities.sync="geo.options"
            :city.sync="geo.value"
            :search.sync="geo.search"
            :is-open.sync="geo.isOpen"
            :rules="getGeoRules"
            :dependence="condition.value"
            :is-valid="geoIsValid"
            :validations.sync="geo.validations"
            :is-subject="true"
          )
        AchievementModalField(
          v-if="!name.value"
          :label="$t('achievement-modal.form-goal.difficult.label')"
          :tool-content="goalDifficultContentToolTip"
          :tool-class="'tool-tip__mark-container-modal-difficult-goal'"
          for-input="make-goal-difficult"
        )
          Multiselector(
            id="make-goal-difficult"
            :options="getDifficulties"
            :is-open.sync="difficult.isOpen"
            :value.sync="difficult.value"
            :placeholder="$t('achievement-modal.form-goal.difficult.placeholder')"
            :rules="difficult.rules"
            :is-valid="difficultIsValid"
            :validations.sync="difficult.validations"
          )
        AchievementModalField(
          v-if="!name.value"
          :label="$t('achievement-modal.form-goal.subcategories.label')"
          :tool-content="subcategoriesToolTip"
          :tool-class="'tool-tip__mark-container-modal-subcategories'"
          for-input="make-goal-subcategories"
        )
          Multiselector(
            id="make-goal-subcategories"
            :placeholder="$t('achievement-modal.form-goal.subcategories.placeholder')"
            :multiply="true"
            :options="subcategories.options"
            :searchable="true"
            :search="subcategories.search"
            :is-open.sync="subcategories.isOpen"
            :value="subcategories.value"
            :with-toggle="false"
            :loading="subcategories.loading"
            @update:search="changeSubCategoriesSearch"
            @update:value="changeSubCategoriesValue"
            :close-by-select="false"
            :rules="subcategories.rules"
            :is-valid="subcategoriesIsValid"
            :validations.sync="subcategories.validations"
          )

        AchievementModalField(
          v-else
          :label="$t('achievement-modal.form-achievement.subcategories.label')"
        )
          Tag(
            v-for="subcategory in name.value.subcategories"
            :key="subcategory.id"
          ) {{ subcategory.name }}

      AchievementModalField(
        v-if="showSubForm"
        :show-tool-tip="false"
      )
        .make-goal-form__submit
          Button(
            :submit="true"
            :loading="form.loading"
          ) {{ $t('achievement-modal.form-goal.submit') }}

</template>

<script>
import AchievementModalField from '../AchievementModalField/AchievementModalField.vue'
import CityInput from '../../../Interactives/Inputs/CityInput/CityInput.vue'
import Multiselector from '../../../Interactives/Inputs/Multiselector/Multiselector.vue'
import TextInput from '../../../Interactives/Inputs/TextInput/TextInput.vue'
import TextArea from '../../../Interactives/Inputs/TextArea/TextArea.vue'
import Button from '../../../Interactives/Controls/Button/Button.vue'
import { RequestTimer } from '../../../../assets/js/helper-classes'
import { searchAchievements, makeGoal } from '../../../../plugins/api/progress'
import AchievementModalDescription from '../../../ProgressDescription/ProgressDescription.vue'
import { CustomRule, EmptyArrayRule, RequiredRule } from '../../../../assets/js/validation-rules'
import Errors from '../../../../plugins/api/errors'
import { searchSubcategories } from '../../../../plugins/api/user'
import Tag from '../../../General/Tag/Tag.vue'
import ProgressConditions from '../../../../assets/js/constatns/progress-conditions'

export default {
  components: {
    Tag,
    AchievementModalField,
    CityInput,
    Multiselector,
    TextInput,
    TextArea,
    Button,
    AchievementModalDescription,
  },
  data() {
    const $t = this.$t.bind(this)
    return {
      nameRequestTimer: new RequestTimer(),
      subCategoryTimer: new RequestTimer(),
      category: {
        isOpen: false,
        value: null,
        rules: [new RequiredRule($t('achievement-modal.form-goal.category.messages.required'))],
        validations: [],
      },
      name: {
        isOpen: false,
        value: null,
        search: '',
        loading: false,
        options: [],
        rules: [
          new CustomRule(
            $t('achievement-modal.form-goal.name.messages.required'),
            () => {
              return Boolean(this.name.search)
            },
            'required',
          ),
          new CustomRule(
            $t('achievement-modal.form-goal.name.messages.min-length'),
            () => {
              if (!this.name.search) return true
              return this.name.search.length >= 3
            },
            'min-length',
          ),
        ],
        validations: [],
      },
      subcategories: {
        isOpen: false,
        loading: false,
        value: [],
        search: '',
        options: [...this.$store.state.progress.subCategories],
        rules: [
          new EmptyArrayRule($t('achievement-modal.form-goal.subcategories.messages.required')),
        ],
        validations: [],
      },
      comment: {
        value: '',
        rules: [new RequiredRule($t('achievement-modal.form-goal.comment.messages.required'))],
        validations: [],
      },
      description: {
        value: '',
        rules: [new RequiredRule($t('achievement-modal.form-goal.description.messages.required'))],
        validations: [],
      },
      difficult: {
        isOpen: false,
        value: null,
        rules: [new RequiredRule($t('achievement-modal.form-goal.difficult.messages.required'))],
        validations: [],
      },
      condition: {
        isOpen: false,
        value: null,
        rules: [new RequiredRule($t('achievement-modal.form-goal.condition.messages.required'))],
        validations: [],
      },
      geo: {
        value: [],
        validations: [],
        search: '',
        options: [],
        loading: false,
        isOpen: false,
      },
      errorHandlers: {
        [Errors.TARGET_ERROR]: () => {
          this.name.validations = [
            {
              name: Errors.TARGET_ERROR,
              message: $t('achievement-modal.form-goal.name.messages.exists'),
            },
          ]
        },
      },
      form: {
        loading: false,
      },
      createState: '',
      showGoalForm: false,
    }
  },
  computed: {
    getCategories() {
      return this.$store.state.progress.categories
    },
    getSubCategories() {
      return this.$store.state.progress.subCategories
    },
    getDifficulties() {
      return this.$store.state.progress.difficulties
    },
    getConditions() {
      return this.$store.state.progress.conditions
    },
    categoryIsValid() {
      return !this.category.validations.filter(k => !k.isValid).length
    },
    nameIsValid() {
      return !this.name.validations.filter(k => !k.isValid).length
    },
    conditionIsValid() {
      return !this.condition.validations.filter(k => !k.isValid).length
    },
    geoIsValid() {
      return !this.geo.validations.filter(k => !k.isValid).length
    },
    difficultIsValid() {
      return !this.difficult.validations.filter(k => !k.isValid).length
    },
    subcategoriesIsValid() {
      return !this.subcategories.validations.filter(k => !k.isValid).length
    },
    commentIsValid() {
      return !this.comment.validations.filter(k => !k.isValid).length
    },
    descriptionIsValid() {
      return !this.description.validations.filter(k => !k.isValid).length
    },
    formIsValid() {
      return (
        this.categoryIsValid &&
        this.nameIsValid &&
        this.conditionIsValid &&
        this.geoIsValid &&
        this.difficultIsValid &&
        this.subcategoriesIsValid &&
        this.commentIsValid &&
        this.descriptionIsValid
      )
    },
    getGeoRules() {
      if (this.condition.value && this.condition.value.id === ProgressConditions.Positioning) {
        return [
          // eslint-disable-next-line
          new EmptyArrayRule(this.$t('achievement-modal.form-achievement.placement.messages.empty')),
        ]
      }
      return []
    },
    getGeoPlaceholder() {
      if (this.getGeoRules.length !== 0) {
        return this.$t('achievement-modal.form-achievement.placement.placeholder.required')
      }
      return this.$t('achievement-modal.form-achievement.placement.placeholder.empty')
    },
    showSubForm() {
      return this.showGoalForm || this.name.value
    },
    showHintLink() {
      return this.createState && !this.showGoalForm
    },
    nameIsLoading() {
      return this.name.loading
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
  watch: {
    nameIsLoading(isLoading) {
      if (!isLoading) {
        this.resetForm()
        if (!this.name.options.length && this.name.search.length >= 3) {
          this.createState = true
        } else {
          this.createState = false
          this.showGoalForm = false
        }
      }
    },
  },
  methods: {
    validateSubmit(result) {
      result.errors.forEach(k => {
        const handler = this.errorHandlers[k.message]
        if (handler) {
          handler(result)
        } else {
          console.error('Tolya ti koe-chto ne obrabotal', k)
        }
      })
    },
    changeSubCategoriesSearch(search) {
      this.subcategories.search = search
      if (search.length >= 2) {
        this.subcategories.loading = true
        this.subCategoryTimer.start(() => this.loadSubcategories(search), 750)
      } else {
        this.subCategoryTimer.clear()
        this.subcategories.options = [...this.getSubCategories]
        this.subcategories.loading = false
      }
    },
    changeSubCategoriesValue(options) {
      this.subcategories.value = options
    },
    async loadSubcategories(search) {
      const { data } = await searchSubcategories({
        name: search,
      })
      this.subcategories.options = data
      this.subcategories.loading = false
    },
    changeNameSearch(search) {
      this.name.search = search
      this.name.value = null
      if (search.length >= 2) {
        this.name.loading = true
        this.nameRequestTimer.start(() => this.loadNames(search), 750)
      } else {
        this.nameRequestTimer.clear()
        this.name.options = []
        this.name.loading = false
      }
    },
    changeNameValue(opt) {
      this.name.value = opt
      this.name.search = opt.title
      this.condition.value = opt.condition
      this.category.value = opt.category_id
      this.geo.value = opt.required_geo
      this.difficult.value = opt.difficulty
      this.subcategories.value = opt.subcategories
      this.description.value = opt.description
    },
    async loadNames(search) {
      const { data } = await searchAchievements({
        title: search,
      })
      this.name.options = data
      this.name.loading = false
    },
    submit() {
      setTimeout(async () => {
        if (!this.formIsValid) return
        this.form.loading = true
        const result = await makeGoal({
          progress_id: this.name.value ? this.name.value.id : null,
          category_id: this.name.value ? this.category.value : this.category.value.id,
          difficulty_id: this.difficult.value.id,
          title: this.name.search,
          required_geo: this.geo.value ? this.geo.value.name : null,
          description: this.description.value,
          subcategories: this.subcategories.value.map(k => k.id),
          condition_id: this.condition.value.id,
        })
        if (!result.status) {
          this.validateSubmit(result)
          this.form.loading = false
          return
        }
        this.$emit('submit')
      })
    },
    toggleAchievementModal() {
      this.showGoalForm = !this.showGoalForm
      this.createState = 0
    },
    resetForm() {
      this.category.value = null
      this.condition.value = null
      this.difficult.value = null
      this.subcategories.value = []
    },
  },
}
</script>

<style lang="stylus">
@import "make-goal-from.styl"
</style>
