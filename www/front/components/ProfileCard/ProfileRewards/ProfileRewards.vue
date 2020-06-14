<template lang="pug">
  .profile-rewards
    .container
      .profile-rewards__header(v-if="showHeader")
        .profile-rewards__header__title {{ $t('profile-rewards.title') }}
        hr.profile-rewards__header__divider
      .row.profile-rewards__content(v-if="!isRewardsEmpty")
        ProfileRewardCard(
          v-for="(reward, i) in rewards.items"
          :key="profileRewardsCard(reward, i)"
          :reward="reward"
          class="col-md-6 col-12"
          @updateUserRewards="updateUserRewards"
        )
      .row.profile-rewards__content-empty(v-else) {{ $t('profile-rewards.empty') }}
      .profile-rewards__footer(v-if="nextPageExist")
        Button(
          :secondary="true"
          :loading="loadingMore"
          @click.native="loadMore"
        ) {{ $t('profile-rewards.load-more') }}
          template(slot="before")
            .profile-rewards__footer__arrow-load
              ArrowLoadSVG
</template>

<script>
import ArrowLoadSVG from '../../SVG/ArrowLoadSVG.vue'
import ProfileRewardCard from './ProfileRewardCard/ProfileRewardCard.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import { getUserRewards } from '../../../plugins/api/user'
import { getRewards } from '../../../plugins/api/rewards'

export default {
  scrollToTop: true,
  components: {
    ArrowLoadSVG,
    ProfileRewardCard,
    Button,
  },
  props: {
    rewards: {
      type: [Object, Array],
      required: true,
    },
    showHeader: {
      type: Boolean,
      default: true,
    },
    isCatalog: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      loadingMore: false,
    }
  },
  computed: {
    getScreenWidth() {
      return this.$store.state.screenWidth
    },
    isRewardsItems() {
      return this.rewards.items
    },
    isRewardsEmpty() {
      if (this.isRewardsItems) {
        return this.rewards.meta.total === 0
      }
      return true
    },
    nextPageExist() {
      if (this.isRewardsItems) {
        return this.rewards.meta.current_page !== this.rewards.meta.last_page
      }
      return false
    },
  },
  mounted() {
    if (this.getScreenWidth > 991) {
      this.$scrollTo('#__nuxt', 200)
    }
  },
  methods: {
    updateUserRewards() {
      this.$emit('updateUserRewards')
    },
    async loadMore() {
      this.loadingMore = true
      const paginate = this.rewards
      const { data } = this.isCatalog
        ? await getRewards({
            page: paginate.meta.current_page + 1,
            records: 6,
          })
        : await getUserRewards(this.$route.params.id, {
            page: paginate.meta.current_page + 1,
            records: 6,
          })
      data.items = [...paginate.items, ...data.items]
      this.$emit('changeRewards', data)
      this.loadingMore = false
    },
    profileRewardsCard(item, key) {
      return `${item.name}${key}-profile-rewards-item`
    },
  },
}
</script>

<style lang="stylus">
@import "profile-rewards.styl"
</style>
