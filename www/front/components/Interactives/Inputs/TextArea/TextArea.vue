<template lang="pug">
  .text-area
    label.input-container__label(v-if="label" :for="id") {{ label }}
    label.input-container.text-area__input-container(
      :class="getContainerClasses"
    )
      textarea.input-container__input.text-area__input(
        :id="id"
        ref="input"
        :placeholder="placeholder"
        :value="value"
        :disabled="disabled"
        rows="0"
        cols="0"
        @input="$emit('changeValue', $event.target.value)"
        @focus="changeFocus(true)"
        @blur="changeFocus(false)"
      )
    ValidationList(:validations="validations")
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
    height: {
      type: [String, Number],
      default: '120px',
    },
  },
  computed: {
    getInput() {
      return this.$refs.input
    },
    getContainerClasses() {
      return {
        'input-container--focus': this.isFocus,
        'input-container--disabled': this.disabled,
        'input-container--invalid': this.isValid === false,
      }
    },
  },
}
</script>

<style lang="stylus">
@import "text-area.styl"
</style>
