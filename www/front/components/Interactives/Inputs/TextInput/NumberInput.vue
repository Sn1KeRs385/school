<template lang="pug">
  .text-input
    .input-container__input-buffer(v-if="autoWidth" ref="buffer") {{ value }}
    label.input-container__label(v-if="label" :for="id") {{ label }}
    label(
      class="input-container"
      :class="{'input-container--focus': isFocus, 'input-container--disabled': disabled}"
    )
      slot(name="before" :isFocus="isFocus")
      input(
        :id="id"
        ref="input"
        class="input-container__input"
        :placeholder="placeholder"
        type="number"
        :value="value"
        :disabled="disabled"
        @input.number="changeValue"
        @change="editEnd"
        @focus="changeFocus(true)"
        @blur="changeFocus(false)"
      )
      slot(name="after" :isFocus="isFocus")
</template>

<script>
import InputMixin from '../../../../assets/js/vue-mixins/input'

export default {
  mixins: [InputMixin('getInput')],
  props: {
    autoWidth: {
      type: Boolean,
      default: false,
    },
    max: {
      type: Number,
      default: Number.MAX_SAFE_INTEGER,
    },
    min: {
      type: Number,
      default: Number.MIN_SAFE_INTEGER,
    },
  },
  computed: {
    getInput() {
      return this.$refs.input
    },
    getBuffer() {
      return this.$refs.buffer
    },
  },
  watch: {
    value() {
      this.calcAutoWidth()
    },
    isFocus(isFocus) {
      if (!isFocus && this.value !== null && this.value !== '') {
        if (this.value < this.min) {
          this.$emit('changeValue', this.min)
        }
        if (this.value > this.max) {
          this.$emit('changeValue', this.max)
        }
      }
    },
  },
  mounted() {
    if (this.autoWidth) {
      this.getInput.style.minWidth = `${this.getBuffer.clientWidth}px`
    }
  },
  methods: {
    calcAutoWidth() {
      if (this.autoWidth) {
        this.$nextTick(() => {
          this.getInput.style.minWidth = `${this.getBuffer.clientWidth}px`
        })
      }
    },
    checkRange(value) {
      if (value === '') {
        return null
      }
      if (value < this.min) {
        return this.min
      }
      if (value > this.max) {
        return this.max
      }
      return value
    },
    checkLength(value) {
      const offset = value > 0 ? 1 : 2
      if (value.toString().length > this.max.toString().length + offset) {
        return false
      }
      if (value.toString().length < this.min.toString().length - offset) {
        return false
      }
      return true
    },
    editEnd({ target }) {
      target.value = this.checkRange(target.value)
      this.$emit('changeValue', target.value)
      this.calcAutoWidth()
    },
    changeValue({ target }) {
      if (!this.checkLength(target.value)) {
        target.value = this.value
      }
      this.$emit('changeValue', target.value)
      this.calcAutoWidth()
    },
  },
}
</script>

<style lang="stylus">
@import "text-input.styl"
</style>
