<template lang="pug">
  div
    .text-input
      .input-container__input-buffer(v-if="autoWidth" ref="buffer") {{ value }}
      label.input-container__label(v-if="label" :for="id") {{ label }}
      label(
        class="input-container"
        :class="containerClasses"
      )
        slot(name="before" :isFocus="isFocus")
        input(
          :id="id"
          ref="input"
          class="input-container__input"
          :placeholder="placeholder"
          :type="type"
          :value="value"
          :disabled="disabled"
          @input="changeValue"
          @focus="changeFocus(true)"
          @blur="changeFocus(false)"
          :readonly="!editable"
          :autocomplete="autocomplete"
        )
        slot(name="after" :isFocus="isFocus")
    ValidationList(
      :validations="validations"
    )

</template>

<script>
import InputMixin from '../../../../assets/js/vue-mixins/input'
import ValidationList from '../ValidationList/ValidationList.vue'

export default {
  components: {
    ValidationList,
  },
  mixins: [InputMixin('getInput')],
  props: {
    type: {
      type: String,
      default: 'text',
    },
    autocomplete: {
      type: String,
      default: 'off',
    },
    autoWidth: {
      type: Boolean,
      default: false,
    },
    editable: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    getInput() {
      return this.$refs.input
    },
    getBuffer() {
      return this.$refs.buffer
    },
    containerClasses() {
      return {
        'input-container--focus': this.isFocus,
        'input-container--invalid': this.isValid === false,
        'input-container--disabled': this.disabled,
      }
    },
  },
  methods: {
    copy() {
      this.getInput.select()
      document.execCommand('copy')
    },
    changeValue({ target }) {
      if (!this.editable) {
        target.value = this.value
        return
      }
      this.$emit('changeValue', target.value)
      if (this.autoWidth) {
        this.$nextTick(() => {
          this.getInput.style.width = `${this.getBuffer.clientWidth}px`
        })
      }
    },
  },
}
</script>

<style lang="stylus">
@import "text-input.styl"
</style>
