<template lang="pug">
  form.rating-settings(@submit.prevent="submit")
    ToolTip(
      v-if="!ratingPageShowed"
      :content="getSettingsHints[2]"
      :hint-number="0"
      class="tool-tip__mark-container-header tool-tip__mark-container-header-settings"
    )
    .rating-settings__title {{ $t('rating.settings.title') }}
    .rating-settings__description {{ $t('rating.settings.description') }}
    .rating-settings__multiselector
      MultiselectorID(
        :id="`${preId}-rating-category`"
        :label="$t('rating.settings.category.label')"
        :options="getCategories"
        :value.sync="settings.category_id"
        :is-open.sync="category.isOpen"
        :disabled="loading"
      )
    .rating-settings__multiselector
      MultiselectorID(
        :id="`${preId}-rating-country`"
        :label="$t('rating.settings.country.label')"
        :placeholder="$t('rating.settings.country.placeholder')"
        :disabled="true"
      )
    .rating-settings__multiselector
      CityInput(
        :id="`${preId}-rating-city`"
        :label="$t('rating.settings.city.label')"
        :placeholder="$t('rating.settings.city.placeholder')"
        :cities.sync="city.options"
        :city.sync="city.value"
        :search.sync="city.search"
        :is-open.sync="city.isOpen"
      )
    .rating-settings__range-age
      RangeInput(
        :id="`${preId}-rating-age`"
        :label="$t('rating.settings.age.label')"
        :min="2"
        :max="100"
        :min-value.sync="minAge"
        :max-value.sync="maxAge"
        :placeholders="$t('rating.settings.age.placeholders')"
        :disabled="loading"
      )
    .rating-settings__checkbox
      CheckBox(
        :id="`${preId}-only-followers`"
        :label="$t('rating.settings.only-followers.label')"
        v-model="settings.show_followers"
        :disabled="loading"
      )
    .rating-settings__checkbox
      CheckBox(
        :id="`${preId}-translate-in-bord`"
        :label="$t('rating.settings.translate-in-bord.label')"
        v-model="settings.show_board"
        :disabled="loading"
      )
    .rating-settings__submit
      Button(
        :submit="true"
        :loading="loading"
        :disabled="loading || !edited"
      ) {{ $t('rating.settings.submit') }}

</template>

<script>
import moment from 'moment'
import MultiselectorID from '../../Interactives/Inputs/Multiselector/MultiselectorID.vue'
import Multiselector from '../../Interactives/Inputs/Multiselector/Multiselector.vue'
import GroupInputs from '../../General/GroupInputs/GroupInputs.vue'
import CheckBox from '../../Interactives/Inputs/CheckBox/CheckBox.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import { getRating, setRatingSettings } from '../../../plugins/api/rating'
import RangeInput from '../../Interactives/Inputs/RangeInput/RangeInput.vue'
import CityInput from '../../Interactives/Inputs/CityInput/CityInput.vue'
import ToolTip from '../../General/ToolTip/ToolTip.vue'

export default {
  components: {
    MultiselectorID,
    Multiselector,
    GroupInputs,
    CheckBox,
    Button,
    RangeInput,
    CityInput,
    ToolTip,
  },
  props: {
    preId: {
      type: String,
      default: '',
    },
    settings: {
      required: true,
      type: Object,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    ratingPageShowed: {
      type: Boolean,
      default: null,
    },
  },
  data() {
    this.$t.bind(this)
    const { city } = this.settings
    const minAge = this.dateToAge(this.settings.birth_end)
    const maxAge = this.dateToAge(this.settings.birth_start)
    return {
      minAge,
      maxAge,
      edited: false,
      category: {
        isOpen: false,
      },
      city: {
        value: city,
        search: city ? city.name : '',
        options: [],
        loading: false,
        isOpen: false,
      },
    }
  },
  computed: {
    getCategories() {
      return [
        { id: null, name: this.$t('rating.settings.category.no-select') },
        ...this.$store.state.progress.categories,
      ]
    },
    getCity() {
      return this.city.value
    },
    getSettingsHints() {
      return this.$t('settings.hints')
    },
  },
  watch: {
    getCity() {
      this.edited = true
    },
    minAge() {
      this.edited = true
    },
    maxAge() {
      this.edited = true
    },
    settings: {
      handler() {
        this.edited = true
      },
      deep: true,
    },
  },
  methods: {
    dateToAge(date) {
      if (!date) {
        return null
      }
      return new Date(Date.now()).getFullYear() - moment(date).year()
    },
    ageToDate(age, toMax) {
      if (!age && toMax) {
        // eslint-disable-next-line no-param-reassign
        age = 0
      } else if (!age && !toMax) {
        // eslint-disable-next-line no-param-reassign
        age = 100
      }
      const year = new Date(Date.now()).getFullYear() - age
      if (toMax) {
        return `${year}-12-31`
      }
      return `${year}-1-1`
    },
    async submit() {
      this.edited = false
      this.$emit('loadingChange', true)
      if (!this.maxAge && !this.minAge) {
        this.settings.birth_start = null
        this.settings.birth_end = null
      } else {
        this.settings.birth_start = this.ageToDate(this.maxAge, false)
        this.settings.birth_end = this.ageToDate(this.minAge, true)
      }
      this.settings.city_id = this.city.value ? this.city.value.id : null
      await setRatingSettings(this.settings)
      const result = await getRating({
        offset_rnk: 0,
        records: 15,
        direction: '>',
      })
      this.$emit('changeRatingList', result.data)
      this.$emit('loadingChange', false)
    },
  },
}
</script>

<style lang="stylus">
@import "rating-settings.styl"
</style>
