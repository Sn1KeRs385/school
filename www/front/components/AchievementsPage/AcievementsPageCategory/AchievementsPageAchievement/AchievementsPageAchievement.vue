<template lang="pug">
  NuxtLink(
    class="achievements-page-achievement"
    :class="getClasses"
    :to="getTo"
  )
    .achievements-page-achievement__state
      CheckSVG(v-if="showCompleteIcon")
      LockSVG(v-else)
    .achievements-page-achievement__name {{ achievement.title }}
    .achievements-page-achievement__points {{ getPoints }}
</template>

<script>
import CheckSVG from '../../../SVG/CheckSVG.vue'
import LockSVG from '../../../SVG/LockSVG.vue'

export default {
  components: {
    CheckSVG,
    LockSVG,
  },
  props: {
    achievement: {
      required: true,
      type: Object,
    },
  },
  computed: {
    isUserRoute() {
      return this.$route.params.id
    },
    isMe() {
      if (this.isUserRoute) {
        return this.getUser.id === Number(this.isUserRoute)
      }
      return false
    },
    getWhoAchieved() {
      if (this.isUserRoute) {
        return this.isMe ? this.getUser.id : this.isUserRoute
      }
      return this.getUser.id
    },
    getUser() {
      return this.$store.state.auth.user
    },
    getPoints() {
      return this.achievement.point || this.achievement.difficulty_point
    },
    showCompleteIcon() {
      return this.achievement.complete || this.isAchievedAt
    },
    completeExist() {
      return this.achievement.complete !== undefined
    },
    isAchievedAt() {
      return this.achievement.achieved_at !== null && this.achievement.achieved_at !== undefined
    },
    getClasses() {
      return {
        'achievements-page-achievement--complete': this.achievement.complete || this.isAchievedAt,
      }
    },
    getUserProgress() {
      return this.achievement.user_progress_id || this.achievement.achieved_id
    },
    getTo() {
      if (this.completeExist) {
        if (!this.achievement.complete) {
          return this.localePath({
            name: 'achievement-id',
            params: {
              id: this.achievement.id,
            },
          })
        }
      } else if (!this.isAchievedAt) {
        return this.localePath({
          name: 'achievement-id',
          params: {
            id: this.achievement.id,
          },
        })
      }
      return this.localePath({
        name: 'id-achievement-achievementId',
        params: {
          id: this.getWhoAchieved,
          achievementId: this.getUserProgress,
        },
      })
    },
  },
}
</script>

<style lang="stylus">
@import "achievement-page-achievement.styl"
</style>
