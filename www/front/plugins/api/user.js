import { LifeCycle } from './request'

export const url = 'users'

export const getSchedule = async (start_at, end_at, user_id) => {
  return LifeCycle('GET', `${url}/getSchedule?start_at=${start_at}&end_at=${end_at}&user_id=${user_id}`)
}
