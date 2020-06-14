<template lang="pug">
  .min-card-user
    .min-card-user__avatar(:style="{ 'background-image': `url(${currentUser.avatar_url})` }")
    .min-card-user__content
      .min-card-user__content-info
        .min-card-user__content-name {{ currentUser.full_name }}
        .min-card-user__content-points
          .min-card-user__content-line(v-if="showRating")
            .min-card-user__content-star
              StarMinUserCard
            | {{ Number(currentUser.points) }}
          .min-card-user__content-line(v-if="showLikes")
            .min-card-user__content-like
              LikesSVG
            | {{ Number(currentUser.likes) }}
    slot(name="after")
      .min-card-user__arrow(v-if="withArrow")
        ArrowSVG

</template>

<script>
import StarMinUserCard from '../SVG/StarMinUserCardSVG.vue'
import ArrowSVG from '../SVG/ArrowSVG.vue'
import LikesSVG from '../SVG/LikesSVG.vue'

export default {
  components: {
    StarMinUserCard,
    ArrowSVG,
    LikesSVG,
  },
  props: {
    currentUser: {
      required: true,
      type: Object,
    },
    withArrow: {
      type: Boolean,
      default: true,
    },
    showRating: {
      type: Boolean,
      default: true,
    },
    showLikes: {
      type: Boolean,
      default: false,
    },
  },
}
</script>

<style lang="stylus">
@import "min-card-user.styl"
</style>
