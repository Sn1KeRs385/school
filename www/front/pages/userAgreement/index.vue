<template>
  <div class="user-agreement">
    <div class="container">
      <div class="row">
        <div class="col-12 col-xl-8 offset-2">
          <div class="user-agreement__content" v-html="userAgreement.data.text" />
        </div>
      </div>
    </div>
    <div class="user-agreement__close" @click="goBack" />
  </div>
</template>

<script>
import { userAgreements } from '../../plugins/api/agreements'

export default {
  layout: 'docsLayout',
  async asyncData() {
    const userAgreement = await userAgreements({
      type: 'user_agreement',
    })
    return {
      userAgreement,
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
  },
}
</script>

<style src="./user-agreement.styl" lang="stylus" scoped></style>
