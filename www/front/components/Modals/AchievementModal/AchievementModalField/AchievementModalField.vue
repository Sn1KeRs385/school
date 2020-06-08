<template lang="pug">
  .achievement-modal-field
    .achievement-modal-field__label-container
      label.achievement-modal-field__label(:for="forInput") {{ label }}
      ToolTip(
        :content="toolContent"
        :show-mark="showToolTip"
        :class="toolClass"
        :show-refuse-button="false"
      )
    .achievement-modal-field__input-container
      .achievement-modal-field__input
        slot
</template>

<script>
import ToolTip from '../../../General/ToolTip/ToolTip.vue'

export default {
  components: {
    ToolTip,
  },
  props: {
    forInput: {
      type: [String, Number],
      default: '',
    },
    label: {
      type: String,
      default: '',
    },
    showToolTip: {
      type: Boolean,
      default: true,
    },
    toolContent: {
      type: String,
      default: null,
    },
    toolClass: {
      type: String,
      default: null,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      markTextActive: false,
    }
  },
  computed: {
    markTextClasses() {
      return { 'achievement-modal-field__mark-container-active': this.markTextActive }
    },
  },
  methods: {
    toggleMarkText() {
      this.markTextActive = !this.markTextActive
    },
  },
}
</script>

<style lang="stylus">
@import "achievement-modal-field.styl"
</style>
