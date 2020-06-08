<template lang="pug">
  .sign-up
</template>

<script>
export default {
  head() {
    return {
      title: 'Регистрация на Liga.Life',
      meta: [
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
    }
  },
  transition: {
    name: 'transition-tab-right',
    mode: '',
  },
  middleware: ({ store, redirect }) => {
    if (store.getters['auth/authorized']) {
      return redirect('/')
    }
    return redirect()
  },
  layout: 'sign-layout',
  scrollToTop: false,
  data() {
    return {}
  },
}
</script>

<style lang="stylus">
@import "sign-up.styl"
</style>
