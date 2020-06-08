import { setHeaderAxios } from './auth'
import { setAxiosLang } from '../plugins/i18n'

export const state = () => ({
  screenWidth: 0,
  screenHeight: 0,
  headerSearch: '',
  numberOfHint: -1,
})

export const mutations = {
  setScreenWidth(st, width) {
    if (process.browser) {
      st.screenWidth = width
    }
  },
  setScreenHeight(st, height) {
    if (process.browser) {
      st.screenHeight = height
    }
  },
  setHeaderSearch(st, headerSearch) {
    st.headerSearch = headerSearch
  },
  increaseNumberOfHint(st) {
    st.numberOfHint += 1
  },
  setNumberOfHint(st, number) {
    st.numberOfHint = number
  },
}

export const actions = {
  async nuxtServerInit({ dispatch, commit }, { req, app }) {
    setAxiosLang(app.i18n.locale)
    if (req.session && req.session.access_token) {
      // eslint-disable-next-line camelcase
      const { access_token, refresh_token } = req.session
      setHeaderAxios({ access_token, refresh_token })
      commit('auth/setTokens', { access_token, refresh_token })
      const user = await dispatch('auth/loadUser')
      if (!user && !user.status) {
        await dispatch('auth/signOut')
        req.session.access_token = null
        req.session.refresh_token = null
      }
    }
  },
  nuxtClientInit(store, ctx) {
    setTimeout(() => {
      setAxiosLang(ctx.app.i18n.locale)
      store.commit('setScreenWidth', window.innerWidth)
      store.commit('setScreenHeight', window.innerHeight)
      if (store.getters['auth/authorized']) {
        setHeaderAxios(store.state.auth.tokens)
      }
      window.addEventListener(
        'resize',
        e => {
          store.commit('setScreenWidth', e.target.innerWidth)
          store.commit('setScreenHeight', e.target.innerHeight)
        },
        { passive: true },
      )
    })
  },
}
