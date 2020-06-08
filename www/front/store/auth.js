import axios from 'axios'
import { getUserByToken, signIn, signOut } from '../plugins/api/auth'
// import { PaginateList } from '../assets/js/helper-classes'
import { getUserAchievements, getUserGoals } from '../plugins/api/user'

export function setHeaderAxios(tokens = null) {
  if (tokens) {
    axios.defaults.headers.Authorization = `Bearer ${tokens.access_token}`
    axios.defaults.headers.common.Authorization = `Bearer ${tokens.access_token}`
    axios.defaults.headers.post.Authorization = `Bearer ${tokens.access_token}`
  } else {
    axios.defaults.headers.Authorization = ''
    axios.defaults.headers.common.Authorization = ''
    axios.defaults.headers.post.Authorization = ''
  }
}

export const state = () => ({
  user: null,
  tokens: null,
  achievements: null,
  goals: null,
})

export const mutations = {
  attachUser(auth, user) {
    auth.user = {
      ...auth.user,
      ...user,
    }
  },
  setUser(auth, user) {
    auth.user = user
  },
  setTokens(auth, tokens) {
    auth.tokens = tokens
  },
  setAchievements(auth, achievements) {
    auth.achievements = achievements
  },
  setGoals(auth, goals) {
    auth.goals = goals
  },
  setHints(auth, hints) {
    auth.user.viewed_hints = hints
  },
}

export const actions = {
  async updateAchievements(auth) {
    const { data } = await getUserAchievements(auth.state.user.id, { page: 1, records: 10 })
    auth.commit('setAchievements', data)
    return data
  },
  async updateGoals(auth) {
    const { data } = await getUserGoals(auth.state.user.id, { page: 1, records: 10 })
    auth.commit('setGoals', data)
    return data
  },
  async updateTokens({ commit, dispatch }, tokens = null) {
    if (tokens) {
      setHeaderAxios(tokens)
      commit('setTokens', tokens)
      await axios.post('/api/login', tokens)
      await dispatch('loadUser', tokens)
    } else {
      await signOut()
      setHeaderAxios()
      commit('setTokens', null)
      commit('setUser', null)
    }
  },
  async loadUser(store) {
    const user = await getUserByToken()
    if (!user && !user.status) {
      return user
    }
    await Promise.all([
     /* store.dispatch('progress/updateCategories', null, { root: true }),
      store.dispatch('progress/updateDifficulties', null, { root: true }),
      store.dispatch('progress/updateConditions', null, { root: true }),
      store.dispatch('progress/updateSubCategories', null, { root: true }),*/
      store.commit('setUser', user.data),
    ])
    return user
  },
  async signIn({ dispatch }, params) {
    const tokens = await signIn(params)
    if (!tokens.status) {
      return tokens
    }
    await dispatch('updateTokens', tokens.data)
    return tokens
  },
  async signOut({ dispatch }) {
    await dispatch('updateTokens')
  },
}

export const getters = {
  getUser: auth => {
    return auth.user
  },
  authorized: auth => {
    return !!auth.user
  },
  hints: auth => {
    return auth.user ? auth.user.viewed_hints : []
  },
}
