<template lang="pug">
  PageLayout
    .user-achievement__card(:class="getUserAchievementCardClasses")
      .row.no-gutters
        .col-lg.col-12
          .user-achievement__back-to
            MobileBackTo(@click.native="goBackPage") {{ $t('user-progress.back-to') }}
          AchievementCard(
            :achievement="achievement"
            @update:achievement="updateAchievement"
            @remove="remove"
          )
            template(#linkToAchievement)
              NuxtLink(
                ref="linkToCard"
                class="achievement-card__title"
                :to="localePath({ name: 'achievement-id', params: { id: achievement.progress_id } })"
              ) {{ achievement.title }}
        .col-lg-auto.col-12
          CommentsControlContainer
            CommentsControl(
              id="user-achievement-comments"
              :comments="comments"
              :loading-more="loadingMoreComments"
              @createComment="createComment"
              @loadMore="loadMore"
            )
</template>

<script>
import PageLayout from '../General/PageLayout/PageLayout.vue'
import AchievementCard from '../AchievementCard/AchievementCard.vue'
import CommentsControlContainer from '../CommentsControl/CommentControlContainer/CommentsControlContainer.vue'
import CommentsControl from '../CommentsControl/CommentsControl.vue'
import { makeCommentUserAchievement, deleteAchievement } from '../../plugins/api/progress'
import MobileBackTo from '../General/MobileBackTo/MobileBackTo.vue'

export default {
  components: {
    PageLayout,
    AchievementCard,
    CommentsControlContainer,
    CommentsControl,
    MobileBackTo,
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
  data() {
    return {
      removing: false,
    }
  },
  computed: {
    getUser() {
      return this.$store.state.auth.user
    },
    getUserAchievementCardClasses() {
      return {
        'user-achievement__card--disabled': this.removing,
      }
    },
  },
  methods: {
    goBackPage() {
      this.$router.back()
    },
    async createComment({ text, scrollToComment }) {
      const comment = await makeCommentUserAchievement({
        description: text,
        user_progress_id: this.achievement.id,
      })
      comment.data.user = this.getUser
      comment.data.user.full_name = `${this.getUser.firstname} ${this.getUser.surname}`
      scrollToComment(comment.data)
      this.$emit('createComment', comment.data)
    },
    loadMore(e) {
      this.$emit('loadMoreComments', e)
    },
    updateAchievement(achievement) {
      this.$emit('update:achievement', achievement)
    },
    async remove() {
      this.removing = true
      await deleteAchievement(this.achievement.id)
      this.$router.push(
        this.localePath({ name: 'id-index-achievements', params: { id: this.getUser.id } }),
      )
    },
  },
}
</script>

<style lang="stylus">
@import "user-achievement.styl";
</style>
