import axios from 'axios'

// let store = null
const ERROR_MESSAGES = {
  AUTHORIZATION_EXCEPTION: 'AUTHORIZATION_EXCEPTION',
}

const errorsHandlers = {
  [ERROR_MESSAGES.AUTHORIZATION_EXCEPTION]() {},
}

function defaultErrorHandler(errorName) {
  const handlerError = errorsHandlers[errorName]
  if (handlerError) handlerError()
}

// export function setStore(localStore) {
//   store = localStore
// }

export const execute = async (params, errorHandler = defaultErrorHandler) => {
  try {
    const { data } = await axios(params)
    if (!data.status) {
      data.errors.forEach(element => {
        errorHandler(element.message)
      })
    }
    return data
  } catch {
    return console.error(params)
  }
}

export const LifeCycle = (method, url, data, other = {}) => {
  other.baseURL = other.baseURL || process.env.apiUrl
  return execute({ method, url, data, ...other })
}

export { ERROR_MESSAGES }
