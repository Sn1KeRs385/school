<template lang="pug">
  .loader-ring(:style="getStyles")
</template>

<script>
export default {
  props: {
    size: {
      type: String,
      default: '100%',
    },
    weight: {
      type: String,
      default: '3px',
    },
    color: {
      type: String,
      default: '#08A8A1',
    },
    backColor: {
      type: String,
      default: 'rgba(8, 168, 161, 0.2)',
    },
  },
  computed: {
    getStyles() {
      return {
        borderTopColor: this.backColor,
        borderRightColor: this.backColor,
        borderBottomColor: this.backColor,
        borderLeftColor: this.color,
        borderWidth: this.weight,
        width: this.size,
        height: this.size,
      }
    },
  },
}
</script>

<style lang="stylus">
@import "../../assets/style/params/colors.styl"

.loader-ring,
.loader-ring:after
  border-radius: 50%

.loader-ring
  font-size: 10px
  position: relative
  text-indent: -9999em
  border-top: 0 solid transparent
  border-right: 0 solid transparent
  border-bottom: 0 solid transparent
  border-left: 0 solid transparent
  -webkit-transform: translateZ(0)
  -ms-transform: translateZ(0)
  transform: translateZ(0)
  -webkit-animation: load8 0.8s infinite linear
  animation: load8 0.8s infinite linear

@-webkit-keyframes load8
  0% {
    -webkit-transform: rotate(0deg)
    transform: rotate(0deg)
  }
  100% {
    -webkit-transform: rotate(360deg)
    transform: rotate(360deg)
  }

@keyframes load8
  0% {
    -webkit-transform: rotate(0deg)
    transform: rotate(0deg)
  }
  100% {
    -webkit-transform: rotate(360deg)
    transform: rotate(360deg)
  }
</style>
