<template lang="pug">
  PageLayout
    .not-found-page
      .not-found-page__404
        NotFoundSVG
      .not-found-page__title {{ $t('not-found.title') }}
      .not-found-page__text {{ $t('not-found.description') }}
      .not-found-page__go-to-main
        NuxtLink(:to="localePath('index')")
          Button(tag="div" :secondary="true" min-height="40px") {{ $t('not-found.go-to-main') }}
</template>

<script>
import PageLayout from '../General/PageLayout/PageLayout.vue'
import NotFoundSVG from '../SVG/404.vue'
import Button from '../Interactives/Controls/Button/Button.vue'

export default {
  components: {
    PageLayout,
    NotFoundSVG,
    Button,
  },
}
</script>

<style lang="stylus">
@import "not-found-page.styl";
</style>
