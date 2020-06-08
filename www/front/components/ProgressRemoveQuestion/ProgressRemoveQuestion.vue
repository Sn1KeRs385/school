<template lang="pug">
  .progress-remove-question(v-show="visible" ref="control")
    .progress-remove-question__inner
      .progress-remove-question__title(v-if="title") {{ title }}
      .progress-remove-question__body
        .progress-remove-question__yes(
          @click="resolve"
        ) {{ $t('remove-progress.answers.yes') }}
        .progress-remove-question__no(
          @click="reject"
        ) {{ $t('remove-progress.answers.no') }}
</template>

<script>
import ClosableByClickMixin from '../../assets/js/vue-mixins/closable-by-click'

export default {
  mixins: [ClosableByClickMixin('visible', 'updateVisible', 'getControl')],
  props: {
    title: {
      type: null,
      default: '',
    },
    visible: {
      required: true,
      type: Boolean,
    },
  },
  computed: {
    getControl() {
      return this.$refs.control
    },
  },
  methods: {
    resolve() {
      this.$emit('resolve')
    },
    reject() {
      this.$emit('reject')
    },
    updateVisible(visible) {
      this.$emit('update:visible', visible)
    },
  },
}
</script>

<style lang="stylus">
@import "progress-remove-question.styl";
</style>
