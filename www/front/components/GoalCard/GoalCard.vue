<template lang="pug">
  .goal-card(:style="{ margin: margin }")
    .goal-card__header
      NuxtLink(
        class="goal-card__header-user-card"
        :to="localePath({ name: 'id-index-achievements', params: { id: localGoal.user.id } })"
      )
        MinCardUser(:current-user="localGoal.user" :with-arrow="false")
    .goal-card__body
      NuxtLink(
        ref="linkToCard"
        class="goal-card__title"
        :to="localePath({ name: 'id-goal-goalId', params: { id: localGoal.user.id, goalId: localGoal.id } })"
        ) {{ goal.title }}
      .goal-card__title-section
        span.goal-card__title-section-geo(
          v-if="localGoal.geo"
        ) {{ localGoal.geo }}
        .goal-card__title-settings
          img(:src="getCategoryIcon")
        span {{ localGoal.category_name }}
      .goal-card__media-info(v-if="isMediaLoading") {{ getMediaLoading }}
      .goal-card__description {{ localGoal.comment }}
      .goal-card__subcategories
        NuxtLink(
          class="achievement-card__description-subcategory"
          v-for="subcategory in localGoal.subcategories"
          :key="subcategory.id"
          :to="localePath({ name: 'achievements-keyword-subcategory', params: { keyword: getCategory.keyword, subcategory: subcategory.id } })"
        ) {{ subcategory.name }}
      .goal-card__completed(v-if="full && (isMy || (isComplete || isModerate))")
        Button(
          @click.native="toAchievement"
          :disabled="isComplete"
        ) {{ getStateComplete }}
          template(slot="after")
            .goal-card__completed-check-box
              ArrowSVG
      .goal-card__info
        .goal-card__info-time
          TimeAgo(:date-time="localGoal.created_at" :timeout="10000")
        .goal-card__info-points
          StarAchievementCardSVG
        | {{ localGoal.difficulty_point }}
        .goal-card__info-likes
          LikeSVG
        | {{ localGoal.likes_count }}
      .goal-card__footer
        .goal-card__footer-item
          .goal-card__footer-item-content(
            :class="{ 'like': hasLike }"
            @click="changeLike"
          )
            LikeAchievementCardSVG
            .goal-card__footer-item-text {{ $t('progress-card.likes') }}
        .goal-card__footer-item
          .goal-card__footer-item-content(
            @click="showShareModal"
          )
            RepostAchievementCardSVG
            .goal-card__footer-item-text {{ $t('progress-card.share') }}
        .goal-card__footer-item(v-if="!localGoal.show_delete")
          .goal-card__footer-item-content(
            :class="{ 'disabled': !asTarget }"
            @click="toTarget"
          )
            PlusAchievementCardSVG
            .goal-card__footer-item-text {{ getIsTargetText }}
        .goal-card__footer-item(v-if="localGoal.show_edit")
          .goal-card__footer-item-content(
            @click="showEditModal"
          )
            EditAchievementCardSVG
            .goal-card__footer-item-text {{ $t('progress-card.edit') }}
        .goal-card__footer-item(v-if="localGoal.show_delete")
          .goal-card__remove-question
            ProgressRemoveQuestion(
              :visible.sync="removeVisible"
              :title="$t('remove-progress.goal.title')"
              @resolve="removeGoal"
              @reject="removeVisible = false"
            )
          .goal-card__footer-item-content(
            @click="removeVisible = true"
          )
            RemoveAchievementCardSVG
            .goal-card__footer-item-text {{ $t('progress-card.remove') }}
</template>

<script>
import cloneDeep from 'lodash.clonedeep'
import MinCardUser from '../MinCardUser/MinCardUser.vue'
import StarAchievementCardSVG from '../SVG/StarAchievementCardSVG.vue'
import LikeSVG from '../SVG/LikesSVG.vue'
import LikeAchievementCardSVG from '../SVG/LikeAchievementCardSVG.vue'
import RepostAchievementCardSVG from '../SVG/RepostAchievementCardSVG.vue'
import FakeAchievementCardSVG from '../SVG/FakeAchievementCardSVG.vue'
import PlusAchievementCardSVG from '../SVG/PlusAchievementCardSVG.vue'
import EditAchievementCardSVG from '../SVG/EditAchievementCardSVG.vue'
import RemoveAchievementCardSVG from '../SVG/RemoveAchievementCardSVG.vue'
import TimeAgo from '../General/TimeAgo.vue'
import ShareModal from '../Modals/ShareModal/ShareModal.vue'
import ProgressDescription from '../ProgressDescription/ProgressDescription.vue'
import { progressLike, achievementAsTarget } from '../../plugins/api/progress'
import Button from '../Interactives/Controls/Button/Button.vue'
// import CheckBoxSVG from '../SVG/CheckBoxSVG.vue'
import ArrowSVG from '../SVG/ArrowSVG.vue'
import GoalToTargetModal from '../Modals/GoalCompleteModal/GoalCompleteModal.vue'
import GoalEditModal from '../Modals/GoalEditModal/GoalEditModal.vue'
import ProgressRemoveQuestion from '../ProgressRemoveQuestion/ProgressRemoveQuestion.vue'
import ProgressDifficulties from '../../assets/js/constatns/progress-diffculties'

export default {
  inject: ['setModal'],
  components: {
    MinCardUser,
    StarAchievementCardSVG,
    LikeSVG,
    LikeAchievementCardSVG,
    RepostAchievementCardSVG,
    FakeAchievementCardSVG,
    PlusAchievementCardSVG,
    EditAchievementCardSVG,
    RemoveAchievementCardSVG,
    TimeAgo,
    ProgressDescription,
    Button,
    // CheckBoxSVG,
    ArrowSVG,
    GoalToTargetModal,
    ProgressRemoveQuestion,
  },
  props: {
    goal: {
      required: true,
      type: Object,
    },
    margin: {
      type: String,
      default: '0',
    },
    full: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    this.$t.bind(this)
    const { goal } = this
    return {
      localGoal: cloneDeep(goal),
      loadingLike: false,
      removeVisible: false,
      isMy: this.$store.state.auth.user.id === this.goal.user_id,
    }
  },
  computed: {
    hasLike: {
      get() {
        return this.localGoal.has_like
      },
      set(val) {
        this.localGoal.has_like = val
      },
    },
    asTarget: {
      get() {
        return this.localGoal.show_target
      },
      set(val) {
        this.localGoal.show_target = val
      },
    },
    isComplete() {
      return !!this.localGoal.is_complete
    },
    isModerate() {
      return (
        !!this.localGoal.is_moderate ||
        (this.isComplete && this.goal.difficulty_id === ProgressDifficulties.Simple)
      )
    },
    getIsTargetText() {
      if (this.asTarget) {
        return this.$t('progress-card.to-target')
      }
      return this.$t('progress-card.already-target')
    },
    isMediaLoading() {
      return (
        this.goal.media_not_finished_count !== 0 && this.goal.media_not_finished_count !== undefined
      )
    },
    getMediaLoading() {
      return this.$t('progress-card.media-loading', { files: this.goal.media_not_finished_count })
    },
    getStateComplete() {
      if (!this.isComplete) {
        return this.$t('goal-card.completed')
      }
      if (!this.isModerate) {
        return this.$t('goal-card.moderate')
      }
      return this.$t('goal-card.finish')
    },
    getLink() {
      return `${window.location.origin}${this.$refs.linkToCard.to}`
    },
    getCategoryIcon() {
      return this.localGoal.category_icon
    },
    getCategory() {
      return this.$store.state.progress.categories.find(k => k.id === this.localGoal.category.id)
    },
    getUser() {
      return this.$store.state.auth.user
    },
    canDeleteGoal() {
      return this.localGoal.show_delete
    },
  },
  watch: {
    localGoal: {
      handler(localGoal) {
        this.$emit('update:goal', cloneDeep(localGoal))
      },
      deep: true,
    },
  },
  methods: {
    async changeLike() {
      this.loadingLike = true
      const oldLike = !this.hasLike
      this.$emit('changeLike', oldLike ? 1 : -1)
      this.hasLike = !this.hasLike
      this.localGoal.likes_count += 1 * oldLike ? 1 : -1
      await progressLike({
        user_progress_id: this.localGoal.id,
      })
      this.loadingLike = false
    },
    async toTarget() {
      if (!this.asTarget) return
      this.asTarget = false
      await achievementAsTarget(this.localGoal.id)
    },
    showEditModal() {
      this.setModal({
        component: GoalEditModal,
        props: {
          goal: { ...this.localGoal },
        },
        events: {
          close: this.setModal,
          submit: (newGoal, message) => {
            this.$toast.global.success_message({ message })
            this.localGoal.comment = newGoal.comment
          },
        },
      })
    },
    showShareModal() {
      const image = 'http://dev-liga.mintrocket.ru:8066/post_picture.png'
      // const points = this.localGoal.difficulty_point
      const localeString = this.canDeleteGoal
        ? 'goal-card.my-share-message'
        : 'goal-card.share-message'
      const title = this.$t(localeString, {
        goal: this.localGoal.title,
      })
      this.setModal({
        bodyClass: 'modal-open',
        component: ShareModal,
        props: {
          url: this.getLink,
          title,
          description: this.localGoal.comment,
          image,
        },
        events: {
          close: this.setModal,
        },
      })
    },
    toAchievement() {
      if (this.isComplete || this.isModerate || !this.isMy) return
      this.setModal({
        component: GoalToTargetModal,
        props: {
          goal: { ...this.localGoal },
        },
        events: {
          close: this.setModal,
          submit: message => {
            this.$toast.global.success_message({ message })
            this.localGoal.is_complete = 1
            this.$router.push(
              this.localePath({ name: 'id-index-achievements', params: { id: this.getUser.id } }),
            )
          },
        },
      })
    },
    async removeGoal() {
      this.removeVisible = false
      this.$emit('remove', this.localGoal)
    },
  },
}
</script>

<style lang="stylus">
@import "goal-card.styl"
</style>
