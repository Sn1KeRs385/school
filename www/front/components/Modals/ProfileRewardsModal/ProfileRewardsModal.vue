<template lang="pug">
  AdjacentModalContainer(
    @close="close"
    class="profile-rewards-modal__container"
  )
    .profile-rewards-modal__close(@click="close")
      ModalCloseSVG
    .profile-rewards-modal
      .profile-rewards-modal__content
        .profile-rewards-modal__content__date {{ formatDateReward }}
        .profile-rewards-modal__content__reward-img
          .profile-rewards-modal__content__reward-img__handler(
            v-if="isCompleted"
          )
            BigRewardIconSVG(
              v-if="!isRewardIconExists"
              class="profile-rewards-modal__content__reward-img__icon"
            )
            .profile-rewards-modal__content__reward-img__different-icon(
              v-else
              :style="getRewardIcon"
            )
          LockRewardIconSVG(
            v-else
            class="profile-rewards-modal__content__reward-img__icon"
          )
        .profile-rewards-modal__content__title {{ rewardCardCopy.name }}
        .profile-rewards-modal__content__description(v-html="rewardCardCopy.description")
        hr.profile-rewards-modal__content__divider
        .profile-rewards-modal__content__achievements
          .profile-rewards-modal__content__achievements__title {{ rewardsAchievementsTitle }}
          AchievementsPageAchievement(
            v-for="(rewardItem, i) in rewardAchievementsCopy.items"
            :key="profileRewardsModalItem(rewardItem, i)"
            :achievement="rewardItem"
          )
        .profile-rewards-modal__content__more-load(v-if="nextPageExist")
          Button(
            :secondary="true"
            :loading="loadingMore"
            @click.native="loadMore"
          ) {{ $t('profile-rewards.load-more') }}
            template(slot="before")
              .profile-rewards-modal__content__more-load__arrow-load
                ArrowLoadSVG
        .profile-rewards-modal__content__total-points(v-if="isCompleted") {{ getTotalPoints }}
        .profile-rewards-modal__content__add-button(v-if="showButton")
          Button(
            :loading="loadingAction"
            @click.native="actionButtonMethod"
            class="profile-rewards-modal__content__button-action"
          ) {{ actionButtonText }}
            template(slot="before")
              .profile-rewards-modal__content__button-action__icon
                PlusSVG(v-if="!isRewardAdd")
                MinusSVG(v-else)

</template>

<script>
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import ArrowLoadSVG from '../../SVG/ArrowLoadSVG.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'
import BigRewardIconSVG from '../../SVG/BigRewardIconSVG.vue'
import LockRewardIconSVG from '../../SVG/LockRewardIconSVG.vue'
import PlusSVG from '../../SVG/PlusSVG.vue'
import MinusSVG from '../../SVG/MinusSVG.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import AchievementsPageAchievement from '../../AchievementsPage/AcievementsPageCategory/AchievementsPageAchievement/AchievementsPageAchievement.vue'
import { addReward, deleteReward, getRewardAchievements } from '../../../plugins/api/rewards'

export default {
  components: {
    AdjacentModalContainer,
    ArrowLoadSVG,
    ModalCloseSVG,
    BigRewardIconSVG,
    LockRewardIconSVG,
    PlusSVG,
    MinusSVG,
    Button,
    AchievementsPageAchievement,
  },
  props: {
    rewardCard: {
      type: Object,
      required: true,
    },
    rewardAchievements: {
      type: Object,
      required: true,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      loadingMore: false,
      loadingAction: false,
      rewardCardCopy: this.rewardCard,
      rewardAchievementsCopy: this.rewardAchievements,
    }
  },
  computed: {
    actionButtonMethod() {
      if (!this.isRewardAdd) {
        return this.addReward
      }
      if (this.isUnCompleted) {
        return this.deleteReward
      }
      return ''
    },
    nextPageExist() {
      return this.rewardAchievementsCopy.meta.current_page !== this.rewardAchievementsCopy.meta.last_page
    },
    getTotalPoints() {
      return this.$t('profile-rewards.total-points', { points: this.rewardCardCopy.max_point })
    },
    showButton() {
      return (!this.isRewardAdd || this.isUnCompleted) && !this.isRequired
    },
    actionButtonText() {
      if (!this.isRewardAdd) {
        return this.$t('profile-rewards.add-reward')
      }
      if (this.isUnCompleted) {
        return this.$t('profile-rewards.remove-reward')
      }
      return ''
    },
    isRequired() {
      return this.rewardCardCopy.required === 1
    },
    isCompleted() {
      return this.isAchieved && this.isRewardAdd
    },
    isUnCompleted() {
      return !this.isAchieved && this.isRewardAdd
    },
    rewardsAchievementsTitle() {
      return this.$t('profile-rewards.modal-title', {
        completed: this.rewardCardCopy.have_progresses,
        all: this.rewardCardCopy.need_progresses,
      })
    },
    isRewardAdd() {
      return this.rewardCardCopy.me.reward_add === 1
    },
    isAchieved() {
      return this.rewardCardCopy.me.achieved_at !== null
    },
    isRewardIconExists() {
      if (this.rewardCardCopy.icon) {
        return this.rewardCardCopy.icon.length > 0
      }
      return false
    },
    getRewardIcon() {
      const { url } = this.rewardCardCopy.icon[0]
      return `background-image: url('${url}')`
    },
    formatDateReward() {
      if (this.rewardCardCopy.achieved_at) {
        const [year, month, day] = this.rewardCardCopy.achieved_at.substring(0, 10).split('-')
        return `${day}.${month}.${year}`
      }
      return ''
    },
  },
  methods: {
    async addReward() {
      this.loadingAction = true
      await addReward({
        reward_id: this.rewardCardCopy.id,
      })
      this.rewardCardCopy.me.reward_add = 1
      this.$emit('updateUserRewards')
      this.loadingAction = false
    },
    async deleteReward() {
      this.loadingAction = true
      await deleteReward({
        reward_id: this.rewardCardCopy.id,
      })
      this.rewardCardCopy.me.reward_add = 0
      this.$emit('updateUserRewards')
      this.loadingAction = false
    },
    async loadMore() {
      this.loadingMore = true
      const { data } = await getRewardAchievements(this.rewardCardCopy.id, this.$route.params.id, {
        page: this.rewardAchievementsCopy.meta.current_page + 1,
        records: 5,
      })
      data.items = [...this.rewardAchievementsCopy.items, ...data.items, ]
      this.rewardAchievementsCopy = data
      this.loadingMore = false
    },
    profileRewardsModalItem(item, key) {
      return `${item.title}${key}-profile-rewards-modal-item`
    },
    close() {
      this.$emit('close')
    },
  },
}
</script>

<style lang="stylus">
@import "profile-rewards-modal.styl"
</style>
