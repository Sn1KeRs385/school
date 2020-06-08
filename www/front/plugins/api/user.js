import { LifeCycle } from './request'

export const getPrivacyOptions = async () => {
  return LifeCycle('GET', 'user/privacy')
}

export const setPrivacy = async data => {
  return LifeCycle('PUT', 'user/privacy', data)
}

export const getUserProfile = async id => {
  return LifeCycle('GET', `user/id${id}`)
}

export const getUserEditInfo = async () => {
  return LifeCycle('GET', 'user/edit')
}

export const setUserEditInfo = async data => {
  return LifeCycle('POST', 'user/edit', data)
}

export const setUserAvatar = async data => {
  return LifeCycle('POST', 'user/edit', data, {
    headers: {
      post: {
        'Content-Type': 'multipart/form-data',
      },
    },
  })
}

export const getUserCategories = async userId => {
  return LifeCycle('GET', `user/id${userId}/categories`)
}

export const getUserRewards = async (userId, params) => {
  return LifeCycle('GET', `user/id${userId}/reward/index`, null, { params })
}

export const removeUserAvatar = async id => {
  return LifeCycle('DELETE', 'user/remove-avatar', { avatar_id: id })
}

export const subscribe = async id => {
  return LifeCycle('GET', `user/id${id}/subscribe`)
}

export const userPosts = async data => {
  return LifeCycle('PUT', `user/${data}`, data)
}

export const getUserFollowers = async (id, data) => {
  return LifeCycle('POST', `user/id${id}/followers`, data)
}

export const getUserSubscribers = async (id, data) => {
  return LifeCycle('POST', `user/id${id}/subscribers`, data)
}

export const getUserAchievements = async (id, params) => {
  return LifeCycle('GET', `user/id${id}/progress`, null, { params })
}

export const getUserGoals = async (id, params) => {
  return LifeCycle('GET', `user/id${id}/targets`, null, { params })
}

export const getTape = async data => {
  return LifeCycle('POST', 'tape', data)
}

export const searchSubcategories = async params => {
  return LifeCycle('GET', 'user/search-subcategories', null, { params })
}

export const getPriorityCategories = async () => {
  return LifeCycle('GET', 'user/priority-categories')
}

export const setPriorityCategories = async data => {
  return LifeCycle('PUT', 'user/priority-categories', data)
}

export const userStatsByCategory = async (userId, params) => {
  return LifeCycle('GET', `user/id${userId}/statistic`, null, { params })
}

export const userStatsLoadSubcategory = async (userId, params) => {
  return LifeCycle('GET', `user/id${userId}/statistic/load-subcategory`, null, { params })
}

export const userLeaderBoard = async () => {
  return LifeCycle('GET', 'user/leader-board')
}

export const userSendHint = async data => {
  return LifeCycle('POST', 'user/hint', data)
}
