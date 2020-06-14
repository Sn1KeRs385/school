<template lang="pug">
  label.check-box(:class="getClassContainer")
    input(
      :id="id"
      ref="input"
      type="checkbox"
      :value="value"
      :disabled="disabled"
      @change="changeValue"
      @focus="changeFocus(true)"
      @blur="changeFocus(false)"
    )
    .check-box__box(:class="getClassBox")
      CheckBoldSVG
    .check-box__label(v-if="label") {{ label }}
</template>

<script>
import InputMixin from '../../../../assets/js/vue-mixins/input'
import CheckBoldSVG from '../../../SVG/CheckBoldSVG.vue'

export default {
  components: {
    CheckBoldSVG,
  },
  mixins: [InputMixin('getInput')],
  computed: {
    getInput() {
      return this.$refs.input
    },
    getClassContainer() {
      return {
        'check-box--disabled': this.disabled,
      }
    },
    getClassBox() {
      return {
        'check-box__box--checked': this.value,
        'check-box__box--focus': this.isFocus,
        'check-box__box--disabled': this.disabled,
      }
    },
  },
  methods: {
    changeValue(e) {
      this.$emit('changeValue', e.target.checked)
    },
  },
}
</script>

<style lang="stylus">
@import "check-box.styl"
</style>
