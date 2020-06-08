<template lang="pug">
  PageLayout
    .user-goal__card(:class="getUserGoalCardClasses")
      .row.no-gutters
        .col-lg.col-12
          .user-goal__back-to
            MobileBackTo(@click.native="$router.back()") Назад
          GoalCard(
            :goal="goal"
            :full="true"
            @update:goal="updateGoal"
            @remove="remove"
          )
        .col-lg-auto.col-12
          CommentsControlContainer
            CommentsControl(
              id="user-goal-comments"
              :comments="comments"
              :loading-more="loadingMoreComments"
              @createComment="createComment"
              @loadMore="loadMore"
            )
</template>

<script>
import PageLayout from '../General/PageLayout/PageLayout.vue'
import CommentsControlContainer from '../CommentsControl/CommentControlContainer/CommentsControlContainer.vue'
import CommentsControl from '../CommentsControl/CommentsControl.vue'
import GoalCard from '../GoalCard/GoalCard.vue'
import { makeCommentUserGoal, deleteGoal } from '../../plugins/api/progress'
import MobileBackTo from '../General/MobileBackTo/MobileBackTo.vue'

export default {
  components: {
    PageLayout,
    CommentsControlContainer,
    CommentsControl,
    GoalCard,
    MobileBackTo,
  },
  props: {
    goal: {
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
    getUserGoalCardClasses() {
      return {
        'user-goal__card--disabled': this.removing,
      }
    },
  },
  methods: {
    async createComment({ text, scrollToComment }) {
      const comment = await makeCommentUserGoal({
        description: text,
        user_progress_id: this.goal.id,
      })
      comment.data.user = this.getUser
      comment.data.user.full_name = `${this.getUser.firstname} ${this.getUser.surname}`
      scrollToComment(comment.data)
      this.$emit('createComment', comment.data)
    },
    loadMore(e) {
      this.$emit('loadMoreComments', e)
    },
    updateGoal(newGoal) {
      this.$emit('update:goal', newGoal)
    },
    async remove() {
      this.removing = true
      await deleteGoal(this.goal.id)
      this.$router.push(
        this.localePath({ name: 'id-index-goals', params: { id: this.getUser.id } }),
      )
    },
  },
}
</script>

<style lang="stylus">
@import "user-goal.styl";
</style>
