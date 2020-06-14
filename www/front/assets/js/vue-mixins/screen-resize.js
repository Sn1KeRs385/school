export default {
  computed: {
    screenWidth() {
      return this.$store.state.screenWidth
    },
    isXL() {
      return this.screenWidth >= 1200
    },
    isLG() {
      return this.screenWidth >= 992
    },
    isMD() {
      return this.screenWidth >= 768
    },
    isSM() {
      return this.screenWidth >= 576
    },
    isXS() {
      return this.screenWidth <= 575
    },
  },
}
