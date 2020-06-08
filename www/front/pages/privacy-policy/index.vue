<template>
  <div class="user-agreement">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
          <div class="user-agreement__content" v-html="privacyPolicy.data.text" />
        </div>
      </div>
    </div>
    <div class="user-agreement__close" @click="goBack" />
  </div>
</template>

<script>
import { PrivacyPolicy } from '../../plugins/api/agreements'

export default {
  layout: 'docsLayout',
  async asyncData() {
    const privacyPolicy = await PrivacyPolicy({
      type: 'privacy_policy',
    })
    return {
      privacyPolicy,
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
  },
}
</script>

<style src="./privacy-policy.styl" lang="stylus" scoped></style>
