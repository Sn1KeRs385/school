import Vue from 'vue'
import axios from 'axios'

export function setAxiosLang(lang) {
  axios.defaults.headers['Accept-Language'] = lang
  axios.defaults.headers.common['Accept-Language'] = lang
  axios.defaults.headers.post['Accept-Language'] = lang
}

export default ({ app }) => {
  Vue.prototype.$localeLink = (path, locale = app.i18n.locale) => {
    if (locale === app.i18n.defaultLocale) {
      return `/${path}`
    }
    return `/${locale}/${path}`
  }
  app.i18n.beforeLanguageSwitch = (oldLocale, newLocale) => {
    setAxiosLang(newLocale)
  }
}
