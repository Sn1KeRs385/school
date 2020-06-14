<template lang="pug">
  GroupInputs
    .group-inputs__input
      NumberInput(
        :id="`${id}-min`"
        :auto-width="true"
        :min="min"
        :max="max"
        :value="minValue"
        :disabled="disabled"
        :placeholder="placeholders[0]"
        :label="label"
        @changeValue="changeMinValue"
      )
      template(slot="before" slot-scope="{ isFocus }")
          .range-input__before(
            v-if="before[0]"
            v-html="before[0]"
            :class="{'range-input__before--focus': isFocus || !minValue}"
          )
          template(slot="after" slot-scope="{ isFocus }")
          .range-input__after(
            v-if="maxValue && after[0]"
            v-html="after[0]"
            :class="{'range-input__after--focus': isFocus || !minValue }"
         )
    .group-inputs__input
      NumberInput(
        :id="`${id}-max`"
        :auto-width="true"
        :min="min"
        :max="max"
        :value="maxValue"
        :disabled="disabled"
        :placeholder="placeholders[1]"
        @changeValue="changeMaxValue"
      )
          template(slot="before" slot-scope="{ isFocus }")
            .range-input__before(
              v-if="before[1]"
              v-html="before[1]"
              :class="{'range-input__before--focus': isFocus || !maxValue}"
            )
          template(slot="after" slot-scope="{ isFocus }")
            .range-input__after(
              v-if="maxValue && after[1]"
              v-html="after[1]"
              :class="{'range-input__after--focus': isFocus}"
            )
</template>

<script>
import GroupInputs from '../../../General/GroupInputs/GroupInputs.vue'
import NumberInput from '../TextInput/NumberInput.vue'

export default {
  components: {
    GroupInputs,
    NumberInput,
  },
  props: {
    id: {
      required: true,
      type: String,
    },
    label: {
      type: String,
      default: '',
    },
    minValue: {
      type: [String, Number],
      default: null,
    },
    maxValue: {
      type: [String, Number],
      default: null,
    },
    min: {
      type: Number,
      default: null,
    },
    max: {
      type: Number,
      default: null,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    placeholders: {
      type: Array,
      default: () => [null, null],
    },
    before: {
      type: Array,
      default: () => [null, null],
    },
    after: {
      type: Array,
      default: () => [null, null],
    },
  },
  methods: {
    changeMinValue(minValue) {
      this.$emit('update:min-value', minValue)
    },
    changeMaxValue(maxValue) {
      this.$emit('update:max-value', maxValue)
    },
  },
}
</script>

<style lang="stylus">
@import "range-input.styl"
</style>
