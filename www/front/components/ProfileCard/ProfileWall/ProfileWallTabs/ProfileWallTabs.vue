<template lang="pug">
  .profile-wall-tabs
    NuxtLink(
      :to="localePath('id-index-achievements')"
      class="profile-wall-tabs__tab"
      :class="{ 'profile-wall-tabs__tab--select': isAchievements }"
      :style="{ 'flex-basis': `${50}%` }"
    ) {{ $t('profile-card.wall.tabs.left') }}
    NuxtLink(
      :to="localePath('id-index-goals')"
       class="profile-wall-tabs__tab"
      :class="{ 'profile-wall-tabs__tab--select': !isAchievements }"
      :style="{ 'flex-basis': `${50}%` }"
    ) {{ $t('profile-card.wall.tabs.right') }}

</template>

<script>
export default {
  computed: {
    isAchievements() {
      return this.$route.fullPath.includes('achievements')
    },
  },
}
</script>

<style lang="stylus">
@import "profile-wall-tabs.styl"
</style>
