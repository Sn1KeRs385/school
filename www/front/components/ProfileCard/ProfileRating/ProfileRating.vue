<template lang="pug">
  .profile-rating
    .profile-rating__header
      NuxtLink(
        class="profile-rating__rating-link"
        :to="localePath({ name: 'rating' })"
      ) {{ $t('profile-card.rating.header') }}
      ToolTip(
        :content="$t('profile-card.rating.hint')"
        :show-refuse-button="false"
        class=" tool-tip__mark-container-mark-rating"
      )
    NuxtLink(
      class="profile-rating__user"
      v-for="user in getExistsBoard"
      :key="user.id"
      :to="localePath({ name: 'id-index-achievements', params: { id: user.id } })"
    )
      MinCardUser(:current-user="user" :with-arrow="false")
        template(#after)
          .profile-rating__rating-count(
            :class="{ 'profile-rating__rating-count--me': user.id === getUser.id }"
          ) {{ user.rnk }}

</template>

<script>
import SortArrowSVG from '../../SVG/SortArrowSVG.vue'
import MinCardUser from '../../MinCardUser/MinCardUser.vue'
import ToolTip from '../../General/ToolTip/ToolTip.vue'

export default {
  components: {
    SortArrowSVG,
    MinCardUser,
    ToolTip,
  },
  props: {
    board: {
      type: Array,
      default: null,
    },
  },
  data() {
    this.$t.bind(this)
    return {}
  },
  computed: {
    getUser() {
      return this.$store.state.auth.user
    },
    getExistsBoard() {
      return this.board.filter(k => !!k)
    },
  },
}
</script>

<style lang="stylus">
@import "profile-rating.styl"
</style>
