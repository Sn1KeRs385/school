import { RequestTimer } from '../helper-classes'

export default (input, prop) => ({
  props: {
    isValid: {
      type: Boolean,
      default: null,
    },
    rules: {
      type: Array,
      default: () => [],
    },
    validations: {
      type: Array,
      default: () => [],
    },
    dependence: {
      default: () => {},
    },
    validateOnBlur: {
      type: Boolean,
      default: false,
    },
    validateOnSubmit: {
      type: Boolean,
      default: true,
    },
    validateOnChange: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      validateTimer: new RequestTimer(100),
    }
  },
  watch: {
    [prop]: {
      handler(value) {
        if (this.validateOnChange) {
          this.validate(value)
        }
      },
    },
    isFocus(isFocus) {
      this.updateCurrentFocusElement(isFocus)
      if (!isFocus && this.validateOnBlur) {
        this.validate()
      }
    },
    dependence: {
      handler() {
        this.validate()
      },
    },
    isValid(isValid) {
      if (!isValid && (!this.$focusElement.id || this.$focusElement.isValid())) {
        this[input].focus()
        this.updateCurrentFocusElement(true)
      }
    },
  },
  mounted() {
    const { form } = this[input]
    if (form) {
      form.addEventListener('submit', this.submit)
    }
  },
  beforeDestroy() {
    const { form } = this[input]
    if (form) {
      form.removeEventListener('submit', this.submit)
    }
  },
  methods: {
    updateCurrentFocusElement(isFocus) {
      this.$setActiveElement(isFocus ? this : null)
    },
    validate(value = this[prop]) {
      if (this.disabled) return []
      const validations = this.rules.map(rule => {
        return rule.check(value)
      })
      this.$emit('update:validations', validations)
      return validations
    },
    submit() {
      if (this.validateOnSubmit) {
        this.validate()
      }
    },
  },
})
