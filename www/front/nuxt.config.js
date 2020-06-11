import bodyParser from 'body-parser'
import session from 'express-session'
import pkg from './package'

require('dotenv').config()

const isDev = process.env.MODE_RUN === 'development'

const server = isDev
  ? {
      port: 3000,
      host: '0.0.0.0',
      timing: false,
    }
  : undefined

export default {
  server,
  loading: {
    color: '#08A8A1',
    height: '5px',
  },
  mode: 'universal',
  env: {
    apiUrl: process.env.API_HOST || 'http://localhost:3000',
    MODE_RUN: process.env.MODE_RUN,
  },
  head: {
    title: pkg.name,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { name: 'msapplication-TileColor', content: '#00aba9' },
      { name: 'theme-color', content: '#ffffff' },
      { name: 'description', content: 'Liga life.' },
      { name: 'image', content: '/favicon/favicon.ico' },
      { itemprop: 'name', content: 'www.liga.life' },
      { itemprop: 'description', content: 'Liga life.' },
      { itemprop: 'image', content: '/favicon/favicon.ico' },
      { name: 'twitter:card', content: 'summary' },
      { name: 'twitter:title', content: 'Liga.Life' },
      { name: 'twitter:description', content: 'Liga life.' },
      { name: 'twitter:image', content: '/favicon/oglogo.png' },
      { name: 'og:title', content: 'Liga.Life' },
      { name: 'og:description', content: 'Liga.Life' },
      { name: 'og:image', content: '/favicon/oglogo.png' },
      { name: 'og:url', content: 'www.liga.life' },
      { name: 'og:site_name', content: 'www.liga.life' },
      { name: 'og:locale', content: 'ru_RU' },
      { name: 'og:type', content: 'website' },
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: 'favicon/favicon.ico' },
      { rel: 'apple-touch-icon', sizes: '76x76', href: 'favicon/apple-touch-icon.png' },
      { rel: 'icon', type: 'image/png', sizes: '32x32', href: 'favicon/favicon-32x32.png' },
      { rel: 'icon', type: 'image/png', sizes: '16x16', href: 'favicon/favicon-16x16.png' },
      { rel: 'manifest', href: 'favicon/site.webmanifest' },
      { rel: 'mask-icon', href: 'favicon/safari-pinned-tab.svg', color: '#5bbad5' },
    ],
  },
  css: [
    '@/assets/style/normalize.styl',
    '@/assets/style/grid/index.styl',
    '@/assets/style/general/index.styl',
    'quill/dist/quill.core.css',
    'quill/dist/quill.snow.css',
    'quill/dist/quill.bubble.css'
  ],
  plugins: [
    '@/plugins/vue-directives',
    '@/plugins/vue-time-ago.js',
    '@/plugins/i18n.js',
    {
      src: '~plugins/nuxt-quill-plugin',
      ssr: false
    },
    {
      src: '~/plugins/vue-infinite-loading.js',
      ssr: false,
    },
    {
      src: '~/plugins/vue-yandex-share.js',
      ssr: false,
    },
    {
      src: '~/plugins/vue-carousel.js',
      ssr: false,
    },
  ],
  i18n: {
    locales: require('./locales/index'),
    defaultLocale: 'ru',
    vueI18n: {
      fallbackLocale: 'ru',
      messages: {
        ru: require('./locales/ru'),
      },
    },
    strategy: 'prefix_except_default',
    seo: true,
    detectBrowserLanguage: {
      useCookie: true,
      cookieKey: 'i18n_redirected',
      alwaysRedirect: true,
      fallbackLocale: 'ru',
    },
  },
  modules: [
    'nuxt-i18n',
    'nuxt-client-init-module',
    'vue-scrollto/nuxt',
    '@nuxtjs/toast',
    "bootstrap-vue/nuxt",
    [
      '@nuxtjs/yandex-metrika',
      {
        id: '57640636',
        webvisor: true,
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
      },
    ],
  ],
  bootstrapVue: {
    icons: true
  },
  toast: {
    register: [
      {
        name: 'success_message',
        message: ({ message }) => {
          return message
        },
        options: {
          type: 'info',
          className: ['success-toast'],
          duration: 3000,
        },
      },
    ],
  },
  build: {
    // extractCSS: true,
    // extend(config, ctx) {
    //   if (ctx.isDev && ctx.isClient) {
    //     config.module.rules.push({
    //       enforce: 'pre',
    //       test: /\.(js|vue)$/,
    //       loader: 'eslint-loader',
    //       exclude: /(node_modules)/,
    //     })
    //   }
    // },
  },
  watchers: {
    webpack: {
      poll: true,
    },
  },
  serverMiddleware: [
    bodyParser.json(),
    session({
      secret: 'super-secret-key',
      resave: false,
      saveUninitialized: false,
      cookie: { maxAge: 1000 * 60 * 60 * 24 * 7 * 4 * 2 },
    }),
    '~/api',
  ],
}
