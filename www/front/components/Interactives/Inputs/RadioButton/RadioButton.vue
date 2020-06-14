<template lang="pug">
  label.radio-button(:class="getClassContainer")
    input(
      :id="id"
      :name="name"
      ref="input"
      type="radio"
      :value="option"
      :disabled="disabled"
      :checked="value === option"
      @change="changeValue"
      @focus="changeFocus(true)"
      @blur="changeFocus(false)"
    )
    .radio-button__box(:class="getClassBox")
    .radio-button__label(v-if="label") {{ label }}
</template>

<script>
import InputMixin from '../../../../assets/js/vue-mixins/input'

export default {
  mixins: [InputMixin('getInput')],
  props: {
    name: {
      required: true,
      type: String,
    },
    option: {
      type: null,
      default: null,
    },
  },
  computed: {
    getInput() {
      return this.$refs.input
    },
    getClassContainer() {
      return {
        'radio-button--disabled': this.disabled,
      }
    },
    getClassBox() {
      return {
        'radio-button__box--checked': this.value === this.option,
        'radio-button__box--focus': this.isFocus,
        'radio-button__box--disabled': this.disabled,
      }
    },
  },
  methods: {
    changeValue() {
      this.$emit('changeValue', this.option)
    },
  },
}
</script>

<style lang="stylus">
@import "radio-button.styl"
</style>
