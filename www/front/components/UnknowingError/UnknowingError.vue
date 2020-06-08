<template lang="pug">
  PageLayout
    .unknowing-error
      .unknowing-error__message =(
      .unknowing-error__title {{ $t('unknowing-error.title') }}
      .unknowing-error__text {{ $t('unknowing-error.description') }}
      .unknowing-error__go-to-main
        NuxtLink(:to="localePath('index')")
          Button(tag="div" :secondary="true" min-height="40px") {{ $t('unknowing-error.go-to-main') }}
</template>

<script>
import PageLayout from '../General/PageLayout/PageLayout.vue'
import Button from '../Interactives/Controls/Button/Button.vue'

export default {
  components: {
    PageLayout,
    Button,
  },
}
</script>

<style lang="stylus">
@import "unknowing-error.styl";
</style>
