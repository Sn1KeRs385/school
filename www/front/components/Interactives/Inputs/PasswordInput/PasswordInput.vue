<template lang="pug">
  .password-input
    label.input-container__label(v-if="label" :for="id") {{ label }}
    label(
      class="input-container"
      :class="{'input-container--focus': isFocus, 'input-container--disabled': disabled}"
    )
      slot(name="before")
      input(
        :id="id"
        ref="input"
        class="input-container__input"
        :placeholder="placeholder"
        :type="currentType"
        :value="value"
        :disabled="disabled"
        @input="$emit('changeValue', $event.target.value)"
        @focus="changeFocus(true)"
        @blur="changeFocus(false)"
      )
      .password-input__eye(
        @click="changeVisible(!visible)"
        @mousedown.prevent
      )
        OpenEyeSVG(v-if="visible")
        CloseEyeSVG(v-else)

</template>

<script>
import InputMixin from '../../../../assets/js/vue-mixins/input'
import CloseEyeSVG from '../../../SVG/CloseEyeSVG.vue'
import OpenEyeSVG from '../../../SVG/OpenEyeSVG.vue'

export default {
  components: {
    CloseEyeSVG,
    OpenEyeSVG,
  },
  mixins: [InputMixin('getInput')],
  data() {
    return {
      visible: false,
    }
  },
  computed: {
    getInput() {
      return this.$refs.input
    },
    currentType() {
      return this.visible ? 'text' : 'password'
    },
  },
  methods: {
    changeVisible(visible) {
      this.visible = visible
    },
  },
}
</script>

<style lang="stylus">
@import "password-input.styl"
</style>
