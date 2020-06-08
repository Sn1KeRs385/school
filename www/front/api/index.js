import express from 'express'

const router = express.Router()

const app = express()
router.use((req, res, next) => {
  Object.setPrototypeOf(req, app.request)
  Object.setPrototypeOf(res, app.response)
  req.res = res
  res.req = req
  next()
})

router.post('/login', (req, res) => {
  req.session.access_token = req.body.access_token
  req.session.refresh_token = req.body.refresh_token
  return res.json(true)
})

router.post('/logout', (req, res) => {
  delete req.session.access_token
  delete req.session.refresh_token
  return res.json(true)
})

export default {
  path: '/api',
  handler: router,
}
