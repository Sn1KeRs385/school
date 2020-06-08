<template lang="pug">
  .empty-tape
    .empty-tape__message
      MagnifierSVG
    .empty-tape__title {{ $t('empty-tape.title') }}
    .empty-tape__text {{ $t('empty-tape.description') }}
    .empty-tape__go-to-main
      NuxtLink(:to="localePath({ name: 'search' })")
        Button(tag="div" :secondary="true" min-height="40px") {{ $t('empty-tape.go-to-main') }}
</template>

<script>
import Button from '../../Interactives/Controls/Button/Button.vue'
import MagnifierSVG from '../../SVG/MagnifierSVG.vue'

export default {
  components: {
    Button,
    MagnifierSVG,
  },
}
</script>

<style lang="stylus">
@import "empty-tape.styl"
</style>
