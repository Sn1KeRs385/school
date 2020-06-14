<template lang="pug">
  .achievements-page-fame
    .achievements-page-fame__empty(
      v-if="isEmpty"
    ) {{ $t('achievements.tabs.fame-empty') }}
      br
      | :(
    div(v-else)
      .achievements-page-fame__search
        SearchInput(
          id="achievements-page-fame-search"
          :placeholder="placeholder"
          :value="title"
          :icon-color="inputColorIcon"
          @changeValue="updateTitle"
        )
      .achievements-page-fame__loader(v-if="loading")
        LoaderRing(size="40px")
      div(v-if="!searchResult && !isEmpty")
        AchievementsPageSubcategory(
          v-for="subcategory in fame.items"
          :category-id="categoryId"
          :key="subcategory.id"
          :subcategory="subcategory"
          :expanded.sync="subcategory.expanded"
        )
        .achievements-page-fame__footer(v-if="fame.meta.next_exists")
          Button(
            :secondary="true"
            :loading="loadingMore"
            @click.native="loadMore"
          ) {{ $t('achievements.load-more') }}
            template(slot="before")
              .achievements-page-fame__arrow-load
                ArrowLoadSVG
      .achievements-page-fame__search-result(v-else)
        .achievements-page-fame__no-result(
          v-if="searchResultEmpty && !loading"
        ) {{ $t('achievements.no-result', { title }) }}
        .achievements-page-fame__elem(
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
import { getProgressFameByUser } from '../../../../plugins/api/progress'
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
    fame: {
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
      return !this.fame.meta.total
    },
  },
  methods: {
    updatedExpanded({ subcategory, expanded }) {
      this.$emit('updateExpanded', expanded ? subcategory.id : null)
    },
    async loadingList(paginate) {
      const routeCategory = Number(this.$route.params.subcategory)
      const { data } = await getProgressFameByUser({
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
      this.$emit('update:fame', data)
    },
    async loadMore() {
      this.loadingMore = true
      await this.loadingList(this.fame)
      this.loadingMore = false
    },
    updateTitle(title) {
      this.$emit('updateTitle', title)
    },
  },
}
</script>

<style lang="stylus">
@import "achievements-page-fame.styl"
</style>
