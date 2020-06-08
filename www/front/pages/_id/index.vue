<template lang="pug">
  ProfileCard(
    :is-me="isMe"
    :is-rewards="isRewardsPage"
    :current-user="currentUser"
    :paginate-subscribers="subscribers"
    :paginate-followers="followers"
    :categories="categories"
    :user-rewards="userRewards"
    :rewards-catalog="rewardsCatalog"
    :board.sync="board"
    :profile-page-showed="profilePageShowed"
    @changeUserRewards="changeUserRewards"
    @changeCatalogRewards="changeCatalogRewards"
    @updateUserRewards="updateUserRewards"
    @updateSubscribing="updateSubscribing"
    @refreshUser="updateCurrentUser"
  )
</template>

<script>
import ProfileCard from '../../components/ProfileCard/ProfileCard.vue'
import {
  getUserCategories,
  getUserFollowers,
  getUserProfile,
  getUserRewards,
  getUserSubscribers,
  userLeaderBoard,
  userSendHint,
} from '../../plugins/api/user'
import { getUserByToken } from '../../plugins/api/auth'
import { getRewards } from '../../plugins/api/rewards'
import HintsKeywords from '../../assets/js/constatns/hints-keywords'

export default {
  middleware: 'authenticated',
  components: {
    ProfileCard,
  },
  computed: {
    isRewardsPage() {
      return this.$route.fullPath.includes('rewards')
    },
    userHints: {
      get() {
        return this.$store.getters['auth/hints']
      },
      set(val) {
        this.$store.commit('auth/setHints', val)
      },
    },
    getNumberOfHint: {
      get() {
        return this.$store.state.numberOfHint
      },
      set(val) {
        this.$store.commit('setNumberOfHint', val)
      },
    },
    checkPageShowed() {
      if (this.userHints) {
        return (
          (this.isMe
            ? this.userHints.includes(HintsKeywords['my-profile-page'])
            : this.userHints.includes(HintsKeywords['profile-page'])) ||
          this.userHints.includes(HintsKeywords['deny-all'])
        )
      }
      return false
    },
    profilePageShowed() {
      return this.checkPageShowed !== false
    },
  },
  async asyncData({ params, redirect, store, route }) {
    const { id } = params
    const [
      currentUser,
      subscribers,
      followers,
      categories,
      userRewards,
      rewardsCatalog,
    ] = await Promise.all([
      getUserProfile(id),
      getUserSubscribers(id, { page: 1, records: 15 }),
      getUserFollowers(id, { page: 1, records: 15 }),
      getUserCategories(id),
      getUserRewards(id, { page: 1, records: 6 }),
      getRewards({ page: 1, records: 6 }),
    ])
    if (currentUser.status === 404) {
      return redirect('/')
    }
    currentUser.data.id = +id
    const isMe = currentUser.data.id === store.state.auth.user.id
    const meResult = {
      board: null,
    }
    if (isMe) {
      const [board] = await Promise.all([userLeaderBoard()])
      meResult.board = board.data
    }
    const isRewardsPage = route.fullPath.includes('rewards')
    if (!isRewardsPage && userRewards.data.items) {
      userRewards.data.items = userRewards.data.items.splice(0, 2)
    }
    return {
      isMe,
      currentUser: currentUser.data,
      subscribers: subscribers.data,
      followers: followers.data,
      categories: categories.data,
      userRewards: userRewards.data,
      rewardsCatalog: rewardsCatalog.data,
      ...meResult,
    }
  },
  beforeDestroy() {
    window.removeEventListener('beforeunload', this.closePage)
  },
  async beforeRouteLeave(to, from, next) {
    await this.closePage()
    next()
  },
  async beforeRouteUpdate(to, from, next) {
    const { id } = this.$route.params
    const [userRewards, rewardsCatalog] = await Promise.all([
      getUserRewards(id, { page: 1, records: 6 }),
      getRewards({ page: 1, records: 6 }),
    ])
    if (userRewards.status && rewardsCatalog.status) {
      const isRewardsPage = to.name.includes('rewards')
      if (!isRewardsPage && userRewards.data.items) {
        userRewards.data.items = userRewards.data.items.splice(0, 2)
      }
      this.changeUserRewards(userRewards.data)
      this.changeCatalogRewards(rewardsCatalog.data)
    }
    await this.closePage()
    next()
  },
  async mounted() {
    window.addEventListener('beforeunload', this.closePage)
  },
  methods: {
    async updateSubscribing(e) {
      await Promise.all([
        this.updateCurrentUser(),
        this.updateSubscribers(),
        this.updateFollowers(),
      ])
      this.$nextTick(() => {
        e.callback()
      })
    },
    async updateUserRewards() {
      const updatedUserRewards = await getUserRewards(this.$route.params.id, {
        page: 1,
        records: 6,
      })
      if (updatedUserRewards.status) {
        this.userRewards = updatedUserRewards.data
      }
    },
    changeUserRewards(rewards) {
      this.userRewards = rewards
    },
    changeCatalogRewards(catalogRewards) {
      this.rewardsCatalog = catalogRewards
    },
    async updateCurrentUser() {
      const currentUser = await getUserProfile(this.currentUser.id)
      currentUser.data.id = this.currentUser.id
      this.currentUser = currentUser.data
    },
    async updateSubscribers() {
      const subscribers = await getUserSubscribers(this.currentUser.id, { page: 1, records: 15 })
      this.subscribers = subscribers.data
    },
    async updateFollowers() {
      const followers = await getUserFollowers(this.currentUser.id, { page: 1, records: 15 })
      this.followers = followers.data
    },
    async closePage() {
      if (!this.profilePageShowed) {
        await userSendHint({
          hints: this.isMe ? [HintsKeywords['my-profile-page']] : [HintsKeywords['profile-page']],
        })
        const userData = await getUserByToken()
        if (userData.status) {
          this.userHints = userData.data.viewed_hints
        }
        this.getNumberOfHint = -1
      }
    },
  },
}
</script>
