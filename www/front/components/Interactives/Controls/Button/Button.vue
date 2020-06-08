<template lang="pug">
  component(
    :is="tag"
    class="button"
    :style="{minHeight: minHeight}"
    :class="{'button--secondary': secondary, 'button--disabled': disabled || loading}"
    :disabled="disabled"
    :type="getType"
  )
    slot(name="before")
    .button__text(v-if="$slots['default']")
      slot

    .button__multiline-text(v-if="$slots['multiline-text']")
      slot(name="multiline-text")
    slot(name="after")
    .button__loader-container(v-if="loading")
      LoaderRing(:color="colorRing" :back-color="backColorRing")
</template>

<script>
import LoaderRing from '../../../General/LoaderRing.vue'

export default {
  components: {
    LoaderRing,
  },
  props: {
    tag: {
      type: [Object, String],
      default: 'button',
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    submit: {
      type: Boolean,
      default: false,
    },
    secondary: {
      type: Boolean,
      default: false,
    },
    minHeight: {
      type: String,
      default: '50px',
    },
  },
  computed: {
    getType() {
      return this.submit ? 'submit' : 'button'
    },
    colorRing() {
      return this.secondary ? '#08A8A1' : '#FFFFFF'
    },
    backColorRing() {
      return this.secondary ? '#CEEEEC' : 'rgba(255,255,255,0.2)'
    },
  },
}
</script>

<style lang="stylus">
@import "button.styl"
</style>
