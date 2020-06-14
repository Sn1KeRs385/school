import { LifeCycle } from './request'

export const searchCity = async data => {
  return LifeCycle('POST', 'search/city', data)
}

export const searchUser = async data => {
  return LifeCycle('POST', 'search/user', data)
}
