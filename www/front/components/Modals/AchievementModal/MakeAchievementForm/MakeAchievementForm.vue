<template lang="pug">
  form.make-achievement-form(@submit.prevent="submit")
    AchievementModalField(
      :tool-content="$t('achievement-modal.form-achievement.name.hint')"
      :tool-class="'tool-tip__mark-container-modal-name'"
      :label="$t('achievement-modal.form-achievement.name.label')"
      :autocomplete=false
      for-input="make-achievement-name"
    )
      Multiselect(
        id="make-achievement-name"
        by-name="title"
        :options="name.options"
        :searchable="true"
        :search="name.search"
        :is-open.sync="name.isOpen"
        :with-toggle="false"
        @update:search="changeNameSearch"
        @update:value="changeNameValue"
        :loading="name.loading"
        :placeholder="$t('achievement-modal.form-achievement.name.placeholder')"
        :rules="name.rules"
        :is-valid="nameIsValid"
        :validations.sync="name.validations"
        :validate-on-change="false"
        :search-validate="true"
      )
    AchievementModalField(
      :show-tool-tip="false"
      v-if="showHintLink"
      class="achievement-modal-field__notification-text"
    ) {{ $t('achievement-modal.form-achievement.name.messages.new-item') }}
      a(
        @click="toggleAchievementModal"
        class="achievement-modal-field__toggle-button"
      ) {{ $t('achievement-modal.form-achievement.name.messages.new-item-link') }}
    .make-achievement-form__description(v-if="name.value")
      AchievementModalDescription(:progress="name.value")
    .make-achievement-subform
      .make-achievement-subform__info-achievement(v-if="showAchievementForm")
        .info-achievement__container-heading
          .info-achievement__heading {{ $t('achievement-modal.form-achievement.heading') }}
          .info-achievement__subheading {{ $t('achievement-modal.form-achievement.subheading') }}
        AchievementModalField(
          :label="$t('achievement-modal.form-achievement.category.label')"
          :tool-content="$t('achievement-modal.form-achievement.category.hint')"
          :tool-class="'tool-tip__mark-container-modal-category'"
          for-input="make-achievement-category"
        )
          Multiselect(
            id="make-achievement-category"
            :options="getCategories"
            :is-open.sync="category.isOpen"
            :is-valid="categoryIsValid"
            :rules="category.rules"
            :validations.sync="category.validations"
            :value.sync="category.value"
            v-model="category.value"
            :placeholder="$t('achievement-modal.form-achievement.category.placeholder')"
          )
        AchievementModalField(
          v-if="!name.value"
          :tool-content="$t('achievement-modal.form-achievement.description.hint')"
          :tool-class="'tool-tip__mark-container-modal-description'"
          :label="$t('achievement-modal.form-achievement.description.label')"
          for-input="make-achievement-description"
        )
          .make-achievement-form__text-area
            TextArea(
              id="make-achievement-description"
              :placeholder="$t('achievement-modal.form-achievement.description.placeholder')"
              v-model="description.value"
              :rules="description.rules"
              :is-valid="descriptionIsValid"
              :validations.sync="description.validations"
            )
        AchievementModalField(
          v-if="!name.value"
          :label="$t('achievement-modal.form-achievement.condition.label')"
          :tool-content="$t('achievement-modal.form-achievement.condition.hint')"
          :tool-class="'tool-tip__mark-container-modal-condition'"
          for-input="make-achievement-condition"
        )
          Multiselect(
            id="make-achievement-condition"
            by-name="title"
            :options="getConditions"
            :is-open.sync="condition.isOpen"
            :value.sync="condition.value"
            :placeholder="$t('achievement-modal.form-achievement.condition.placeholder')"
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
          :label="$t('achievement-modal.form-achievement.difficult.label')"
          :tool-content="achievementDifficultContentToolTip"
          :tool-class="'tool-tip__mark-container-modal-difficult'"
          for-input="make-achievement-difficult"
        )
          Multiselect(
            id="make-achievement-difficult"
            :options="getDifficulties"
            :is-open.sync="difficult.isOpen"
            :value.sync="difficult.value"
            :placeholder="$t('achievement-modal.form-achievement.difficult.placeholder')"
            :rules="difficult.rules"
            :is-valid="difficultIsValid"
            :validations.sync="difficult.validations"
          )
        AchievementModalField(
          v-if="!name.value"
          :label="$t('achievement-modal.form-achievement.subcategories.label')"
          :tool-content="subcategoriesToolTip"
          :tool-class="'tool-tip__mark-container-modal-subcategories'"
          for-input="make-achievement-subcategories"
        )
          Multiselect(
            id="make-achievement-subcategories"
            :placeholder="$t('achievement-modal.form-achievement.subcategories.placeholder')"
            :close-by-select="false"
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
            :rules="subcategories.rules"
            :is-valid="subcategoriesIsValid"
            :validations.sync="subcategories.validations"
          )
      .make-achievement-subform__commentary-achievement(v-if="showSubForm")
        .commentary-achievement__container-heading
          .commentary-achievement__heading {{ $t('achievement-modal.form-achievement.subform-heading') }}
        AchievementModalField(
          :label="$t('achievement-modal.form-achievement.dateProgress.label')"
          :tool-content="$t('achievement-modal.form-achievement.dateProgress.hint')"
          :tool-class="'tool-tip__mark-container-modal-dateProgress'"
          for-input="make-achievement-comment-date"
        )
          DateSelect(
            id="make-achievement-comment-date"
            :placeholder="$t('achievement-modal.form-achievement.comment.placeholder')"
            :date.sync="dateProgress"
            :yearFromNow="true"
            :rules="date.rules"
            :is-valid="dateProgressIsFull"
            :validations.sync="date.validations"
          )
        AchievementModalField(
          :label="$t('achievement-modal.form-achievement.placement.label')"
          :tool-content="$t('achievement-modal.form-achievement.placement.hint-myself')"
          :tool-class="'tool-tip__mark-container-modal-placement-myself'"
          for-input="make-achievement-comment-placement"
        )
          CityInput(
            id="make-achievement-comment-placement"
            :placeholder="getGeoPlaceholder"
            :cities.sync="geoComment.options"
            :city.sync="geoComment.value"
            :search.sync="geoComment.search"
            :is-open.sync="geoComment.isOpen"
            :rules="geoComment.rules"
            :is-valid="geoCommentIsValid"
            :validations.sync="geoComment.validations"
            :is-subject="true"
          )
        AchievementModalField(
          :label="$t('achievement-modal.form-achievement.comment.label')"
          :tool-content="$t('achievement-modal.form-achievement.comment.hint')"
          :tool-class="'tool-tip__mark-container-modal-comment'"
          for-input="make-achievement-comment"
        )
          .make-achievement-form__text-area
            TextArea(
              id="make-achievement-comment"
              :placeholder="$t('achievement-modal.form-achievement.comment.placeholder')"
              v-model="comment.value"
              :rules="comment.rules"
              :is-valid="commentIsValid"
              :validations.sync="comment.validations"
            )
        AchievementModalField(
          :tool-content="$t('achievement-modal.form-achievement.files.hint')"
          :tool-class="'tool-tip__mark-container-modal-files'"
          class="align-items-center achievement-modal-field-files"
        )
          FileInput(
            id="make-achievement-files"
            v-model="files.value"
            :validations.sync="files.validations"
            :is-valid="filesIsValid"
            :rules="getFilesRules"
            :dependence="condition.value"
            :collection-names="collectionNames"
          ) {{ $t('achievement-modal.form-achievement.files.label') }}
        AchievementModalField(:show-tool-tip="false")
          .make-achievement-form__submit
            Button(
              :submit="true"
              :loading="form.loading"
            ) {{ $t('achievement-modal.form-achievement.submit') }}
</template>

<script>
import AchievementModalField from '../AchievementModalField/AchievementModalField.vue'
import AchievementModalDescription from '../../../ProgressDescription/ProgressDescription.vue'
import CityInput from '../../../Interactives/Inputs/CityInput/CityInput.vue'
import TextInput from '../../../Interactives/Inputs/TextInput/TextInput.vue'
import TextArea from '../../../Interactives/Inputs/TextArea/TextArea.vue'
import Multiselect from '../../../Interactives/Inputs/Multiselector/Multiselector.vue'
import Button from '../../../Interactives/Controls/Button/Button.vue'
import FileInput from '../../../Interactives/Inputs/FileInput/FileInput.vue'
import DateSelect from '../../../Interactives/Inputs/DateSelect/DateSelect.vue'
import { searchAchievements, makeAchievement } from '../../../../plugins/api/progress'
import { RequestTimer, DateValue } from '../../../../assets/js/helper-classes'
import { CustomRule, EmptyArrayRule, RequiredRule } from '../../../../assets/js/validation-rules'
import ProgressConditions from '../../../../assets/js/constatns/progress-conditions'
import Errors from '../../../../plugins/api/errors'
import { searchSubcategories } from '../../../../plugins/api/user'
import Tag from '../../../General/Tag/Tag.vue'

export default {
  components: {
    Tag,
    AchievementModalField,
    CityInput,
    TextInput,
    TextArea,
    Multiselect,
    Button,
    FileInput,
    DateSelect,
    AchievementModalDescription,
  },
  data() {
    const $t = this.$t.bind(this)
    let dateProgress = new Date().toISOString().substring(0, 10)
    const [year, month, day] = dateProgress.split('-')
    dateProgress = new DateValue({ year, month, day })
    return {
      nameRequestTimer: new RequestTimer(),
      subCategoryTimer: new RequestTimer(),
      category: {
        isOpen: false,
        value: null,
        rules: [
          new RequiredRule($t('achievement-modal.form-achievement.category.messages.required')),
        ],
        validations: [],
      },
      name: {
        isOpen: false,
        value: null,
        search: '',
        loading: false,
        rules: [
          new CustomRule(
            $t('achievement-modal.form-achievement.name.messages.required'),
            () => {
              return Boolean(this.name.search)
            },
            'required',
          ),
          new CustomRule(
            $t('achievement-modal.form-achievement.name.messages.min-length'),
            () => {
              if (!this.name.search) return true
              return this.name.search.length >= 3
            },
            'min-length',
          ),
        ],
        validations: [],
        options: [],
      },
      subcategories: {
        isOpen: false,
        loading: false,
        value: [],
        search: '',
        options: [...this.$store.state.progress.subCategories],
        rules: [
          new EmptyArrayRule(
            $t('achievement-modal.form-achievement.subcategories.messages.required'),
          ),
        ],
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
      dateProgress,
      date: {
        rules: [
          new RequiredRule($t('achievement-modal.form-achievement.dateProgress.messages.required')),
        ],
        validations: [],
      },
      geoComment: {
        value: [],
        search: '',
        options: [],
        loading: false,
        isOpen: false,
        rules: [
          // eslint-disable-next-line
          new EmptyArrayRule($t('achievement-modal.form-achievement.placement.messages.required'))
          // new RequiredRule($t('achievement-modal.form-achievement.placement.messages.required')),
        ],
        validations: [],
      },
      comment: {
        value: '',
        rules: [
          new RequiredRule($t('achievement-modal.form-achievement.comment.messages.required')),
        ],
        validations: [],
      },
      description: {
        value: '',
        rules: [
          new RequiredRule($t('achievement-modal.form-achievement.description.messages.required')),
        ],
        validations: [],
      },
      difficult: {
        isOpen: false,
        value: null,
        rules: [
          new RequiredRule($t('achievement-modal.form-achievement.difficult.messages.required')),
        ],
        validations: [],
      },
      condition: {
        isOpen: false,
        value: null,
        rules: [
          new RequiredRule($t('achievement-modal.form-achievement.condition.messages.required')),
        ],
        validations: [],
      },
      collectionNames: {
        image: 'progress_image',
        video: 'progress_video',
      },
      files: {
        value: [],
        validations: [],
      },
      errorHandlers: {
        [Errors.USER_PROGRESS_EXISTS]: () => {
          this.name.validations = [
            {
              name: Errors.USER_PROGRESS_EXISTS,
              message: $t('achievement-modal.form-achievement.name.messages.exists'),
            },
          ]
        },
      },
      form: {
        loading: false,
      },
      createState: '',
      showAchievementForm: false,
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
    difficultIsValid() {
      return !this.difficult.validations.filter(k => !k.isValid).length
    },
    geoIsValid() {
      return !this.geo.validations.filter(k => !k.isValid).length
    },
    geoCommentIsValid() {
      return !this.geoComment.validations.filter(k => !k.isValid).length
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
    subcategoriesIsValid() {
      return !this.subcategories.validations.filter(k => !k.isValid).length
    },
    commentIsValid() {
      return !this.comment.validations.filter(k => !k.isValid).length
    },
    descriptionIsValid() {
      return !this.description.validations.filter(k => !k.isValid).length
    },
    filesIsValid() {
      return !this.files.validations.filter(k => !k.isValid).length
    },
    formIsValid() {
      return (
        this.categoryIsValid &&
        this.nameIsValid &&
        this.conditionIsValid &&
        this.difficultIsValid &&
        this.geoIsValid &&
        this.geoCommentIsValid &&
        this.dateProgressIsFull &&
        this.subcategoriesIsValid &&
        this.commentIsValid &&
        this.descriptionIsValid &&
        this.filesIsValid
      )
    },
    getFilesRules() {
      if (this.condition.value && this.condition.value.id === ProgressConditions.PhotoOrVideo) {
        return [
          new EmptyArrayRule(this.$t('achievement-modal.form-achievement.files.messages.empty')),
        ]
      }
      return []
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
      return this.showAchievementForm || this.name.value
    },
    showHintLink() {
      return this.createState && !this.showAchievementForm
    },
    nameIsLoading() {
      return this.name.loading
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
  watch: {
    nameIsLoading(isLoading) {
      if (!isLoading) {
        // Reset для перевыбора достижения с существующего на новое
        this.category.value = null
        this.condition.value = null
        this.difficult.value = null
        this.subcategories.value = []
        if (!this.name.options.length && this.name.search.length >= 3) {
          this.createState = true
        } else {
          this.createState = false
          this.showAchievementForm = false
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
    // Реворк: теперь ищем достижения по названию
    // Убрали поле category_id: this.category.value.id, из body
    async loadNames(search) {
      const { data } = await searchAchievements({
        title: search,
      })
      this.name.options = data
      this.name.loading = false
    },
    // Реворк: теперь есть обязательное поля achieved_at - дата выполнения достижения
    // required_geo - пользовательское местоположение
    submit() {
      setTimeout(async () => {
        if (!this.formIsValid) return
        this.form.loading = true
        const result = await makeAchievement({
          progress_id: this.name.value ? this.name.value.id : null,
          category_id: this.name.value ? this.category.value : this.category.value.id,
          difficulty_id: this.difficult.value.id,
          title: this.name.search,
          required_geo: this.geo.value ? this.geo.value.name : null,
          achieved_at: this.dateProgressFormat,
          geo: this.geoComment.value.name,
          comment: this.comment.value,
          description: this.description.value,
          subcategories: this.subcategories.value.map(k => k.id),
          condition_id: this.condition.value.id,
          files_ids: this.files.value.map(k => k.id),
        })
        if (!result.status) {
          this.validateSubmit(result)
          this.form.loading = false
          return
        }
        this.$emit('submit', {
          progressId: this.name.value ? this.name.value.id : null,
          difficultyId: this.difficult.value.id,
          filesCount: this.files.value.length,
        })
      })
    },
    toggleAchievementModal() {
      this.showAchievementForm = !this.showAchievementForm
      this.createState = 0
    },
  },
}
</script>

<style lang="stylus">
@import "make-achievement-form.styl"
</style>
