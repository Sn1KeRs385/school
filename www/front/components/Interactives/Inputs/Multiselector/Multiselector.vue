<template lang="pug">
  .multiselector__container
    .multiselector__label-container(v-if="label")
      label.multiselector__label(:for="id") {{ label }}
    .multiselector(ref="control")
      .multiselector__body(:class="getBodyClass")
        .multiselector__wrapper
          label.multiselector__input-container(
            :for="id"
            :class="getInputContainerClass"
          )
            input.multiselector__input(
              tabindex="0"
              :id="id"
              ref="input"
              :type="searchable ? 'text' : ''"
              :placeholder="placeholder"
              :disabled="disabled"
              :value="search"
              :autocomplete="autocomplete"
              @input="$emit('update:search', $event.target.value)"
            )
            .multiselector__input-loader(v-if="loading")
              LoaderRing(weight="2px")
            .multiselector__toggle(
              v-if="withToggle"
              :class="getToggleClass"
              @click.prevent="changeIsOpen(!isOpen)"
            )
              ArrowSVG
          label.multiselector__value-container(
            v-if="!searchable"
            :for="id"
            :class="getValueContainerClass"
          )
            .multiselector__value(v-if="!multiply && !valueIsEmpty")
              slot(name="value" :opt="value") {{ customName(value) }}
            .multiselector__placeholder(v-else) {{ placeholder }}
            .multiselector__value-loader(v-if="loading")
              LoaderRing(weight="2px")
            .multiselector__toggle(
              v-if="withToggle"
              :class="getToggleClass"
              @click.prevent="changeIsOpen(!isOpen)"
            )
              ArrowSVG
          .multiselector__menu-container(
            v-show="menuIsVisible"
            :class="getMenuContainerClasses"
          )
            .multiselector__menu(@mousedown.prevent)
              .multiselector__menu-item(
                v-for="opt in options"
                :key="opt[byId]"
                :class="getClassItem(opt)"
                @click="changeSelect(opt)"
              )
                slot(name="item" :opt="opt") {{ opt[byName] }}
                .multiselector__menu-item-check(v-if="isSelected(opt)")
                  CheckSVG

    .multiselector__tags(v-if="multiply && value.length")
      Tag(
        v-for="opt in value"
        :key="opt[byId]"
        :removable="true"
        @remove="unselect(opt)"
      ) {{ customName(opt) }}
    ValidationList(:validations="validations")

</template>

<script>
import InputMixin from '../../../../assets/js/vue-mixins/input'
import ClosableByClickMixin from '../../../../assets/js/vue-mixins/closable-by-click'
import Tag from '../../../General/Tag/Tag.vue'
import CheckSVG from '../../../SVG/CheckSVG.vue'
import LoaderRing from '../../../General/LoaderRing.vue'
import ArrowSVG from '../../../SVG/ArrowSVG.vue'
import ValidationList from '../ValidationList/ValidationList.vue'

export default {
  components: {
    Tag,
    CheckSVG,
    LoaderRing,
    ArrowSVG,
    ValidationList,
  },
  mixins: [InputMixin('getInput'), ClosableByClickMixin('isOpen', 'changeIsOpen', 'getControl')],
  props: {
    byId: {
      type: String,
      default: 'id',
    },
    byName: {
      type: String,
      default: 'name',
    },
    isOpen: {
      type: Boolean,
      default: false,
    },
    search: {
      type: String,
      default: '',
    },
    searchable: {
      type: Boolean,
      default: false,
    },
    options: {
      type: Array,
      default: () => [],
    },
    multiply: {
      type: Boolean,
      default: false,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    customName: {
      type: Function,
      default(opt) {
        if (!opt) return ''
        return opt[this.byName]
      },
    },
    closeBySelect: {
      type: Boolean,
      default: true,
    },
    withToggle: {
      type: Boolean,
      default: true,
    },
    min: {
      type: Number,
      default: 0,
    },
    searchValidate: {
      type: Boolean,
      default: false,
    },
    autocomplete: {
      type: String,
      default: 'off',
    },
  },
  computed: {
    valueIsEmpty() {
      if (this.multiply) {
        return !this.value.length
      }
      return !this.value
    },
    menuIsVisible() {
      return !!(this.options.length && this.isOpen && !this.loading)
    },
    getInput() {
      return this.$refs.input
    },
    getControl() {
      return this.$refs.control
    },
    getBodyClass() {
      return {
        'multiselector__body--invalid': this.isValid === false,
        'multiselector__body--focus': this.isOpen && !this.disabled,
        'multiselector__body--disabled': this.disabled,
      }
    },
    getInputContainerClass() {
      return {
        'multiselector__input-container--invalid': this.isValid === false,
        'multiselector__input-container--disabled': this.disabled,
        'multiselector__input-container--focus':
          this.isOpen && this.options.length && !this.loading && !this.disabled,
        hidden: !this.searchable,
      }
    },
    getValueContainerClass() {
      return {
        'multiselector__value-container--invalid': this.isValid === false,
        'multiselector__value-container--disabled': this.disabled,
        'multiselector__value-container--focus': this.isOpen && !this.disabled,
      }
    },
    getMenuContainerClasses() {
      return {
        'multiselector__menu-container--invalid': this.isValid === false,
      }
    },
    optionsIsEmpty() {
      return !this.options.length
    },
    getToggleClass() {
      return {
        'multiselector__toggle--is-open': this.isOpen && !this.optionsIsEmpty,
      }
    },
  },
  watch: {
    search() {
      if (this.searchValidate) {
        this.validate()
      }
    },
    isFocus(isFocus) {
      this.changeIsOpen(isFocus)
    },
    isOpen(isOpen) {
      this.isFocus = isOpen
    },
  },
  methods: {
    getClassItem(opt) {
      return {
        'multiselector__menu-item--selected': this.isSelected(opt),
      }
    },
    changeIsOpen(isOpen) {
      this.$emit('update:isOpen', isOpen)
    },
    isSelected(opt) {
      if (this.multiply) {
        const index = this.value.findIndex(k => k[this.byId] === opt[this.byId])
        return index !== -1
      }
      return this.value && this.value[this.byId] === opt[this.byId]
    },
    select(opt) {
      if (this.multiply) {
        this.value.push(opt)
      } else {
        this.$emit('update:value', opt)
      }
      if (this.closeBySelect) {
        this.changeIsOpen(false)
      }
    },
    unselect(opt) {
      if (this.multiply) {
        if (this.value.length <= this.min) return
        const index = this.value.findIndex(k => k[this.byId] === opt[this.byId])
        this.value.splice(index, 1)
      } else {
        if (this.min) {
          this.changeIsOpen(false)
          return
        }
        this.$emit('update:value', null)
      }
    },
    changeSelect(opt) {
      if (this.multiply && this.isSelected(opt)) {
        this.$emit('unselect')
        this.unselect(opt)
      } else {
        this.select(opt)
      }
    },
  },
}
</script>

<style lang="stylus">
@import "multiselector.styl"
</style>
