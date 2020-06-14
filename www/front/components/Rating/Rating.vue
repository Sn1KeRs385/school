<template lang="pug">
  PageLayout
    .row.no-gutters
      .col-lg.col-12
        RatingList(
          :loading="sumLoading"
          :ratingList="ratingList"
          :sort-likes.sync="sortLikes"
          :settings="settings"
          :rating-page-showed="ratingPageShowed"
          @loadingChange="listLoadingChange"
          @changeRatingList="changeRatingList"
        )
        NoSsr
          InfiniteLoading(
            v-if="ratingList.meta.total !== ratingList.items.length && !settingsLoading"
            @infinite="infiniteHandler"
          )
      .col-lg-auto.col-12.d-lg-block.d-none
        RatingSettings(
          :loading="sumLoading"
          :settings="settings"
          :rating-page-showed="ratingPageShowed"
          @loadingChange="settingsLoadingChange"
          @changeRatingList="changeRatingList"
        )

</template>

<script>
import PageLayout from '../General/PageLayout/PageLayout.vue'
import RatingList from './RatingList/RatingList.vue'
import RatingSettings from './RatingSettings/RatingSettings.vue'
import { getRating } from '../../plugins/api/rating'
import { userSendHint } from '../../plugins/api/user'
import { getUserByToken } from '../../plugins/api/auth'

export default {
  components: {
    PageLayout,
    RatingList,
    RatingSettings,
  },
  props: {
    ratingList: {
      required: true,
      type: Object,
    },
    settings: {
      required: true,
      type: Object,
    },
    ratingPageShowed: {
      required: true,
      type: Boolean,
    },
  },
  data() {
    return {
      settingsLoading: false,
      listLoading: false,
      sortLikes: false,
      loadingMore: false,
    }
  },
  computed: {
    sumLoading() {
      return this.settingsLoading || this.listLoading
    },
  },
  methods: {
    settingsLoadingChange(loading) {
      this.settingsLoading = loading
    },
    listLoadingChange(loading) {
      this.listLoading = loading
    },
    changeRatingList(ratingList) {
      this.$emit('changeRatingList', ratingList)
    },
    async loadMoreRating() {
      const lastItem = this.ratingList.items[this.ratingList.items.length - 1]
      const result = await getRating({
        offset_rnk: lastItem ? lastItem.rnk : 0,
        direction: '>',
        records: this.ratingList.meta.records,
        sort_likes: Number(this.sortLikes),
      })
      result.data.items = [...this.ratingList.items, ...result.data.items]
      return result.data
    },
    async infiniteHandler(e) {
      if (this.loadingMore) return
      this.loadingMore = true
      const ratingList = await this.loadMoreRating()
      this.$emit('changeRatingList', ratingList)
      if (ratingList.meta.total !== ratingList.items.length) {
        e.loaded()
      } else {
        e.complete()
      }
      this.loadingMore = false
    },
  },
}
</script>
