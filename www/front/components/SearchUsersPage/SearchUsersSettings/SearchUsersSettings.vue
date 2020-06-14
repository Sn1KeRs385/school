<template lang="pug">
  form.search-users-settings(@submit.prevent="submit")
    p.search-users-settings__title {{ $t('search-user.settings.title') }}
    .search-users-settings__param
      CityInput(
        id="search-users-settings-cities"
        :label="$t('search-user.settings.cities.label')"
        :placeholder="$t('search-user.settings.cities.placeholder')"
        :cities.sync="city.options"
        :city.sync="city.value"
        :search.sync="city.search"
        :is-open.sync="city.isOpen"
        :multiply="true"
        :disabled="false"
      )
    .search-users-settings__param
      Multiselector(
        id="search-users-settings-countries"
        :label="$t('search-user.settings.countries.label')"
        :placeholder="$t('search-user.settings.countries.placeholder')"
        :with-toggle="false"
        :disabled="true"
      )
    .search-users-settings__param
      RangeInput(
        id="search-users-settings-age"
        label="Возраст"
        :min="2"
        :max="100"
        :min-value="minAge"
        :max-value="maxAge"
        @update:min-value="updateMinAge"
        @update:max-value="updateMaxAge"
        :placeholders="$t('search-user.settings.age.placeholders')"
        :disabled="loading"
      )
    .search-users-settings__param
      .search-users-settings__param-title {{ $t('search-user.settings.sex.title') }}
      .search-users-settings__radio
        RadioButton(
          id="search-users-settings-male"
          name="search-users-settings-sex"
          :label="$t('search-user.settings.sex.male')"
          :option="1"
          v-model="male"
          :disabled="loading"
        )
      .search-users-settings__radio
        RadioButton(
          id="search-users-settings-female"
          name="search-users-settings-sex"
          :label="$t('search-user.settings.sex.female')"
          :option="0"
          v-model="male"
          :disabled="loading"
        )
      .search-users-settings__radio
        RadioButton(
          id="search-users-settings-any-male"
          name="search-users-settings-sex"
          :label="$t('search-user.settings.sex.any')"
          :option="null"
          v-model="male"
          :disabled="loading"
        )
    .search-users-settings__buttons
      .search-users-settings__clear
        Button(
          :secondary="true"
          min-height="40px"
          :disabled="loading || settingsClear"
          @click.native="clearSettings"
        ) {{ $t('search-user.settings.reset') }}
      .search-users-settings__submit
        Button(
          :submit="true"
          min-height="40px"
          :disabled="loading || !settingsChanged"
        ) {{ $t('search-user.settings.submit') }}

</template>

<script>
import Multiselector from '../../Interactives/Inputs/Multiselector/Multiselector.vue'
import RangeInput from '../../Interactives/Inputs/RangeInput/RangeInput.vue'
import RadioButton from '../../Interactives/Inputs/RadioButton/RadioButton.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import CityInput from '../../Interactives/Inputs/CityInput/CityInput.vue'

export default {
  components: {
    Multiselector,
    RangeInput,
    RadioButton,
    Button,
    CityInput,
  },
  props: {
    loading: {
      required: true,
      type: Boolean,
    },
    settings: {
      required: true,
      type: Object,
    },
    minAge: {
      required: true,
      type: null,
    },
    maxAge: {
      required: true,
      type: null,
    },
    name: {
      required: true,
      type: String,
    },
  },
  data() {
    return {
      settingsChanged: false,
      city: {
        value: [],
        search: '',
        options: [],
        loading: false,
        isOpen: false,
      },
    }
  },
  computed: {
    male: {
      get() {
        return this.settings.male
      },
      set(male) {
        this.settings.male = male
      },
    },
    cities() {
      return this.city.value
    },
    settingsClear() {
      return (
        !this.name &&
        !this.settings.countries.length &&
        !this.cities.length &&
        !this.minAge &&
        !this.maxAge &&
        this.settings.male === null
      )
    },
  },
  watch: {
    settings: {
      handler() {
        this.settingsChanged = true
      },
      deep: true,
    },
    cities() {
      this.settingsChanged = true
    },
    minAge() {
      this.settingsChanged = true
    },
    maxAge() {
      this.settingsChanged = true
    },
  },
  methods: {
    updateMinAge(minAge) {
      this.$emit('update:minAge', minAge)
    },
    updateMaxAge(maxAge) {
      this.$emit('update:maxAge', maxAge)
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
    submit() {
      if (!this.settingsChanged) {
        return
      }
      if (!this.maxAge && !this.minAge) {
        this.settings.borned.start_date = null
        this.settings.borned.end_date = null
      } else {
        this.settings.borned.start_date = this.ageToDate(this.maxAge, false)
        this.settings.borned.end_date = this.ageToDate(this.minAge, true)
      }
      this.settings.cities = this.cities.map(k => k.id)
      this.$emit('settingsUpdate', this.settings)
      this.$nextTick(() => {
        this.settingsChanged = false
      })
    },
    clearSettings() {
      this.$emit('clearSettings')
      this.city.value = []
      this.city.search = ''
      this.city.options = []
      this.$nextTick(() => {
        this.settingsChanged = false
      })
    },
  },
}
</script>

<style lang="stylus">
@import "search-users-settings.styl"
</style>
