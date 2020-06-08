<template lang="pug">
  PageLayout
    .row.no-gutters
      .col-lg.col-12
        SystemAchievementCard(:achievement="achievement")
      .col-lg-auto.col-12
        CommentsControlContainer
          CommentsControl(
            id="system-achievement-comments"
            :comments="comments"
            :loading-more="loadingMoreComments"
            @createComment="createComment"
            @loadMore="loadMore"
          )
</template>

<script>
import PageLayout from '../General/PageLayout/PageLayout.vue'
import SystemAchievementCard from './SystemAchievementCard/SystemAchievementCard.vue'
import CommentsControl from '../CommentsControl/CommentsControl.vue'
import CommentsControlContainer from '../CommentsControl/CommentControlContainer/CommentsControlContainer.vue'
import { makeCommentSystemAchievement } from '../../plugins/api/progress'

export default {
  components: {
    PageLayout,
    SystemAchievementCard,
    CommentsControlContainer,
    CommentsControl,
  },
  props: {
    achievement: {
      required: true,
      type: Object,
    },
    comments: {
      required: true,
      type: Object,
    },
    loadingMoreComments: {
      required: true,
      type: Boolean,
    },
  },
  computed: {
    getUser() {
      return this.$store.state.auth.user
    },
  },
  methods: {
    async createComment({ text, scrollToComment }) {
      const comment = await makeCommentSystemAchievement({
        description: text,
        progress_id: this.achievement.id,
      })
      comment.data.user = this.getUser
      comment.data.user.full_name = `${this.getUser.firstname} ${this.getUser.surname}`
      this.$emit('createComment', comment.data)
      scrollToComment(comment.data)
    },
    loadMore(e) {
      this.$emit('loadMoreComments', e)
    },
  },
}
</script>
