import { LifeCycle } from './request'

export const url = 'classes'

export const getMembers = async (classId, type = null) => {
  return LifeCycle('GET', `${url}/getMembers?class_id=${classId}${type ? '&type=' + type : ''}`)
}

export const setMembers = async (classId, type = null, params = null) => {
  let data = {
    class_id: classId,
    type: type,
    users: params,
  }
  return LifeCycle('POST', `${url}/setMembers`, data )
}

export const getStudentsWithProgress = async (lessonId) => {
  return LifeCycle('GET', `${url}/getStudentsWithProgress?lesson_id=${lessonId}`)
}

export const saveStudentsProgress = async (data) => {
  return LifeCycle('POST', `${url}/saveStudentsProgress`, data)
}
