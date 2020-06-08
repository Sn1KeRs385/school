<template lang="pug">
  div
    GoalCard(
      v-for="(goal, i) in goals.items"
      :key="goal.id"
      :margin="i >= 1 ? '10px 0 0 0' : '0'"
      :goal="goal"
      @update:goal="updateGoal(i, $event)"
      @changeLike="changeLikes"
      @remove="remove(i)"
    )
    InfiniteLoading(v-if="goals.meta.next_exists" :distance="500" @infinite="infiniteHandler")
</template>

<script>
import GoalCard from '../../../GoalCard/GoalCard.vue'
import { getUserGoals } from '../../../../plugins/api/user'
import { deleteGoal } from '../../../../plugins/api/progress'

export default {
  inject: ['changeLikes'],
  components: {
    GoalCard,
  },
  props: {
    goals: {
      required: true,
      type: Object,
    },
  },
  data() {
    return {
      countRemoved: 0,
    }
  },
  computed: {
    getUserId() {
      return this.$route.params.id
    },
  },
  methods: {
    async remove(index) {
      this.countRemoved += 1
      const goal = this.goals.items[index]
      this.$emit('remove:goal', { index, goal })
      this.goals.items.splice(index, 1)
      this.changeLikes(-goal.likes_count)
      await deleteGoal(goal.id)
    },
    async infiniteHandler(e) {
      const paginate = await this.loadMore()
      this.$emit('update:goals', paginate)
      if (!paginate.meta.next_exists) {
        e.complete()
      } else {
        e.loaded()
      }
    },
    async loadMore(currentPaginate = this.goals) {
      const { data } = await getUserGoals(this.getUserId, {
        records: currentPaginate.meta.records,
        page: currentPaginate.meta.page + 1,
        sub: 0,
        offset: this.countRemoved,
      })
      this.countRemoved = 0
      data.items = [...currentPaginate.items, ...data.items]
      return data
    },
    updateGoal(index, goal) {
      this.$emit('update:goal', { index, goal })
    },
  },
}
</script>
