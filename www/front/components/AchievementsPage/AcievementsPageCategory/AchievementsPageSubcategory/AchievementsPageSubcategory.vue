<template lang="pug">
  .achievements-page-subcategory
    ToolTip(
      v-if="showHintForExpanded"
      :content="hintContent"
      :hint-number="0"
      class="tool-tip__mark-container-header tool-tip__mark-container-achievement-category"
    )
    .achievements-page-subcategory__header(@click="updateExpanded(!expanded)")
      .achievements-page-subcategory__name {{ subcategory.name }}
      .achievements-page-subcategory__stats {{ subcategory.completed_points }}/{{ subcategory.sum_points }}
      .achievements-page-subcategory__arrow(:class="getArrowClasses")
        ArrowSVG
    .achievements-page-subcategory__body(:class="getBodyClasses" :style="getBodyStyle")
      .achievements-page-subcategory__expandable(ref="expandable")
        .achievements-page-subcategory__achievements
          .achievements-page-subcategory__elem(
            v-for="achievement in getAchievements.items"
            :key="achievement.id"
          )
            AchievementsPageAchievement(:achievement="achievement")
        .achievements-page-subcategory__footer
          .achievements-page-subcategory__load-more(
            v-if="getAchievements.meta.next_exists"
            :class="getLoadMoreClasses"
            @click="loadMore"
          ) {{ $t('achievements.subcategory.show-more') }}
          .achievements-page-subcategory__loader(v-if="loadingMore")
            LoaderRing(size="25px" weight="2px")
          .achievements-page-subcategory__counter {{ $t('achievements.subcategory.from', { count: getAchievements.items.length, total: getAchievements.meta.total }) }}

</template>

<script>
import ArrowSVG from '../../../SVG/ArrowSVG.vue'
import AchievementsPageAchievement from '../AchievementsPageAchievement/AchievementsPageAchievement.vue'
import { getProgressAchievements } from '../../../../plugins/api/progress'
import LoaderRing from '../../../General/LoaderRing.vue'
import ToolTip from '../../../General/ToolTip/ToolTip.vue'

export default {
  components: {
    ArrowSVG,
    AchievementsPageAchievement,
    LoaderRing,
    ToolTip,
  },
  props: {
    categoryId: {
      required: true,
      type: Number,
    },
    subcategory: {
      required: true,
      type: Object,
    },
    expanded: {
      default: false,
      type: Boolean,
    },
    showHint: {
      default: null,
      type: Boolean,
    },
    hintContent: {
      default: null,
      type: String,
    },
  },
  data() {
    return {
      bodyExpandable: false,
      expandableHeight: 0,
      loadingMore: false,
    }
  },
  computed: {
    screenWidth() {
      return this.$store.state.screenWidth
    },
    getExpandable() {
      return this.$refs.expandable
    },
    getArrowClasses() {
      return {
        'achievements-page-subcategory__arrow--expanded': this.expanded,
      }
    },
    getAchievements: {
      get() {
        return this.subcategory.progresses
      },
      set(paginate) {
        this.subcategory.progresses = paginate
      },
    },
    getLoadMoreClasses() {
      return {
        'achievements-page-subcategory__load-more--loading': this.loadingMore,
      }
    },
    getBodyClasses() {
      return {
        'achievements-page-subcategory__body--expandable': this.bodyExpandable,
      }
    },
    getBodyStyle() {
      return {
        maxHeight: this.expanded ? `${this.expandableHeight}px` : 0,
      }
    },
    showHintForExpanded() {
      return this.showHint && this.expanded
    },
  },
  watch: {
    screenWidth() {
      this.updateExpandableHeight()
    },
  },
  mounted() {
    this.updateExpandableHeight()
    setTimeout(() => {
      this.bodyExpandable = true
    }, 150)
  },
  beforeDestroy() {
    this.bodyExpandable = false
  },
  methods: {
    updateExpandableHeight() {
      this.expandableHeight = this.getExpandable.scrollHeight
    },
    async loadingAchievements(paginate) {
      const { data } = await getProgressAchievements({
        category_id: this.categoryId,
        subcategory_id: this.subcategory.id,
        page: paginate.meta.page + 1,
        records: paginate.meta.records,
      })
      data.items = [...paginate.items, ...data.items]
      return data
    },
    async loadMore() {
      if (this.loadingMore) return
      this.loadingMore = true
      this.getAchievements = await this.loadingAchievements(this.getAchievements)
      this.$nextTick(this.updateExpandableHeight)
      this.loadingMore = false
    },
    updateExpanded(expanded) {
      this.$emit('update:expanded', expanded)
      // this.expanded = expanded
    },
  },
}
</script>

<style lang="stylus">
@import "achievements-page-subcategory.styl";
</style>
