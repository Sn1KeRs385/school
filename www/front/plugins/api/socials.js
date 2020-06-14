import { LifeCycle } from './request'

export default async () => {
  return LifeCycle('GET', '/social-networks')
}
