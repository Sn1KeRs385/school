import ValidateInputMixin from './validate-input'

export default (input, prop = 'value', event = 'changeValue', required = false) => ({
  mixins: [ValidateInputMixin(input, prop)],
  model: { prop, event },
  props: {
    id: {
      required: true,
      type: [String, Number],
    },
    label: {
      type: String,
    },
    placeholder: {
      type: String,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    [prop]: {
      required,
    },
  },
  data() {
    return {
      isFocus: false,
    }
  },
  watch: {
    isFocus(isFocus) {
      if (isFocus) {
        this[input].focus()
      } else {
        this[input].blur()
      }
    },
  },
  mounted() {
    this[input].addEventListener('focus', this.$_focusHandler)
    this[input].addEventListener('blur', this.$_blurHandler)
  },
  beforeDestroy() {
    this[input].removeEventListener('focus', this.$_focusHandler)
    this[input].removeEventListener('blur', this.$_blurHandler)
  },
  methods: {
    changeFocus(isFocus) {
      this.isFocus = isFocus
    },
    $_focusHandler() {
      this.isFocus = true
    },
    $_blurHandler() {
      this.isFocus = false
    },
  },
})
