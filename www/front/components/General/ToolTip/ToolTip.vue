<template lang="pug">
  .tool-tip__mark-container(:class="getClassesHint")
    QuestionMark.tool-tip__mark(@click.native="toggleMarkText" v-if="showMark")
    .tool-tip__mark-handler(v-show="initialised")
      .tool-tip__mark-triangle
      .tool-tip__mark-text
        .tool-tip__mark-info
          slot(name="content")
            span.tool-tip__mark-span(v-html="content")
        .tool-tip__mark-controls(:class="alignButton")
          .tool-tip__mark-refuse(
            @click="refuseHints"
            v-show="showRefuseButton"
          ) {{ $t('tool-tip.refuse') }}
          .tool-tip__mark-accept(
            @click="toggleMarkText"
          ) {{ $t('tool-tip.accept') }}
</template>

<script>
import QuestionMark from '../../SVG/QuestionMark.vue'
import { userSendHint } from '../../../plugins/api/user'
import { getUserByToken } from '../../../plugins/api/auth'
import HintsKeywords from '../../../assets/js/constatns/hints-keywords'

export default {
  components: {
    QuestionMark,
  },
  props: {
    content: {
      type: String,
      default: null,
    },
    hintNumber: {
      type: Number,
      default: null,
    },
    showMark: {
      type: Boolean,
      default: true,
    },
    showRefuseButton: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      markTextActive: false,
      initialised: false,
    }
  },
  computed: {
    getNumberOfHint() {
      return this.$store.state.numberOfHint
    },
    getClassesHint() {
      return {
        'tool-active': this.hintNumber === this.getNumberOfHint,
        'tool-toggle-button': this.markTextActive,
      }
    },
    alignButton() {
      return { 'justify-content-end': !this.showRefuseButton }
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
  mounted() {
    this.$nextTick(() => {
      this.initialised = true
    })
  },
  methods: {
    toggleMarkText() {
      if (this.hintNumber === null) {
        this.markTextActive = !this.markTextActive
      } else {
        this.$store.commit('increaseNumberOfHint')
      }
    },
    async refuseHints() {
      if (!this.userHints.includes('deny-all')) {
        await userSendHint({
          hints: [HintsKeywords['deny-all']],
        })
        const res = await getUserByToken()
        this.userHints = res.data.viewed_hints
      }
      this.markTextActive = !this.markTextActive
    },
  },
}
</script>

<style lang="stylus">
@import "tool-tip.styl"
</style>
