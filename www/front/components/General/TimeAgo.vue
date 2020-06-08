<template lang="pug">
  time(:datetime="dateTime") {{ localeDateTime }}
</template>

<script>
import moment from 'moment'
import 'moment-timezone'
import { Updater } from '../../assets/js/helper-classes'

export default {
  props: {
    dateTime: {
      required: true,
      type: String,
    },
    timeout: {
      type: null,
      default: false,
    },
    locale: {
      type: String,
      default: '',
    },
  },
  data() {
    const { dateTime, timeout, $timeAgo, locale, $i18n } = this
    const date = moment(dateTime)
      .utc(true)
      .unix()
    const localeDateTime = $timeAgo(date * 1000, locale || $i18n.locale)
    return {
      localeDateTime,
      updater: new Updater(timeout),
    }
  },
  computed: {
    getLocale() {
      return this.locale || this.$i18n.locale
    },
  },
  watch: {
    timeout(timeout) {
      this.updater.timeout = timeout
      if (timeout) {
        this.startUpdater()
      } else {
        this.stopUpdater()
      }
    },
    dateTime() {
      this.updateTimeAgo()
    },
  },
  mounted() {
    if (this.timeout) {
      this.startUpdater()
    }
  },
  beforeDestroy() {
    this.stopUpdater()
  },
  methods: {
    startUpdater() {
      this.updater.start(this.updateTimeAgo)
    },
    stopUpdater() {
      this.updater.stop()
    },
    updateTimeAgo() {
      const date = moment(this.dateTime)
        .utc(true)
        .unix()
      this.localeDateTime = this.$timeAgo(date * 1000, this.getLocale)
    },
  },
}
</script>
