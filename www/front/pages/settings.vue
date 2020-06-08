<template lang="pug">
  .settings
    .container.settings__container
      .settings__content
        .row.no-gutters
          .col-12.d-lg-none(:class="{'d-block': !isParentPage, 'd-none': isParentPage}")
            NuxtLink(class="settings__back-to-settings" :to="localePath({ name: 'settings' })")
              .settings__back-to-settings-arrow-container
                ArrowSVG
              | {{ $t('settings.back-to') }}
          .col(:class="{'d-none': isParentPage}")
            NuxtChild(ref="nuxtChild")
          .col-lg-auto.col-12.order-lg-1.order-first.d-lg-block(
            :class="{'d-none': !isParentPage}"
          )
            .settings__menu
              PageNavigationMenuLink(
                :to="localePath('settings-personal')"
              ) {{ $t('settings.menu.personal') }}
                template(slot="icon")
                  ProfileSVG
              PageNavigationMenuLink(
                :to="localePath('settings-private')"
              ) {{ $t('settings.menu.private') }}
                template(slot="icon")
                  PrivateSVG
              //
                PageNavigationMenuLink(
                  :to="localePath('settings-password')"
                ) {{ $t('settings.menu.password') }}
                  template(slot="icon")
                    LookSVG
                PageNavigationMenuLink(
                  :to="localePath('settings-phone')"
                ) {{ $t('settings.menu.phone') }}
                  template(slot="icon")
                    PhoneSVG
              //
              PageNavigationMenuLink(
                :to="localePath('settings-priority-categories')"
              ) {{ $t('settings.menu.categories') }}
                template(slot="icon")
                  TicketSVG
</template>

<script>
import ProfileSVG from '../components/SVG/ProfileSVG.vue'
import PrivateSVG from '../components/SVG/SlashEyeSVG.vue'
import LookSVG from '../components/SVG/LookSVG.vue'
import PhoneSVG from '../components/SVG/PhoneSVG.vue'
import TicketSVG from '../components/SVG/TicketSVG.vue'
import ArrowSVG from '../components/SVG/ArrowSVG.vue'
import ScreenResizeMixin from '../assets/js/vue-mixins/screen-resize'
import PageNavigationMenuLink from '../components/NavMenuLink/PageNavigationMenuLink.vue'

export default {
  components: {
    PageNavigationMenuLink,
    ProfileSVG,
    PrivateSVG,
    LookSVG,
    PhoneSVG,
    TicketSVG,
    ArrowSVG,
  },
  mixins: [ScreenResizeMixin],
  scrollToTop: false,
  middleware: 'authenticated',
  computed: {
    isParentPage() {
      return `settings___${this.$i18n.locale}` === this.$route.name
    },
  },
}
</script>

<style lang="stylus">
@import "settings.styl"
</style>
