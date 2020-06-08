<template lang="pug">
  .achievements-page-list__empty(
    v-if="isEmpty"
  ) {{ $t('achievements.tabs.list-empty') }}
    br
    | :(
  div(v-else)
    .achievements-page-list
      .achievements-page-list__search
        SearchInput(
          id="achievements-page-list-search"
          :placeholder="placeholder"
          :value="title"
          :icon-color="inputColorIcon"
          @changeValue="updateTitle"
        )
      .achievements-page-list__loader(v-if="loading")
        LoaderRing(size="40px")
      div.achievements-page-list__container(v-if="!searchResult")
        AchievementsPageSubcategory(
          v-for="(subcategory, i) in list.items"
          :category-id="categoryId"
          :key="subcategory.id"
          :subcategory="subcategory"
          :expanded.sync="subcategory.expanded"
          :show-hint="showHintCategory(i)"
          :hint-content="categoryToolContent"
        )
        .achievements-page-list__footer(v-if="list.meta.next_exists")
          Button(
            :secondary="true"
            :loading="loadingMore"
            @click.native="loadMore"
          ) {{ $t('achievements.load-more') }}
            template(slot="before")
              .achievements-page-list__arrow-load
                ArrowLoadSVG
      .achievements-page-list__search-result(v-else)
        .achievements-page-fame__no-result(
          v-if="searchResultEmpty && !loading"
        ) {{ $t('achievements.no-result', { title }) }}
        .achievements-page-list__elem(
          v-for="achievement in searchResult"
          :key="achievement.id"
        )
          AchievementsPageAchievement(
            :achievement="achievement"
          )
</template>

<script>
import AchievementsPageSubcategory from '../AchievementsPageSubcategory/AchievementsPageSubcategory.vue'
import Button from '../../../Interactives/Controls/Button/Button.vue'
import ArrowLoadSVG from '../../../SVG/ArrowLoadSVG.vue'
import { getProgressListByUser } from '../../../../plugins/api/progress'
import AchievementsPageAchievement from '../AchievementsPageAchievement/AchievementsPageAchievement.vue'
import LoaderRing from '../../../General/LoaderRing.vue'
import SearchInput from '../../../Interactives/Inputs/SearchInput/SearchInput.vue'

export default {
  components: {
    AchievementsPageSubcategory,
    Button,
    ArrowLoadSVG,
    AchievementsPageAchievement,
    LoaderRing,
    SearchInput,
  },
  props: {
    categoryId: {
      required: true,
      type: Number,
    },
    list: {
      required: true,
      type: Object,
    },
    searchResult: {
      required: true,
      type: null,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    title: {
      required: true,
      type: String,
    },
    placeholder: {
      required: true,
      type: String,
    },
    showHint: {
      required: true,
      type: Boolean,
    },
    achievementsHints: {
      required: true,
      type: Array,
    },
  },
  data() {
    return {
      loadingMore: false,
    }
  },
  computed: {
    inputColorIcon() {
      return this.title ? '#222222' : '#AAAAAA'
    },
    searchResultEmpty() {
      return !this.searchResult || !this.searchResult.length
    },
    isEmpty() {
      return !this.list.meta.total
    },
    categoryToolContent() {
      return `${this.$t('achievements.hints')[1]}<br />${this.$t('achievements.hints')[2]}<br />`
    },
  },
  methods: {
    showHintCategory(i) {
      return this.showHint && i === 0
    },
    async loadingList(paginate) {
      const routeCategory = Number(this.$route.params.subcategory)
      const { data } = await getProgressListByUser({
        user_id: this.$route.params.id,
        category_id: this.categoryId,
        subcategory_id: this.subcategoryIdExpanded,
        page: paginate.meta.page + 1,
        records: paginate.meta.records,
      })
      data.items = [
        ...paginate.items,
        ...data.items.map(k => ({
          ...k,
          expanded: routeCategory === k.id,
        })),
      ]
      this.$emit('update:list', data)
    },
    async loadMore() {
      this.loadingMore = true
      await this.loadingList(this.list)
      this.loadingMore = false
    },
    updateTitle(title) {
      this.$emit('updateTitle', title)
    },
  },
}
</script>

<style lang="stylus">
@import "achievements-page-list.styl"
</style>
