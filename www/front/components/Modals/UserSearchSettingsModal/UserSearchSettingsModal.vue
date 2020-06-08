<template lang="pug">
  AdjacentModalContainer(@close="close")
    .user-search-settings__close(@click="close")
      ModalCloseSVG
    .user-search-settings
      .user-search-settings__back-to-profile
        MobileBackTo(
          @click.native="close"
        ) {{ $t('search-user.settings.back-to') }}
      SearchUsersSettings(
        :name="name"
        :settings="settings"
        :loading="loading"
        :max-age="maxAge"
        :min-age="minAge"
        @update:maxAge="updateMaxAge"
        @update:minAge="updateMinAge"
        @settingsUpdate="settingsUpdate"
        @clearSettings="clearSettings"
      )
</template>

<script>
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import SearchUsersSettings from '../../SearchUsersPage/SearchUsersSettings/SearchUsersSettings.vue'
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'

export default {
  components: {
    SearchUsersSettings,
    AdjacentModalContainer,
    MobileBackTo,
    ModalCloseSVG,
  },
  props: {
    settings: {
      required: true,
      type: Object,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    minAge: {
      required: true,
      type: null,
    },
    maxAge: {
      required: true,
      type: null,
    },
    name: {
      required: true,
      type: String,
    },
  },
  computed: {
    screenWidth() {
      return this.$store.state.screenWidth
    },
  },
  watch: {
    screenWidth(width) {
      if (width >= 992) {
        this.close()
      }
    },
  },
  methods: {
    close() {
      this.$emit('close')
    },
    updateMaxAge(maxAge) {
      this.$emit('update:max-age', maxAge)
    },
    updateMinAge(minAge) {
      this.$emit('update:min-age', minAge)
    },
    settingsUpdate(settings) {
      this.$emit('settingsUpdate', settings)
    },
    clearSettings() {
      this.$emit('clearSettings')
    },
  },
}
</script>

<style lang="stylus">
@import "user-search-settings-modal.styl"
</style>
