<template lang="pug">
  .profile-rewards-catalog
    .profile-rewards-catalog__tabs
      DoubleTabs(:tab="tab")
        template(slot="left")
          .profile-rewards-catalog__tabs__tab(
            @click="changeTab('left')"
          ) {{ $t('profile-rewards.tabs.left') }}
        template(slot="right")
          .profile-rewards-catalog__tabs__tab(
            @click="changeTab('right')"
          ) {{ $t('profile-rewards.tabs.right') }}
    .profile-rewards-catalog__content
      Transition(:name="transitionName")
        ProfileRewards(
          key="user-rewards"
          v-if="isShowLeftTab"
          :show-header="false"
          :rewards="userRewards"
          @changeRewards="changeMyRewards"
          @updateUserRewards="updateUserRewards"
        )
        ProfileRewards(
          key="rewards-catalog"
          v-if="isShowRightTab"
          :show-header="false"
          :rewards="rewardsCatalog"
          :is-catalog="true"
          @changeRewards="changeCatalogRewards"
          @updateUserRewards="updateUserRewards"
        )
</template>

<script>
import DoubleTabs from '../../../General/DoubleTabs/DoubleTabs.vue'
import ProfileRewardCard from '../ProfileRewardCard/ProfileRewardCard.vue'
import ProfileRewards from '../ProfileRewards.vue'

export default {
  components: {
    DoubleTabs,
    ProfileRewardCard,
    ProfileRewards,
  },
  props: {
    userRewards: {
      type: [Object, Array],
      required: true,
    },
    rewardsCatalog: {
      type: [Object, Array],
      required: true,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      tab: 'right',
    }
  },
  computed: {
    transitionName() {
      return `transition-tab-${this.tab}`
    },
    isShowLeftTab() {
      return this.tab === 'left'
    },
    isShowRightTab() {
      return this.tab === 'right'
    },
  },
  methods: {
    updateUserRewards() {
      this.$emit('updateUserRewards')
    },
    changeTab(tab) {
      this.tab = tab
    },
    changeMyRewards(userRewards) {
      this.$emit('changeUserRewards', userRewards)
    },
    changeCatalogRewards(catalogRewards) {
      this.$emit('changeCatalogRewards', catalogRewards)
    },
  },
}
</script>

<style lang="stylus">
@import "profile-rewards-catalog.styl"
</style>
