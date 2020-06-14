import { LifeCycle } from './request'

export const getRewards = async params => {
  return LifeCycle('GET', `progress/reward/index`, null, { params })
}

export const addReward = async data => {
  return LifeCycle('POST', `progress/reward/add`, data)
}

export const deleteReward = async data => {
  return LifeCycle('DELETE', `progress/reward/delete`, data)
}

export const getRewardCard = async (rewardId, userId) => {
  return LifeCycle('GET', `progress/reward/show?reward_id=${rewardId}&user_id=${userId}`)
}

export const getRewardAchievements = async (rewardId, userId, params) => {
  return LifeCycle(
    'GET',
    `progress/reward/progress?reward_id=${rewardId}&user_id=${userId}`,
    null,
    { params },
  )
}
