<template lang="pug">
  ProfileWallAchievements(
    :is-me="isMe"
    :achievements.sync="achievements"
    @update:achievement="updateAchievement"
    @remove:achievement="removeAchievement"
  )
</template>

<script>
import ProfileWallAchievements from '../../../components/ProfileCard/ProfileWall/ProfileWallAchievements/ProfileWallAchievements.vue'
import { getUserAchievements } from '../../../plugins/api/user'

export default {
  components: {
    ProfileWallAchievements,
  },
  computed: {
    achievements: {
      get() {
        if (this.isMe) {
          return this.$store.state.auth.achievements
        }
        return this.achievementsCollection
      },
      set(collection) {
        if (this.isMe) {
          this.$store.commit('auth/setAchievements', collection)
        } else {
          this.achievementsCollection = collection
        }
      },
    },
  },
  async asyncData({ params, store }) {
    const { id } = params
    const isMe = Number(id) === store.state.auth.user.id
    let achievementsCollection = null
    if (isMe) {
      await store.dispatch('auth/updateAchievements')
    } else {
      achievementsCollection = (await getUserAchievements(id, { page: 1, records: 10 })).data
    }
    return {
      isMe,
      achievementsCollection,
    }
  },
  methods: {
    updateAchievement({ index, achievement }) {
      this.achievements = {
        items: [
          ...this.achievements.items.slice(0, index),
          achievement,
          ...this.achievements.items.slice(index + 1),
        ],
        meta: { ...this.achievements.meta },
      }
    },
    removeAchievement({ index }) {
      this.achievements = {
        items: [
          ...this.achievements.items.slice(0, index),
          ...this.achievements.items.slice(index + 1),
        ],
        meta: { ...this.achievements.meta },
      }
    },
  },
}
</script>
