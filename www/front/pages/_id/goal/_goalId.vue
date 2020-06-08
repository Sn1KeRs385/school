<template lang="pug">
  UserGoal(
    :goal.sync="goal"
    :comments="comments"
    :loadingMoreComments="loadingMoreComments"
    @loadMoreComments="loadMoreComments"
    @createComment="createComment"
  )
</template>

<script>
import UserGoal from '../../../components/UserGoal/UserGoal.vue'
import { showUserGoal, getCommentsUserGoal } from '../../../plugins/api/progress'

export default {
  middleware: 'authenticated',
  components: {
    UserGoal,
  },
  async asyncData({ params }) {
    const { goalId } = params
    const [goal, comments] = await Promise.all([
      showUserGoal(goalId),
      getCommentsUserGoal({
        page: 1,
        records: 15,
        user_progress_id: goalId,
      }),
    ])
    comments.data.items.reverse()
    return {
      // isMe: currentUser.data.id === store.state.auth.user.id,
      goal: goal.data,
      comments: comments.data,
      loadingMoreComments: false,
    }
  },
  methods: {
    async loadMoreComments({ commentId, direction }) {
      this.loadingMoreComments = true
      const partComments = await getCommentsUserGoal({
        user_progress_id: this.goal.id,
        offset_id: commentId,
        direction,
        oldest: direction === '<' ? 0 : 1,
      })
      if (direction === '<') {
        partComments.data.items.reverse()
        partComments.data.items = [...partComments.data.items, ...this.comments.items]
      } else {
        partComments.data.items = [...this.comments.items, ...partComments.data.items]
      }
      this.comments = partComments.data
      this.loadingMoreComments = false
    },
    async createComment(comment) {
      this.comments.meta.total += 1
      this.comments.items.push(comment)
    },
  },
}
</script>
