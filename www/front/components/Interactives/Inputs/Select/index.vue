<template lang="pug">
  div(
    ref="control"
    tabindex="0"
    :id="id"
    :class="classContainer"
    @mousedown.prevent
    @focus="tabIndex"
    @keydown="keyDown"
  )
    slot(name="label")
    .input-container__label(v-if="label") {{ label }}
    input(ref="input" type="hidden")
    slot(name="input")
      .input-container.select__input-container(
        :class="{'select__input--focus': isOpen, 'input-container--disabled': disabled}"
        @click="changeIsOpen(!isOpen)"
      )
        .select__value-container(v-if="currentOption")
          slot(
            name="value"
            :opt="currentOption"
          ) {{ customName(currentOption) }}
        .select__placeholder(v-if="!currentOption") {{ placeholder }}
        .select__toggler(:class="{'select__toggler--is-open': isOpen}")
          ArrowSVG
    .select__content
      .select__content-padding
      slot(
        name="menu"
        :isSelected="isSelected"
        :select="select"
      )
        .select__menu(v-if="isOpen")
          .select__menu-item(
            v-for="opt in options"
            :class="{ 'select__menu-item--selected': isSelected(opt) }"
            @click="select(opt)"
          )
            slot(
              name="item"
              :opt="opt"
            ) {{ customName(opt) }}
</template>

<script>
import InputMixin from '../../../../assets/js/vue-mixins/input'
import CloseByClickMixin from '../../../../assets/js/vue-mixins/closable-by-click'
import ArrowSVG from '../../../SVG/ArrowSVG.vue'

export default {
  components: {
    ArrowSVG,
  },
  mixins: [InputMixin('getInput'), CloseByClickMixin('isOpen', 'changeIsOpen', 'getControl')],
  props: {
    isOpen: {
      type: Boolean,
      default: false,
    },
    options: {
      type: Array,
      default: () => [],
    },
    byId: {
      type: String,
      default: 'id',
    },
    byName: {
      type: String,
      default: 'name',
    },
    customName: {
      type: Function,
      default(opt) {
        return opt[this.byName]
      },
    },
    classContainer: {
      type: [Array, String, Object],
      default: 'select',
    },
    closeBySelect: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    getInput() {
      return this.$refs.input
    },
    getControl() {
      return this.$refs.control
    },
    currentOption() {
      return this.options.find(k => k[this.byId] === this.value)
    },
  },
  methods: {
    isSelected(opt) {
      return this.value === opt[this.byId]
    },
    changeIsOpen(isOpen) {
      this.$emit('changeIsOpen', isOpen)
    },
    select(opt) {
      this.$emit('changeValue', opt[this.byId])
      if (this.closeBySelect) {
        this.changeIsOpen(false)
      }
    },
    keyDown(e) {
      const keyCode = e.keyCode || e.which
      if (keyCode === 9) {
        this.changeIsOpen(false)
      }
    },
    tabIndex() {
      this.changeIsOpen(true)
    },
  },
}
</script>

<style lang="stylus">
@import "select.styl"
</style>
