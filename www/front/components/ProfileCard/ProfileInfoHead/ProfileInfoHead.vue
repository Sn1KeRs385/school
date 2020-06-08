<template lang="pug">
  .profile-info-head
    ToolTip(
      v-show="showProfileHint"
      :content="getProfileCardHints[1]"
      :hint-number="-1"
      class="tool-tip__mark-container-header tool-tip__mark-container-profile-info"
    )
    .profile-info-head__data
      .profile-info-head__avatar(
        :style="{ 'background-image': `url(${this.currentUser.avatar.url})` }"
      )
      .profile-info-head__info
        .profile-info-head__name {{ currentUser.full_name }}
        .profile-info-head__login {{ currentUser.email }}
        .profile-info-head__stats
          .profile-info-head__points
            StarProfileHeadSVG
            | {{ currentUser.points }}
          .profile-info-head__likes
            LikeProfileHeadSVG
            | {{ currentUser.likes_count }}
        .profile-info-head__expand-trigger(
          :class="{'profile-info-head__expand-trigger--expand': expanded}"
          @click="$emit('update:expanded', !expanded)"
        )
          .profile-info-head__expand-trigger-point
          .profile-info-head__expand-trigger-point
          .profile-info-head__expand-trigger-point
    .profile-info-head__subscribe-report-container(v-if="!isMe")
      .profile-info-head__subscribe-btn
        Button(
          min-height="100%"
          :secondary="!isFollow"
          :loading="loading"
          @click.native="changeSubscribe"
        ) {{ $t(`profile-card.head.submit-${isFollow ? 'subscribe' : 'unsubscribe'}`) }}

</template>

<script>
import LikeProfileHeadSVG from '../../SVG/LikeProfileHeadSVG.vue'
import StarProfileHeadSVG from '../../SVG/StarProfileHeadSVG.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import ToolTip from '../../General/ToolTip/ToolTip.vue'
import { subscribe } from '../../../plugins/api/user'

export default {
  components: {
    LikeProfileHeadSVG,
    StarProfileHeadSVG,
    Button,
    ToolTip,
  },
  props: {
    isMe: {
      required: true,
      type: Boolean,
    },
    currentUser: {
      required: true,
      type: Object,
    },
    expanded: {
      required: true,
      type: Boolean,
    },
    profilePageShowed: {
      type: Boolean,
      default: null,
    },
    getProfileCardHints: {
      type: Array,
      default: null,
    },
  },
  data() {
    return {
      loading: false,
    }
  },
  computed: {
    getUser() {
      return this.$store.state.auth.user
    },
    isFollow() {
      return this.currentUser.is_follow
    },
    getSubscribers() {
      return this.currentUser.subscribers
    },
    showProfileHint() {
      return !this.profilePageShowed && !this.isMe
    },
  },
  methods: {
    async changeSubscribe() {
      if (this.loading) return
      this.loading = true
      const subscribing = await subscribe(this.currentUser.id)
      this.$emit('updateSubscribing', {
        follow: subscribing.data.follow,
        callback: () => {
          this.loading = false
        },
      })
    },
  },
}
</script>

<style lang="stylus">
@import "profile-info-head.styl"
</style>
