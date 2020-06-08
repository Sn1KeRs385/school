<template lang="pug">
  .input-comments
    .input-comments__avatar(
      :style="{ 'background-image': `url(${getUser.avatar_url})` }"
    )
    label.input-container__label(v-if="label" :for="id") {{ label }}
    label.input-container.input-comments__input-container(
      ref="container"
      :class="{'input-container--focus': isFocus, 'input-container--disabled': disabled}"
    )
      textarea.input-comments__input(
        :id="id"
        :class="{ 'input-comments__input--focus': isFocus }"
        ref="input"
        :placeholder="placeholder"
        :value="value"
        :disabled="disabled"
        :maxlength="maxLength"
        rows="1"
        cols="1"
        @input="changeValue"
        @focus="changeFocus(true)"
        @blur="changeFocus(false)"
      )
      .input-comments__send(
        v-if="isFocus || value"
        @mousedown.prevent
        @click="send"
        :class="{ 'input-comments__send--active': value }"
      ) {{ $t('comments.send') }}

</template>

<script>
import InputMixin from '../../../assets/js/vue-mixins/input'

export default {
  mixins: [InputMixin('getInput')],
  props: {
    maxLength: {
      type: Number,
      default: 255,
    },
  },
  data() {
    return {
      containerOffsetHeight: 0,
      styleInput: {},
    }
  },
  computed: {
    getInput() {
      return this.$refs.input
    },
    getUser() {
      return this.$store.state.auth.user
    },
  },
  watch: {
    value() {
      this.$nextTick(() => {
        this.autoHeight()
      })
    },
  },
  mounted() {
    this.containerOffsetHeight = this.getInput.offsetHeight - this.getInput.clientHeight
  },
  methods: {
    autoHeight() {
      this.getInput.style.height = 'auto'
      this.getInput.style.height = `${this.getInput.scrollHeight + this.containerOffsetHeight}px`
    },
    send() {
      this.$emit('send', this.value)
      this.$emit('changeValue', '')
    },
    changeValue({ target }) {
      this.$emit('changeValue', target.value)
      this.autoHeight()
    },
  },
}
</script>

<style lang="stylus">
@import "input-comment.styl"
</style>
