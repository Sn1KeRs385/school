import { LifeCycle } from './request'

export const userAgreements = async params => {
  return LifeCycle('GET', '/agreement', null, { params })
}

export const PrivacyPolicy = async params => {
  return LifeCycle('GET', '/agreement', null, { params })
}
