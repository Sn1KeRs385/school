<template lang="pug">
  AchievementsPage(
    :hints-info="hintsInfo"
  )
</template>

<script>
import AchievementsPage from '../components/AchievementsPage/AchievementsPage.vue'
import { userSendHint } from '../plugins/api/user'
import HintsKeywords from '../assets/js/constatns/hints-keywords'
import { getUserByToken } from '../plugins/api/auth'

export default {
  middleware: 'authenticated',
  components: {
    AchievementsPage,
  },
  data() {
    this.$t.bind(this)
    return {}
  },
  computed: {
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
          this.userHints.includes(HintsKeywords['catalog-page']) ||
          this.userHints.includes(HintsKeywords['deny-all'])
        )
      }
      return false
    },
    catalogPageShowed() {
      return this.checkPageShowed !== false
    },
    getAchievementsHints() {
      return this.$t('achievements.hints')
    },
    hintsInfo() {
      return {
        getAchievementsHints: this.getAchievementsHints,
        catalogPageShowed: this.catalogPageShowed,
      }
    },
  },
  beforeDestroy() {
    window.removeEventListener('beforeunload', this.closePage)
  },
  async beforeRouteLeave(to, from, next) {
    await this.closePage()
    next()
  },
  async beforeRouteUpdate(to, from, next) {
    await this.closePage()
    next()
  },
  async mounted() {
    window.addEventListener('beforeunload', this.closePage)
  },
  methods: {
    async closePage() {
      if (!this.catalogPageShowed) {
        await userSendHint({
          hints: [HintsKeywords['catalog-page']],
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
