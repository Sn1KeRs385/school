<template lang="pug">
  Rating(
    :ratingList="ratingList"
    :settings="settings"
    :rating-page-showed="ratingPageShowed"
    @changeRatingList="changeRatingList"
  )
</template>

<script>
import Rating from '../../components/Rating/Rating.vue'
import { getRating, getRatingSettings } from '../../plugins/api/rating'
import { userSendHint } from '../../plugins/api/user'
import { getUserByToken } from '../../plugins/api/auth'
import HintsKeywords from '../../assets/js/constatns/hints-keywords'

class RatingSettings {
  constructor(userId, settings = {}) {
    this.user_id = userId
    this.category_id = settings.category_id || null
    this.country_id = settings.country_id || null
    this.city_id = settings.city_id || null
    this.birth_start = settings.birth_start || null
    this.birth_end = settings.birth_end || null
    this.show_followers = settings.show_followers || 0
    this.show_board = settings.show_board || 0
  }
}

export default {
  middleware: 'authenticated',
  components: {
    Rating,
  },
  computed: {
    checkPageShowed() {
      if (this.userHints) {
        return (
          this.userHints.includes(HintsKeywords['rating-page']) ||
          this.userHints.includes(HintsKeywords['deny-all'])
        )
      }
      return false
    },
    ratingPageShowed() {
      return this.checkPageShowed !== false
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
  },
  async asyncData({ store }) {
    const userId = store.state.auth.user.id
    const [ratingList, settings] = await Promise.all([
      getRating({ page: 1, records: 15 }),
      getRatingSettings(),
    ])
    return {
      ratingList: ratingList.data,
      settings: settings.data || new RatingSettings(userId),
    }
  },
  beforeDestroy() {
    window.removeEventListener('beforeunload', this.closePage)
  },
  async beforeRouteLeave(to, from, next) {
    await this.closePage()
    next()
  },
  async mounted() {
    window.addEventListener('beforeunload', this.closePage)
  },
  methods: {
    changeRatingList(paginate) {
      this.ratingList = paginate
    },
    async closePage() {
      if (!this.ratingPageShowed) {
        await userSendHint({
          hints: [HintsKeywords['rating-page']],
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
