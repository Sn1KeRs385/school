<template lang="pug">
  .category-stats__container(v-if="categories.length")
    .category-stats
      .category-stats__grid
        .category-stats__link(
          v-for="category in categories"
          :key="category.id"
        )
          .category-stats__link-text
            span.category-stats__link-name {{ category.name }}
            ToolTip(
              :content="$t(`profile-card.categories-hints.${category.keyword}`)"
              :show-refuse-button="false"
            )
          .category-stats__link-content(
            @click="showStatsModal(category)"
          )
            img(:src="category.icon")
            div.category-stats__link-count {{ category.points }}
          .category-stats__link-rank
            span.category-stats__link-rank__text(
              v-if="isRankExist(category.rank)"
            ) {{ category.rank.name }}
        ProfileRating(
          v-if="board"
          :board="board"
          @update:board="updateBoard"
        )

</template>

<script>
import UserStatsCategoryModal from '../../Modals/UserStatsCategoryModal/UserStatsCategoryModal.vue'
import ProfileRating from '../ProfileRating/ProfileRating.vue'
import ToolTip from '../../General/ToolTip/ToolTip.vue'

export default {
  inject: ['setModal'],
  components: {
    ProfileRating,
    ToolTip,
  },
  props: {
    currentUser: {
      required: true,
      type: Object,
    },
    categories: {
      required: true,
      type: Array,
    },
    board: {
      type: Array,
      default: null,
    },
  },
  data() {
    this.$t.bind(this)
    return {}
  },
  methods: {
    isRankExist(rank) {
      return rank !== null
    },
    showStatsModal(category) {
      this.setModal({
        component: UserStatsCategoryModal,
        props: {
          category,
          currentUser: this.currentUser,
        },
        events: {
          close: this.setModal,
        },
      })
    },
    updateBoard(board) {
      this.$emit('update:board', board)
    },
  },
}
</script>

<style lang="stylus">
@import "category-stats.styl"
</style>
