<template lang="pug">
  UserAchievement(
    :achievement.sync="achievement"
    :comments="comments"
    :loadingMoreComments="loadingMoreComments"
    @loadMoreComments="loadMoreComments"
    @createComment="createComment"
  )
</template>

<script>
import UserAchievement from '../../../components/UserAchievement/UserAchievement.vue'
import { showUserAchievement, getCommentsUserAchievement } from '../../../plugins/api/progress'

export default {
  head() {
    return {
      title: this.achievement.title,
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.achievement.comment,
        },
        {
          property: 'og:title',
          content: this.achievement.title,
        },
        { property: 'og:description', content: this.achievement.comment },
      ],
    }
  },
  middleware: 'authenticated',
  components: {
    UserAchievement,
  },
  async asyncData({ params }) {
    const { achievementId } = params
    const [achievement, comments] = await Promise.all([
      showUserAchievement(achievementId),
      getCommentsUserAchievement({
        page: 1,
        records: 15,
        user_progress_id: achievementId,
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
      const partComments = await getCommentsUserAchievement({
        user_progress_id: this.achievement.id,
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
