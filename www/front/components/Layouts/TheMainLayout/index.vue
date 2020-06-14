<template lang="pug">
    PageLayout
      .the-main-layout
        TheHeader(
          v-if="isAuthorized"
        )
        .the-main-layout__content
          slot
          Transition(name="modal.transition")
            component(v-if="modal" :is="modal.component" v-bind="modal.props" v-on="modal.events")
</template>

<script>
import TheHeader from '../../TheHeader/index.vue'
import PageLayout from '../../General/PageLayout/PageLayout.vue'

export default {
  provide() {
    return {
      setModal: this.setModal,
    }
  },
  components: {
    TheHeader,
    PageLayout
  },
  data() {
    return {
      modal: null,
      bodyClass: null,
      oldScrollY: 0,
    }
  },
  computed: {
    isAuthorized() {
      return this.$store.getters['auth/authorized']
    },
  },
  methods: {
    setModal(modal) {
      const layout = document.body
      if (modal) {
        this.oldScrollY = window.pageYOffset
        const bodyClass = modal.bodyClass || 'adjacent-modal-open'
        this.bodyClass = bodyClass
        layout.classList.add(bodyClass)
      } else {
        layout.classList.remove(this.bodyClass)
        this.bodyClass = null
        this.$nextTick(() => {
          window.scrollTo({ top: this.oldScrollY })
          this.oldScrollY = 0
        })
      }
      this.modal = modal
    },
  },
}
</script>

<style lang="stylus">
@import "the-main-layout.styl"
</style>
