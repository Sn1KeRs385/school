<template lang="pug">
  SystemAchievement(
    :achievement="achievement"
    :comments="comments"
    :loadingMoreComments="loadingMoreComments"
    @loadMoreComments="loadMoreComments"
    @createComment="createComment"
  )
</template>

<script>
import SystemAchievement from '../../components/SystemAchievement/SystemAchievement.vue'
import { showSystemAchievement, getCommentsSystemAchievement } from '../../plugins/api/progress'

export default {
  middleware: 'authenticated',
  components: {
    SystemAchievement,
  },
  async asyncData({ params }) {
    const { id } = params
    const [achievement, comments] = await Promise.all([
      showSystemAchievement(id),
      getCommentsSystemAchievement({
        progress_id: id,
      }),
    ])
    comments.data.items.reverse()
    return {
      achievement: achievement.data,
      comments: comments.data,
      loadingMoreComments: false,
    }
  },
  methods: {
    async loadMoreComments({ commentId, direction }) {
      this.loadingMoreComments = true
      const partComments = await getCommentsSystemAchievement({
        progress_id: this.achievement.id,
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
