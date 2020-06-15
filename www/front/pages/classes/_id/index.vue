<template lang="pug">
  div 1234123
</template>

<script>

import { index, del } from '../../../plugins/api/api'
import { url } from '../../../plugins/api/class'

export default {
  middleware: 'authenticated',
  data() {
    return {
    }
  },
  async asyncData(params) {
  },
  methods: {
  }
}
</script>

<style src="./index.styl" lang="stylus" scoped></style>
