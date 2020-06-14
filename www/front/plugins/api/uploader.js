import { LifeCycle } from './request'

export const uploaderRegister = async data => {
  return LifeCycle('POST', 'uploader/register', data)
}

export const uploaderUpload = async ({ uploadId, chunk, blob }) => {
  return LifeCycle('PATCH', 'uploader/upload', blob, {
    headers: {
      patch: {
        'upload-id': uploadId,
        'portion-from': chunk,
      },
    },
  })
}

export const uploaderFinish = async ({ uploadId }) => {
  return LifeCycle('PATCH', 'uploader/finish', null, {
    headers: {
      patch: {
        'upload-id': uploadId,
      },
    },
  })
}

export const uploaderDelete = async id => {
  return LifeCycle('DELETE', 'uploader/delete', null, { params: { id } })
}
