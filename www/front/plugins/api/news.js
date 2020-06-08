import { LifeCycle } from './request'
const url = 'news'
export const getNews = async params => {
  return LifeCycle('GET', url, { params })
}
export const saveNews = async params => {
  return LifeCycle('POST', `${url}?add`, params)
}
export const deleteNews = async id => {
  return LifeCycle('DELETE', `${url}/${id}`)
}
