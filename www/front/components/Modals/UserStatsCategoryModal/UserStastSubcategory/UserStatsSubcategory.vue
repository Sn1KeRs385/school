<template lang="pug">
  .user-stats-subcategory(:id="id")
    .user-stats-subcategory__header(@click="updateIsOpen")
      .user-stats-subcategory__subcategory-name {{ subcategory.name }}
      .user-stats-subcategory__subcategory-points {{ subcategory.points_subcategory }}
      .user-stats-subcategory__subcategory-arrow(:class="getArrowClasses")
        ArrowSVG
    .user-stats-subcategory__expandable-container(:style="getExpandableContainerStyle")
      .user-stats-subcategory__expandable(ref="expandable")
        .user-stats-subcategory__achievement(
          v-for="achievement in subcategory.progresses.items"
          :key="achievement.id"
        )
          NuxtLink(
            class="user-stats-subcategory__achievement-name"
            :to="linkToAchievement(achievement)"
          ) {{ achievement.title }}
          .user-stats-subcategory__achievement-points {{ achievement.point }}
        .user-stats-subcategory__expandable-footer
          .user-stats-subcategory__expandable-footer-load-more(
            v-if="subcategory.progresses.meta.next_exists"
            :class="getLoadMoreClasses"
            @click="loadMore"
          ) {{ $t('users-stats-category-modal.subcategory.load-more') }}
          .user-stats-subcategory__expandable-footer-loader(v-if="loadingMore")
            LoaderRing(size="20px" weight="2px")
          .user-stats-subcategory__expandable-footer-count {{ countFrom }}

</template>

<script>
import ArrowSVG from '../../../SVG/ArrowSVG.vue'
import LoaderRing from '../../../General/LoaderRing.vue'
import { userStatsLoadSubcategory } from '../../../../plugins/api/user'

export default {
  components: {
    ArrowSVG,
    LoaderRing,
  },
  props: {
    id: {
      required: true,
      type: null,
    },
    currentUser: {
      required: true,
      type: Object,
    },
    category: {
      required: true,
      type: Object,
    },
    subcategory: {
      required: true,
      type: Object,
    },
    container: {
      required: true,
      type: String,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      isOpen: false,
      loadingMore: false,
      expandableHeight: 0,
    }
  },
  computed: {
    screenWidth() {
      return this.$store.state.screenWidth
    },
    getExpandableContainerStyle() {
      if (!this.isOpen) {
        return {
          height: 0,
        }
      }
      return {
        height: `${this.expandableHeight}px`,
      }
    },
    getExpandable() {
      return this.$refs.expandable
    },
    getArrowClasses() {
      return {
        'user-stats-subcategory__subcategory-arrow--open': this.isOpen,
      }
    },
    getLoadMoreClasses() {
      return {
        'user-stats-subcategory__expandable-footer-load-more--disabled': this.loadingMore,
      }
    },
    countFrom() {
      return this.$t('users-stats-category-modal.subcategory.count-from', {
        count: this.subcategory.progresses.items.length,
        total: this.subcategory.progresses.meta.total,
      })
    },
  },
  watch: {
    screenWidth() {
      this.updateExpandableHeight()
    },
    isOpen(isOpen) {
      if (isOpen) {
        this.scrollTo()
      }
    },
  },
  mounted() {
    this.updateExpandableHeight()
  },
  methods: {
    linkToAchievement(achievement) {
      return this.localePath({
        name: 'id-achievement-achievementId',
        params: { id: this.currentUser.id, achievementId: achievement.id },
      })
    },
    scrollTo() {
      if (this.screenWidth <= 767) return
      setTimeout(() => {
        this.$scrollTo(`#${this.id}`, 250, { container: this.container })
      })
    },
    updateExpandableHeight() {
      this.expandableHeight = this.getExpandable.clientHeight
    },
    updateIsOpen() {
      this.isOpen = !this.isOpen
    },
    async loadMore() {
      this.loadingMore = true
      const paginate = this.subcategory.progresses
      const { data } = await userStatsLoadSubcategory(this.currentUser.id, {
        category_id: this.category.id,
        subcategory_id: this.subcategory.id,
        page: paginate.meta.page + 1,
        records: paginate.meta.records,
      })
      data.items = [...paginate.items, ...data.items]
      this.subcategory.progresses = data
      this.loadingMore = false
      this.$nextTick(this.updateExpandableHeight)
    },
  },
}
</script>

<style lang="stylus">
@import "user-stats-subcategory.styl";
</style>
