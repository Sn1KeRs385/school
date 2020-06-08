<template lang="pug">
  .sign-in
    img.sign-in__logo(src="../../assets/image/logo.png")
    form.sign-in__form(@submit.prevent="submit")
      .sign-in__login-input
        TextInput(
          id="login"
          v-model="login.value"
          :is-valid="loginIsValid"
          :rules="login.rules"
          :validations.sync="login.validations"
          :placeholder="$t('sign.in.inputs.login.placeholder')"
          :disabled="loading"
        )
      .sign-in__password-input
        TextInput(
          id="password"
          v-model="password.value"
          :is-valid="passwordIsValid"
          :rules="password.rules"
          :validations.sync="password.validations"
          type="password"
          :placeholder="$t('sign.in.inputs.password.placeholder')"
          :disabled="loading"
        )
      .sign-in__validaitons-list
        ValidationList(
          :validations="form.validations"
        )
      .sign-in__button-login
        Button(
          :submit="true"
          :loading="loading"
          :disabled="!formIsValid"
        ) {{$t('sign.in.buttons.sign-in')}}

</template>

<script>
import TextInput from '../../components/Interactives/Inputs/TextInput/TextInput.vue'
import Button from '../../components/Interactives/Controls/Button/Button.vue'
import { RequiredRule, EmailRule } from '../../assets/js/validation-rules'
import Errors from '../../plugins/api/errors.json'
import ValidationList from '../../components/Interactives/Inputs/ValidationList/ValidationList.vue'

export default {
  head() {
    return {
      title: 'Вход на Liga.Life',
      meta: [
        { name: 'description', content: 'Liga life.' },
        { name: 'image', content: '/favicon/favicon.ico' },
        { itemprop: 'name', content: 'www.liga.life' },
        { itemprop: 'description', content: 'Liga life.' },
        { itemprop: 'image', content: '/favicon/favicon.ico' },
        { name: 'twitter:card', content: 'summary' },
        { name: 'twitter:title', content: 'Liga.Life' },
        { name: 'twitter:description', content: 'Liga life.' },
        { name: 'twitter:image', content: '/favicon/oglogo.png' },
        { name: 'og:title', content: 'Liga.Life' },
        { name: 'og:description', content: 'Liga.Life' },
        { name: 'og:image', content: '/favicon/oglogo.png' },
        { name: 'og:url', content: 'www.liga.life' },
        { name: 'og:site_name', content: 'www.liga.life' },
        { name: 'og:locale', content: 'ru_RU' },
        { name: 'og:type', content: 'website' },
      ],
    }
  },
  transition: {
    name: 'transition-tab-left',
    mode: '',
  },
  components: {
    TextInput,
    Button,
    ValidationList,
  },
  middleware: ({ store, redirect }) => {
    if (store.getters['auth/authorized']) {
      return redirect('/')
    }
    return redirect()
  },
  layout: 'sign-layout',
  data() {
    const $t = this.$t.bind(this)
    return {
      login: {
        value: '',
        rules: [
          new RequiredRule($t('sign.in.inputs.login.messages.required')),
        ],
        validations: [],
      },
      password: {
        value: '',
        rules: [new RequiredRule($t('sign.in.inputs.password.messages.required'))],
        validations: [],
      },
      form: {
        validations: [],
      },
      loading: false,
      errorHandlers: {
        [Errors.WRONG_CREDENTIALS]: () => ({
          name: Errors.WRONG_CREDENTIALS,
          message: $t('sign.in.form.messages.wrong-sign-in'),
        }),
        [Errors.VALIDATION_EXCEPTION]: () => ({
          name: Errors.VALIDATION_EXCEPTION,
          message: $t('sign.in.form.messages.wrong-sign-in'),
        }),
      },
      MODE_RUN: process.env.MODE_RUN,
    }
  },
  computed: {
    loginIsValid() {
      return !this.login.validations.filter(k => !k.isValid).length && !this.form.validations.length
    },
    passwordIsValid() {
      return (
        !this.password.validations.filter(k => !k.isValid).length && !this.form.validations.length
      )
    },
    formIsValid() {
      return this.loginIsValid && this.passwordIsValid
    },
    getLogin() {
      return this.login.value
    },
    getPassword() {
      return this.password.value
    },
  },
  watch: {
    getLogin() {
      this.form.validations = []
    },
    getPassword() {
      this.form.validations = []
    },
  },
  methods: {
    async submit() {
      setTimeout(async () => {
        if (!this.formIsValid) return
        this.loading = true
        const result = await this.$store.dispatch('auth/signIn', {
          login: this.login.value,
          password: this.password.value,
        })
        if (!result.status) {
          result.errors.forEach(k => {
            const handler = this.errorHandlers[k.message]
            if (handler) {
              this.form.validations.push(handler())
            }
          })
          this.loading = false
          return
        }
        const { id } = this.$store.getters['auth/getUser']
        this.$router.push(
          this.localePath({
            name: '/',
            params: {
              id
            }
          }),
        )
      })
    },
  },
}
</script>

<style lang="stylus">
@import "sign-in.styl"
</style>
