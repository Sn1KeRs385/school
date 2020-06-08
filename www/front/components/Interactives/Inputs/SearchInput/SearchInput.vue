<template lang="pug">
  .search-input
    label.input-container__label(v-if="label" :for="id") {{ label }}
    label(
      class="search-input__container"
      :class="{'input-container--disabled': disabled}"
    )
      .search-input__icon(
        :style="{ color: iconColor }"
        @mousedown.prevent
      )
        MagnifierSVG
      input(
        :id="id"
        ref="input"
        class="search-input__input"
        :placeholder="placeholder"
        type="text"
        :value="value"
        :disabled="disabled"
        @input="$emit('changeValue', $event.target.value)"
        @focus="changeFocus(true)"
        @blur="changeFocus(false)"
      )
      slot(name="after")
</template>

<script>
import InputMixin from '../../../../assets/js/vue-mixins/input'
import MagnifierSVG from '../../../SVG/MagnifierSVG.vue'

export default {
  components: {
    MagnifierSVG,
  },
  mixins: [InputMixin('getInput')],
  props: {
    iconColor: {
      type: String,
      default: '#AAAAAA',
    },
  },
  computed: {
    getInput() {
      return this.$refs.input
    },
  },
}
</script>

<style lang="stylus">
@import "search-input.styl"
</style>
