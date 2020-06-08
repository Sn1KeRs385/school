<template lang="pug">
  div
    .profile-reward-card(
        v-if="!isAchieved"
        :class="profileRewardCardClasses"
        @click="showRewardModal(reward.id)"
      )
        .profile-reward-card__title {{ reward.name }}
        .profile-reward-card__wrapper
          .profile-reward-card__progress-bar
            .profile-reward-card__progress-bar__progress(:style="getUserProgress")
          .profile-reward-card__progress
            .profile-reward-card__progress__content
              RewardLockSVG
              .profile-reward-card__progress__content__text {{ getCardText }}
              ArrowSVG(
                class="profile-reward-card__progress__content__arrow"
              )
    .profile-reward-card(
        v-else
        :class="profileRewardCardClasses"
        @click="showRewardModal(reward.id)"
      )
      .profile-reward-card__achieved
        .profile-reward-card__achieved__header
          .profile-reward-card__achieved__header__date {{ formatDateReward }}
          RewardIconSVG(
            v-if="!isRewardIconExists"
            class="profile-reward-card__achieved__header__icon"
          )
          .profile-reward-card__achieved__header__different-icon(
            v-else
            :style="getRewardIcon"
          )
          ArrowSVG(
            class="profile-reward-card__achieved__header__arrow"
          )
        .profile-reward-card__achieved__title {{ reward.name }}
</template>

<script>
import RewardLockSVG from '../../../SVG/RewardLockSVG.vue'
import ArrowSVG from '../../../SVG/ArrowSVG.vue'
import RewardIconSVG from '../../../SVG/RewardIconSVG.vue'
import ProfileRewardsModal from '../../../Modals/ProfileRewardsModal/ProfileRewardsModal.vue'
import { getRewardCard, getRewardAchievements } from '../../../../plugins/api/rewards'

export default {
  components: {
    RewardLockSVG,
    ArrowSVG,
    RewardIconSVG,
  },
  inject: ['setModal'],
  props: {
    reward: {
      type: Object,
      required: true,
    },
  },
  computed: {
    getCardText() {
      return this.$t('profile-rewards.card-text', {
        completed: this.reward.have_progresses,
        all: this.reward.need_progresses,
      })
    },
    profileRewardCardClasses() {
      return { 'profile-reward-card-achieved': this.isAchieved }
    },
    isAchieved() {
      return this.reward.achieved_at !== null
    },
    formatDateReward() {
      if (this.isAchieved) {
        const [year, month, day] = this.reward.achieved_at.substring(0, 10).split('-')
        return `${day}.${month}.${year}`
      }
      return ''
    },
    getUserProgress() {
      const percents = (this.reward.have_progresses / this.reward.need_progresses) * 100
      return `width: ${percents}%`
    },
    isRewardIconExists() {
      if (this.reward.icon) {
        return this.reward.icon.length > 0
      }
      return false
    },
    getRewardIcon() {
      const { url } = this.reward.icon[0]
      return `background-image: url('${url}')`
    },
  },
  methods: {
    updateUserRewards() {
      this.$emit('updateUserRewards')
    },
    async showRewardModal(id) {
      const userId = this.$route.params.id
      const [rewardCard, rewardAchievements] = await Promise.all([
        getRewardCard(id, userId),
        getRewardAchievements(id, userId, {
          page: 1,
          records: 5,
        }),
      ])
      if (rewardCard.status && rewardAchievements.status) {
        this.setModal({
          component: ProfileRewardsModal,
          props: {
            rewardCard: rewardCard.data,
            rewardAchievements: rewardAchievements.data,
          },
          events: {
            updateUserRewards: this.updateUserRewards,
            close: this.setModal,
          },
        })
      }
    },
  },
}
</script>

<style lang="stylus" scoped>
@import 'profile-reward-card.styl';
</style>
