<template lang="pug">
  div
    .date-select
      input(:id="id" ref="input" type="hidden")
      .date-select__input-day
        MultiselectorID(
          :id="`${id}-day`"
          :value="date.day"
          :is-open.sync="daySelectIsOpen"
          :options="getDays"
          placeholder="31"
          :is-valid="isValid"
          @update:value="changeDay"
        )
      .date-select__input-month
        MultiselectorID(
          :id="`${id}-month`"
          :value="date.month"
          :is-open.sync="monthSelectIsOpen"
          :options="getMonth"
          :placeholder="getMonth[0].name"
          :is-valid="isValid"
          @update:value="changeMonth"
        )
      .date-select__input-year
        MultiselectorID(
          :id="`${id}-year`"
          :value="date.year"
          :is-open.sync="yearSelectIsOpen"
          :options="getYears"
          placeholder="1969"
          :is-valid="isValid"
          @update:value="changeYear"
        )
    ValidationList(
      :validations="validations"
    )
</template>

<script>
import moment from 'moment'
import MultiselectorID from '../Multiselector/MultiselectorID.vue'
import InputMixin from '../../../../assets/js/vue-mixins/input'
import ValidationList from '../ValidationList/ValidationList.vue'

export default {
  components: {
    MultiselectorID,
    ValidationList,
  },
  mixins: [InputMixin('getInput', 'date', 'update:date')],
  props: {
    id: {
      required: true,
      type: [String, Number],
    },
    date: {
      type: Object,
      default: () => ({
        day: null,
        month: null,
        year: null,
      }),
    },
    yearFromNow: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      daySelectIsOpen: false,
      monthSelectIsOpen: false,
      yearSelectIsOpen: false,
    }
  },
  computed: {
    getInput() {
      return this.$refs.input
    },
    getYears() {
      const years = []
      const fromLoopParam = this.yearFromNow ? moment().year() : moment().year() - 14
      for (let i = fromLoopParam; i >= moment().year() - 114; i -= 1) {
        years.push(i)
      }
      return years.map(k => ({ id: k, name: k }))
    },
    getMonth() {
      return moment
        .localeData(this.$i18n.locale)
        .months()
        .map((k, i) => ({ id: i + 1, name: k }))
    },
    getDays() {
      const dayInMonth = moment(`${moment().year()}-${this.date.month}`, 'YYYY-M').daysInMonth()
      return [...Array(dayInMonth).keys()].map(k => ({ id: k + 1, name: k + 1 }))
    },
    currentMonth() {
      return this.date.month
    },
    currentDay() {
      return this.date.day
    },
    lastDayInMonth() {
      return this.getDays[this.getDays.length - 1]
    },
  },
  watch: {
    currentMonth() {
      if (this.currentDay > this.lastDayInMonth.id) {
        this.changeDay(1)
      }
    },
  },
  methods: {
    changeDay(day) {
      this.$emit('update:date', { ...this.date, day })
    },
    changeMonth(month) {
      this.$emit('update:date', { ...this.date, month })
    },
    changeYear(year) {
      this.$emit('update:date', { ...this.date, year })
    },
  },
}
</script>

<style lang="stylus">
@import "date-select.styl"
</style>
