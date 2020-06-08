<template lang="pug">
  .profile-control(
    @click="clickHeaderHandler"
    ref="control"
  )
    .profile-control__header(
      :class="{'profile-control__header--is-open': isOpen}"
      @click="changeIsOpen(!isOpen)"
      @mousedown.prevent
    )
      .profile-control__name {{ getFullName }}
      .profile-control__header-arrow(
        :class="{'profile-control__header-arrow--is-open': isOpen}"
      )
        ArrowSVG
    .profile-control__menu(v-if="isOpen")
      NuxtLink(
        class="profile-control__menu-item"
        :to="localePath({ name: 'id-index-achievements', params: { id: getUser.id } })"
      ) {{ getFullName }}
        .profile-control__menu-my-profile {{ $t('header.profile.my-profile') }}
      .profile-control__menu-item.profile-control__menu-exit(@click="logout")
        .profile-control__menu-exit-icon
        .profile-control__menu-exit-text {{ $t('header.profile.exit') }}

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
      return this.$store.state.auth.user
    },
    getFullName() {
      return `${this.getUser.first_name} ${this.getUser.last_name}` || this.$t('header.no-name-label')
    },
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
@import "profile-control.styl"
</style>
