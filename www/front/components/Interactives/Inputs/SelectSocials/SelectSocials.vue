<template lang="pug">
  .social-input(v-for="social in socials")
    .social-input-img
      Select(
        id="social-img"
        v-model="social.id"
        :options="getSocials"
        :is-open="social.selectIsOpen"
        @changeIsOpen="changeSocialSelectIsOpen(social, $event)"
      )
        template(slot="value" slot-scope="{ opt }")
          component(:is="opt.img")
        template(slot="item" slot-scope="{ opt }")
          component(:is="opt.img")
    .personal-tab__social-id
      TextInput(id="social-id" :placeholder="social.placeholder")
    button.personal-tab__social-remove
</template>

<script>
import Select from '../Select/index.vue'
import TextInput from '../TextInput/TextInput.vue'

export default {
  components: {
    Select,
    TextInput,
  },
}
</script>

<style lang="stylus">
@import "select-socials.styl"
</style>
