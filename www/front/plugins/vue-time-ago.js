import Vue from 'vue'
import TimeAgo from 'javascript-time-ago'

import en from 'javascript-time-ago/locale/en'
import ru from 'javascript-time-ago/locale/ru'

TimeAgo.addLocale(en)
TimeAgo.addLocale(ru)

export default ({ app }) => {
  Vue.prototype.$timeAgo = (datetime, locale = app.i18n.locale) => {
    const timeAgo = new TimeAgo(locale)
    return timeAgo.format(datetime)
  }
}
