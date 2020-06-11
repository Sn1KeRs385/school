<template lang="pug">
  .menu-control(
    @click="clickHeaderHandler"
    ref="control"
  )
    .menu-control__header(
      :class="{'menu-control__header--is-open': isOpen}"
      @click="changeIsOpen(!isOpen)"
      @mousedown.prevent
    )
      .menu-control__name {{ $t('header.profile.menu') }}
      .menu-control__header-arrow(
        :class="{'menu-control__header-arrow--is-open': isOpen}"
      )
        ArrowSVG
    .menu-control__menu(v-if="isOpen")
      NuxtLink(
        v-for="item in mainMenu"
        :key="item.name"
        class="profile-control__menu-item"
        :to="item.link"
      ) {{ item.name }}

</template>
<!--.profile-control__menu-item.profile-control__menu-notifications-->
<!--.profile-control__menu-notifications-text Уведомления-->
<!--.profile-control__menu-notifications-counter 68-->
<script>
import ClosableByClickMixin from '../../../assets/js/vue-mixins/closable-by-click'
import ArrowSVG from '../../SVG/ArrowSVG.vue'
import ToolTip from '../../General/ToolTip/ToolTip.vue'

export default {
  components: {
    ArrowSVG,
    ToolTip,
  },
  mixins: [ClosableByClickMixin('isOpen', 'changeIsOpen', 'getControl')],
  data() {
    return {
      isOpen: false,
    }
  },
  computed: {
    getControl() {
      return this.$refs.control
    },
    getUser() {
      console.log(this.$store.state.auth.user);
      return this.$store.state.auth.user
    },
    getFullName() {
      return `${this.getUser.first_name} ${this.getUser.last_name}` || this.$t('header.no-name-label')
    },
    mainMenu() {
      return [
        {name:"Объявления", link:'/'},
        {name:"Пользователи", link:'/users'},
        {name:"Направления подготовки", link:'/specializations'},
      ];
    }
  },
  methods: {
    clickHeaderHandler(e) {
      setTimeout(() => {
        if (e.target.tagName === 'A') {
          this.changeIsOpen(false)
        }
      }, 0)
    },
    changeIsOpen(isOpen) {
      this.isOpen = isOpen
    },
    logout() {
      const signinPage = this.localePath('signin')
      this.$store.dispatch('auth/signOut').then(() => {
        this.$router.push(signinPage)
      })
    },
  },
}
</script>

<style lang="stylus">
@import "menu-control.styl"
</style>
