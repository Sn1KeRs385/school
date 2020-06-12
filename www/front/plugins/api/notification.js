import { LifeCycle } from './request'
const url = 'notifications'
export const readAll = async (created_at) => {
  return LifeCycle('POST', `${url}/readAll?created_at=${created_at}`)
}
