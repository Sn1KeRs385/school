export default (prop, setter, control) => ({
  methods: {
    windowClickHandler({ target }) {
      if (this[prop] && target !== this[control] && !this[control].contains(target)) {
        this[setter](false)
      }
    },
  },
  watch: {
    [prop](val) {
      setTimeout(() => {
        if (val) {
          window.addEventListener('click', this.windowClickHandler, { capture: true })
        } else {
          window.removeEventListener('click', this.windowClickHandler)
        }
      })
    },
  },
})
