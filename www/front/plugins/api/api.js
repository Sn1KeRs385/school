import { LifeCycle } from './request'

export const index = async (params, url) => {
  return LifeCycle('GET', url, { params })
}
export const all = async (url, query = null) => {
  return LifeCycle('GET', `${url}?all&${query || ''}`)
}
export const save = async (params, url) => {
  return LifeCycle('POST', `${url}?add`, params)
}
export const del = async (id, url) => {
  return LifeCycle('DELETE', `${url}/${id}`)
}
