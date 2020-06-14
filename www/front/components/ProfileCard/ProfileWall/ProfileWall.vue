<template lang="pug">
  .profile-wall
    ProfileWallTabs
    ToolTip(
      v-if="showHintsForMe"
      :content="getProfileCardHints[0]"
      :hint-number="-1"
      class="tool-tip__mark-container-header tool-tip__mark-container-profile-wall"
    )
    NuxtChild
</template>

<script>
import ProfileWallTabs from './ProfileWallTabs/ProfileWallTabs.vue'
import ToolTip from '../../General/ToolTip/ToolTip.vue'

export default {
  components: {
    ProfileWallTabs,
    ToolTip,
  },
  props: {
    showHintsForMe: {
      type: Boolean,
      default: null,
    },
    getProfileCardHints: {
      type: Array,
      default: null,
    },
  },
}
</script>

<style lang="stylus">
@import "profile-wall.styl"
</style>
