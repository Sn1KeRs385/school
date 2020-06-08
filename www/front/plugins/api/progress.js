import { LifeCycle } from './request'

export const getCategories = async () => {
  return LifeCycle('GET', 'progress/categories')
}

export const getSubCategories = async () => {
  return LifeCycle('GET', 'progress/subcategories')
}

export const searchAchievements = async data => {
  return LifeCycle('POST', 'progress/search', data)
}

export const makeAchievement = async data => {
  return LifeCycle('POST', 'progress', data)
}

export const updateAchievement = async data => {
  return LifeCycle('PUT', `progress`, data)
}

export const deleteAchievement = async id => {
  return LifeCycle('DELETE', `progress/${id}`)
}

export const makeGoal = async data => {
  return LifeCycle('POST', 'progress/create-target', data)
}

export const updateGoal = async data => {
  return LifeCycle('PUT', `progress`, data)
}

export const deleteGoal = async id => {
  return LifeCycle('DELETE', `progress/${id}`)
}

export const showSystemAchievement = async id => {
  return LifeCycle('GET', `progress/show-system/${id}`)
}

export const getCommentsSystemAchievement = async params => {
  return LifeCycle('GET', `progress/system-comments`, null, { params })
}

export const makeCommentSystemAchievement = async data => {
  return LifeCycle('POST', `progress/system-comments`, data)
}

export const getCommentsUserAchievement = async params => {
  return LifeCycle('GET', `progress/comments`, null, { params })
}

export const makeCommentUserAchievement = async data => {
  return LifeCycle('POST', `progress/comments`, data)
}

export const getCommentsUserGoal = async params => {
  return LifeCycle('GET', `progress/comments`, null, { params })
}

export const makeCommentUserGoal = async data => {
  return LifeCycle('POST', `progress/comments`, data)
}

export const getDifficulties = async () => {
  return LifeCycle('GET', 'progress/difficulties')
}

export const getConditions = async () => {
  return LifeCycle('GET', 'progress/conditions')
}

export const showUserAchievement = async id => {
  return LifeCycle('GET', `progress/show/${id}`)
}

export const showUserGoal = async id => {
  return LifeCycle('GET', `progress/target/${id}`)
}

export const progressLike = async params => {
  return LifeCycle('GET', `progress/likes`, null, { params })
}

export const getProgressListByUser = async params => {
  return LifeCycle('GET', 'progress/list', null, { params })
}

export const getProgressAchievements = async params => {
  return LifeCycle('GET', 'progress/list/load_subcategory', null, { params })
}

export const getProgressFameByUser = async params => {
  return LifeCycle('GET', 'progress/fame', null, { params })
}

export const systemAchievementAsTarget = async id => {
  return LifeCycle('POST', 'progress/target-system', { progress_id: id })
}

export const achievementAsTarget = async id => {
  return LifeCycle('POST', 'progress/target', { user_progress_id: id })
}

export const achievementIsFake = async id => {
  return LifeCycle('GET', 'progress/fake', null, { params: { user_progress_id: id } })
}

export const progressView = async id => {
  return LifeCycle('POST', 'progress/add-view', { user_progress_id: id })
}

export const removeMediaUserAchievement = async id => {
  return LifeCycle('DELETE', `media/delete/${id}`)
}

export const progressUsersComplete = async (progressId, params) => {
  return LifeCycle('GET', `progress/users/${progressId}`, null, { params })
}

export const getFirstCategoryBySubcategory = async subcategoryId => {
  return LifeCycle('GET', `progress/find_subcategory?subcategory_id=${subcategoryId}`)
}
