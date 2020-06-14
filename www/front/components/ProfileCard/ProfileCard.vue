<template lang="pug">
  PageLayout
    .profile-card
      .row.no-gutters
        .col-lg.col-12(
          v-if="!isRewards"
        )
          CategoryStats(
            :current-user="currentUser"
            :categories="categories"
            :board="board"
            @update:board="updateBoard"
          )
          ProfileWall(
            :is-me="isMe"
            :current-user="currentUser"
            :show-hints-for-me="showHintsForMe"
            :get-profile-card-hints="getProfileCardHints"
          )
        .col-lg.col-12(
          v-else-if="isUserRewards"
        )
          ProfileRewards(
            :rewards="userRewards"
            @changeRewards="changeUserRewards"
            @updateUserRewards="updateUserRewards"
          )
        .col-lg.col-12(
          v-else
        )
          ProfileRewardsCatalog(
            :user-rewards="userRewards"
            :rewards-catalog="rewardsCatalog"
            @changeUserRewards="changeUserRewards"
            @changeCatalogRewards="changeCatalogRewards"
            @updateUserRewards="updateUserRewards"
          )
        .profile-card__sidebar.col-lg-auto.col-12.order-lg-last.order-first
          div(
            class="profile-card__info"
          )
            ProfileInfoHead(
              :current-user="currentUser"
              :expanded.sync="expanded"
              :is-me="isMe"
              :profile-page-showed="profilePageShowed"
              :get-profile-card-hints="getProfileCardHints"
              @updateSubscribing="updateSubscribing"
            )
            .profile-card__info-expandable(
              :style="getStyleExpandable"
            )
              .profile-card__info-expandable-content(ref="expandable")
                .profile-card__info-expandable-data
                  .profile-card__item {{ getAbout }}
                  .profile-card__item
                    .profile-card__item-title {{ $t('profile-card.personal-data') }}
                    .profile-card__item-line
                      | {{ $t('profile-card.date-of-birth', { date: getBorned }) }}
                    .profile-card__item-line
                      | {{ $t('profile-card.city', { city: getCity }) }}
                  .profile-card__item(v-if="hasSocials")
                    .profile-card__item-title {{ $t('profile-card.social-networks') }}
                    span.profile-card__item-social(
                      v-for="social in currentUser.social_networks"
                      :key="social.id + social.identificator"
                    ) {{ social.name }}
                  .profile-card__item(v-if="hasPriorityCategories")
                    .profile-card__item-title {{ $t('profile-card.priority-categories') }}
                    Tag(
                      v-for="category in currentUser.priority_categories"
                      :key="category.id"
                    ) {{ category.name }}
                  NuxtLink(v-if="isMe" class="profile-card__edit-profile" :to="localePath('settings-personal')")
                    SettingsSVG
                    | {{ $t('profile-card.edit-profile') }}
                .profile-card__rewards(v-if="!isRewards")
                  NuxtLink(
                    :to="localePath({ name: 'id-index-user-rewards', params: { id: getUserIdParam } })"
                    class="profile-card__rewards-title"
                  ) {{ $t('profile-rewards.title') }}
                    .profile-card__rewards-title-count {{ getUserRewardsCount }}
                    .profile-card__rewards-title-arrow
                      ArrowSVG
                  .profile-card__rewards-content(v-if="!isUserRewardsEmpty")
                    .profile-card__rewards-content__item-wrapper(
                      v-for="(reward, i) in getUserRewardsItems"
                      :key="profileCardShortRewards(reward, i)"
                      @click="showRewardModal(reward.id)"
                    )
                      .profile-card__rewards-content__item
                        .profile-card__rewards-content__item__img
                          .profile-card__rewards-content__item__img__handler(
                            v-if="isAchievedReward(reward.achieved_at)"
                          )
                            RewardIconSVG(
                              v-if="!isRewardIconExists(reward.icon)"
                              class="profile-card__rewards-content__item__img-icon"
                            )
                            .profile-card__rewards-content__item__img__different-icon(
                              v-else
                              :style="getRewardIcon(reward.icon)"
                            )
                          LockRewardIconSVG(
                            v-else
                            class="profile-card__rewards-content__item__img-icon"
                          )
                        .profile-card__rewards-content__item__title {{ reward.name }}
                  .profile-card__rewards-content-empty(v-else) {{ $t('profile-rewards.empty') }}
                .profile-card__subscribers(v-if="hasSubscribers")
                  ToolTip(
                    v-show="showHintsForMe"
                    :content="getProfileCardHints[3]"
                    :hint-number="0"
                    class="tool-tip__mark-container-header tool-tip__mark-container-profile-subscribers"
                  )
                  .profile-card__subscribers-title(
                    @click="showSubscribers('left')"
                    :class="profileSubsClasses"
                  ) {{ $t('profile-card.subscribers.title') }}
                    .profile-card__subscribers-title-count {{ currentUser.subscribers_count }}
                    .profile-card__subscribers-title-arrow
                      ArrowSVG
                  .profile-card__subscribers-content
                    .profile-card__subscribers-peoples(v-if="getSubscribers.length")
                      .profile-card__subscribers-people(
                        v-for="subscriber in getSubscribers"
                        :key="subscriber.id"
                      )
                        NuxtLink(
                          class="profile-card__subscribers-people-avatar"
                          :to="localePath({ name: 'id-index-achievements', params: { id: subscriber.id } })"
                          :style="{ backgroundImage: `url(${subscriber.avatar_url})` }"
                        )
                        .profile-card__subscribers-people-name {{ subscriber.firstname }}

                    .profile-card__subscribers-placeholder(v-else)
                      .profile-card__subscribers-placeholder-avatar
                      .profile-card__subscribers-placeholder-label
                        | {{ $t(`profile-card.subscribers.empty-${isMe ? 'me' : 'other'}`) }}
                        span(v-if="isMe")
                          br
                          NuxtLink(
                            class="profile-card__subscribers-placeholder-link-search"
                            :to="localePath({ name: 'search' })"
                          ) {{ $t('profile-card.subscribers.go-to-search') }}

                .profile-card__followers(v-if="isMe")
                  ToolTip(
                    v-show="!profilePageShowed"
                    :content="getProfileCardHints[2]"
                    :hint-number="1"
                    class="tool-tip__mark-container-header tool-tip__mark-container-profile-followers"
                  )
                  .profile-card__followers-title(
                    @click="showSubscribers('right')"
                    :class="profileFollowersClasses"
                  ) {{ $t('profile-card.followers.title') }}
                    .profile-card__followers-title-count {{ currentUser.followers_count }}
                    .profile-card__followers-title-arrow
                      ArrowSVG
                  .profile-card__followers-content
                    .profile-card__followers-peoples(v-if="getFollowers.length")
                      .profile-card__followers-people(
                        v-for="follower in getFollowers"
                        :key="follower.id"
                      )
                        NuxtLink(
                          class="profile-card__followers-people-avatar"
                          :to="localePath({ name: 'id-index-achievements', params: { id: follower.id } })"
                          :style="{ backgroundImage: `url(${follower.avatar_url})` }"
                        )
                        .profile-card__followers-people-name {{ follower.firstname }}
                    .profile-card__followers-placeholder(v-else)
                      .profile-card__followers-placeholder-avatar
                      .profile-card__followers-placeholder-label
                        | {{ $t('profile-card.followers.empty-me') }}
                        br
                        NuxtLink(
                          class="profile-card__followers-placeholder-link-search"
                          :to="localePath({ name: 'search' })"
                        ) {{ $t('profile-card.followers.go-to-search') }}
</template>

<script>
import moment from 'moment'
import ProfileInfoHead from './ProfileInfoHead/ProfileInfoHead.vue'
import Tag from '../General/Tag/Tag.vue'
import SettingsSVG from '../SVG/SettingsSVG.vue'
import ArrowSVG from '../SVG/ArrowSVG.vue'
import RewardIconSVG from '../SVG/RewardIconSVG.vue'
import LockRewardIconSVG from '../SVG/LockRewardIconSVG.vue'
import SubscribersModal from '../Modals/SubscribersModal/SubscribersModal.vue'
import PageLayout from '../General/PageLayout/PageLayout.vue'
import ProfileWall from './ProfileWall/ProfileWall.vue'
import CategoryStats from './CategoryStats/CategoryStats.vue'
import ToolTip from '../General/ToolTip/ToolTip.vue'
import ProfileRewards from './ProfileRewards/ProfileRewards.vue'
import { getRewardAchievements, getRewardCard } from '../../plugins/api/rewards'
import ProfileRewardsModal from '../Modals/ProfileRewardsModal/ProfileRewardsModal.vue'
import ProfileRewardsCatalog from './ProfileRewards/ProfileRewardsCatalog/ProfileRewardsCatalog.vue'

export default {
  inject: ['setModal'],
  provide() {
    return {
      currentUser: this.currentUser,
      changeLikes: this.changeLikes,
      changePoints: this.changePoints,
      showGeneralHints: this.showGeneralHints,
      getProfileCardHints: this.getProfileCardHints,
    }
  },
  components: {
    ProfileRewardsCatalog,
    ToolTip,
    PageLayout,
    ProfileInfoHead,
    Tag,
    SettingsSVG,
    ArrowSVG,
    RewardIconSVG,
    LockRewardIconSVG,
    ProfileWall,
    CategoryStats,
    ProfileRewards,
  },
  props: {
    isMe: {
      required: true,
      type: Boolean,
    },
    isRewards: {
      type: Boolean,
      default: false,
    },
    currentUser: {
      required: true,
      type: Object,
    },
    paginateSubscribers: {
      required: true,
      type: Object,
    },
    paginateFollowers: {
      required: true,
      type: Object,
    },
    categories: {
      required: true,
      type: Array,
    },
    userRewards: {
      required: true,
      type: [Object, Array],
    },
    rewardsCatalog: {
      required: true,
      type: Object,
    },
    board: {
      type: Array,
      default: null,
    },
    profilePageShowed: {
      type: Boolean,
      default: null,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      expandableHeight: 0,
      expanded: true,
    }
  },
  computed: {
    isUserRewards() {
      return this.$route.fullPath.includes('user-rewards')
    },
    getCity() {
      return this.currentUser.city ? this.currentUser.city.name : this.$t('profile-card.city-empty')
    },
    getBorned() {
      if (!this.currentUser.borned_at) {
        return this.$t('profile-card.date-of-birth-empty')
      }
      return moment(this.currentUser.borned_at)
        .locale(this.$i18n.locale)
        .format('D MMMM Y')
    },
    getSubscribers() {
      return this.currentUser.subscribers
    },
    getFollowers() {
      return this.currentUser.followers
    },
    getScreenWidth() {
      return this.$store.state.screenWidth
    },
    getExpandable() {
      return this.$refs.expandable
    },
    getStyleExpandable() {
      if (this.getScreenWidth >= 576) {
        return {
          maxHeight: 'none',
          height: 'auto !important',
        }
      }
      return {
        maxHeight: this.expanded ? `${this.expandableHeight}px` : 0,
      }
    },
    getAbout() {
      return this.currentUser.about || this.$t('profile-card.about-empty')
    },
    hasSubscribers() {
      return this.currentUser.subscribers
    },
    hasSocials() {
      return this.currentUser.social_networks && this.currentUser.social_networks.length
    },
    hasPriorityCategories() {
      return this.currentUser.priority_categories && this.currentUser.priority_categories.length
    },
    profileFollowersClasses() {
      return { 'tool-active': this.getNumberOfHint === 1 && this.isMe }
    },
    profileSubsClasses() {
      return { 'tool-active': this.getNumberOfHint === 0 && this.isMe }
    },
    showHintsForMe() {
      return !this.profilePageShowed && this.isMe
    },
    showGeneralHints() {
      return !this.profilePageShowed
    },
    getProfileCardHints() {
      return this.$t('profile-card.hints')
    },
    getNumberOfHint() {
      return this.$store.state.numberOfHint
    },
    getUserRewardsItems() {
      return this.userRewards.items ? this.userRewards.items : []
    },
    getUserRewardsCount() {
      return this.userRewards.items ? this.userRewards.meta.total : ''
    },
    isUserRewardsEmpty() {
      return this.getUserRewardsCount === 0 || !this.userRewards.items
    },
    getUserIdParam() {
      return this.$route.params.id
    },
  },
  watch: {
    currentUser() {
      this.$nextTick(() => {
        this.expandableHeight = this.getExpandable.scrollHeight
      })
    },
    getScreenWidth() {
      this.expandableHeight = this.getExpandable.scrollHeight
    },
  },
  mounted() {
    this.$nextTick(() => {
      this.expandableHeight = this.getExpandable.scrollHeight
    })
  },
  methods: {
    styleExpandable() {
      if (this.getScreenWidth >= 576) {
        return {
          maxHeight: 'none',
          height: 'auto !important',
        }
      }
      return {
        maxHeight: this.expanded ? `${this.expandableHeight}px` : 0,
      }
    },
    showSubscribers(preSelectedTab) {
      this.setModal({
        component: SubscribersModal,
        props: {
          preSelectedTab,
          isMe: this.isMe,
          paginateSubscribers: this.paginateSubscribers,
          paginateFollowers: this.paginateFollowers,
          currentUser: this.currentUser,
        },
        events: {
          close: this.setModal,
        },
      })
    },
    updateSubscribing(e) {
      this.$emit('updateSubscribing', e)
    },
    changeLikes(increment) {
      this.currentUser.likes_count += increment
    },
    changePoints(increment) {
      this.currentUser.points = Number(this.currentUser.points) + increment
    },
    updateBoard(board) {
      this.$emit('update:board', board)
    },
    profileCardShortRewards(item, key) {
      return `${item.name}${key}-profile-card-short-records-item`
    },
    isAchievedReward(achievedAt) {
      return achievedAt !== null
    },
    isRewardIconExists(icon) {
      if (icon) {
        return icon.length > 0
      }
      return false
    },
    getRewardIcon(icon) {
      return `background-image: url('${icon[0].url}')`
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
            close: this.setModal,
            updateUserRewards: this.updateUserRewards,
          },
        })
      }
    },
    updateUserRewards() {
      this.$emit('updateUserRewards')
    },
    changeUserRewards(userRewards) {
      this.$emit('changeUserRewards', userRewards)
    },
    changeCatalogRewards(catalogRewards) {
      this.$emit('changeCatalogRewards', catalogRewards)
    },
  },
}
</script>

<style lang="stylus">
@import "profile-card.styl"
</style>
