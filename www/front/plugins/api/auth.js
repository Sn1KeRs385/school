import { LifeCycle } from './request'

export const getUserByToken = async data => {
  return LifeCycle('POST', '/user/me', data)
}

export const signIn = async data => {
  return LifeCycle('POST', '/signin/login', data)
}

export const signOut = async () => {
  return LifeCycle('POST', '/signout')
}
