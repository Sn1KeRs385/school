import { LifeCycle } from './request'

// eslint-disable-next-line import/prefer-default-export
export const getRating = async params => {
  return LifeCycle('GET', 'rating', null, { params })
}

export const getRatingSettings = async () => {
  return LifeCycle('GET', 'rating/settings')
}

export const setRatingSettings = async data => {
  return LifeCycle('POST', 'rating/settings', data)
}
