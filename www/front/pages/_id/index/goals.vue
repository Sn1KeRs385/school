<template lang="pug">
  ProfileWallGoals(
    :goals.sync="goals"
    @update:goal="updateGoal"
    @remove:goal="removeGoal"
  )
</template>

<script>
import ProfileWallGoals from '../../../components/ProfileCard/ProfileWall/ProfileWallGoals/ProfileWallGoals.vue'
import { getUserGoals } from '../../../plugins/api/user'

export default {
  components: {
    ProfileWallGoals,
  },
  computed: {
    goals: {
      get() {
        if (this.isMe) {
          return this.$store.state.auth.goals
        }
        return this.goalsCollection
      },
      set(collection) {
        if (this.isMe) {
          this.$store.commit('auth/setGoals', collection)
        } else {
          this.goalsCollection = collection
        }
      },
    },
  },
  async asyncData({ params, store }) {
    const { id } = params
    const isMe = Number(id) === store.state.auth.user.id
    let goalsCollection = null
    if (isMe) {
      await store.dispatch('auth/updateGoals')
    } else {
      goalsCollection = (await getUserGoals(id, { page: 1, records: 10 })).data
    }
    return {
      isMe,
      goalsCollection,
    }
  },
  methods: {
    updateGoal({ index, goal }) {
      this.goals = {
        items: [...this.goals.items.slice(0, index), goal, ...this.goals.items.slice(index + 1)],
        meta: { ...this.goals.meta },
      }
    },
    removeGoal({ index }) {
      this.goals = {
        items: [...this.goals.items.slice(0, index), ...this.goals.items.slice(index + 1)],
        meta: { ...this.goals.meta },
      }
    },
  },
}
</script>
