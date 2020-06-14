import { LifeCycle } from './request'
export const url = 'messages'
export const unreadCounter = async () => {
  return LifeCycle('GET', `${url}/unreadCounter`)
}
export const getChats = async () => {
  return LifeCycle('GET', `${url}/getChats`)
}
export const getMessages = async (userId) => {
  return LifeCycle('GET', `${url}/getMessages?user_id=${userId}`)
}
export const sendMessage = async (userId, message) => {
  return LifeCycle('GET', `${url}/sendMessage?user_id=${userId}`, null, {params: { message }})
}
