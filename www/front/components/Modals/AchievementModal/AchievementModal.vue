<template lang="pug">
  AdjacentModalContainer(@close="close")
    .achievement-modal__close(@click="close")
      ModalCloseSVG
    .achievement-modal__container
      .achievement-modal__back-to-profile
        MobileBackTo(
          @click.native="close"
        ) {{ $t('achievement-modal.back-to') }}
      .achievement-modal(v-if="!isSuccess")
        .achievement-modal__header
          DoubleTabs(
            :tab="currentTab"
          )
            template(slot="left")
              ToolTip(
                v-if="!modalAchievementShowed"
                :content="getAchievementModalHints[0]"
                :hint-number="-1"
                class="tool-tip__mark-container-header tool-tip__mark-container-achievement-tab"
              )
              .achievement-modal__tab(
                @click="changeTab('left')"
              ) {{ $t('achievement-modal.tabs.left') }}
            template(slot="right")
              ToolTip(
                v-if="!modalAchievementShowed"
                :content="getAchievementModalHints[1]"
                :hint-number="0"
                class="tool-tip__mark-container-header tool-tip__mark-container-goal-tab"
              )
              .achievement-modal__tab(
                @click="changeTab('right')"
              ) {{ $t('achievement-modal.tabs.right') }}
        .achievement-modal__body
          KeepAlive
            component(:is="getCurrentForm.component" v-on="getCurrentForm.events")
      SuccessMediaModal(
        v-else
        @submitSuccess="submitSuccess"
      )
</template>

<script>
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import DoubleTabs from '../../General/DoubleTabs/DoubleTabs.vue'
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'
import MakeAchievementForm from './MakeAchievementForm/MakeAchievementForm.vue'
import MakeGoalForm from './MakeGoalForm/MakeGoalForm.vue'
import ProgressDifficulties from '../../../assets/js/constatns/progress-diffculties'
import ToolTip from '../../General/ToolTip/ToolTip.vue'
import SuccessMediaModal from '../SuccessMediaModal/SuccessMediaModal.vue'
import { userSendHint } from '../../../plugins/api/user'
import { getUserByToken } from '../../../plugins/api/auth'

export default {
  components: {
    AdjacentModalContainer,
    DoubleTabs,
    MobileBackTo,
    ModalCloseSVG,
    ToolTip,
    SuccessMediaModal
  },
  data() {
    this.$t.bind(this)
    return {
      message: '',
      isSuccess: false,
      oldHintValue: 0,
      currentTab: 'left',
      forms: {
        left: {
          transition: 'transition-tab-left',
          component: MakeAchievementForm,
          events: {
            submit: this.submitAchievementMake,
          },
        },
        right: {
          transition: 'transition-tab-right',
          component: MakeGoalForm,
          events: {
            submit: this.submitGoalsMake,
          },
        },
      },
    }
  },
  computed: {
    getCurrentForm() {
      return this.forms[this.currentTab]
    },
    getAchievementModalHints() {
      return this.$t('achievement-modal.hints')
    },
    checkPageShowed() {
      if (this.userHints) {
        return this.userHints.includes('achievement-modal') || this.userHints.includes('deny-all')
      }
      return false
    },
    modalAchievementShowed() {
      return this.checkPageShowed !== false
    },
    getNumberOfHint: {
      get() {
        return this.$store.state.numberOfHint
      },
      set(val) {
        this.$store.commit('setNumberOfHint', val)
      },
    },
    userHints: {
      get() {
        return this.$store.getters['auth/hints']
      },
      set(val) {
        this.$store.commit('auth/setHints', val)
      },
    },
  },
  created() {
    this.oldHintValue = this.getNumberOfHint
    this.getNumberOfHint = -1
  },
  methods: {
    async submitAchievementMake({ progressId, difficultyId, filesCount }) {
      await this.$store.dispatch('auth/updateAchievements')
      if (progressId !== null && difficultyId === ProgressDifficulties.Simple) {
        if (filesCount !== 0) {
          this.submit(this.$t('messages.achievement-success-create'))
        } else {
          this.message = this.$t('messages.achievement-success-create')
          this.submitSuccess()
        }
      } else if (filesCount !== 0) {
        this.submit(this.$t('messages.achievement-go-to-moderate'))
      } else {
        this.message = this.$t('messages.achievement-go-to-moderate')
        this.submitSuccess()
      }
    },
    async submitGoalsMake() {
      await this.$store.dispatch('auth/updateGoals')
      this.message = this.$t('messages.goal-success-create')
      this.submitSuccess()
    },
    submitSuccess() {
      this.$emit('submit', this.message)
    },
    submit(message) {
      this.isSuccess = true
      this.message = message
    },
    async close() {
      if (!this.modalAchievementShowed) {
        await userSendHint({
          hints: ['achievement-modal'],
        })
        const res = await getUserByToken()
        this.userHints = res.data.viewed_hints
      }
      this.getNumberOfHint = this.oldHintValue
      if (this.message !== '') this.$emit('submit', this.message)
      this.$emit('close')
    },
    changeTab(tab) {
      this.currentTab = tab
    },
  },
}
</script>

<style lang="stylus">
@import "achievement-modal.styl"
</style>
