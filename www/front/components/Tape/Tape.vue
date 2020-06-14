<template lang="pug">
  PageLayout
    .row.no-gutters(v-if="tape.items.length")
      .col-12
        .tape__achievement(v-for="achievement in tape.items")
          AchievementCard(
            :achievement.sync="achievement"
          )
      .col-12
        NoSsr
          InfiniteLoading(
            v-if="tape.meta.next_exists"
            @infinite="infiniteHandler"
          )
    .row.no-gutters(v-else)
      .col-12
        EmptyTape

</template>

<script>
import PageLayout from '../General/PageLayout/PageLayout.vue'
import AchievementCard from '../AchievementCard/AchievementCard.vue'
import InfinityRingLoader from '../General/InfinityRingLoader/InfinityRingLoader.vue'
import EmptyTape from './EmptyTape/EmptyTape.vue'
import { getTape } from '../../plugins/api/user'

export default {
  components: {
    PageLayout,
    AchievementCard,
    InfinityRingLoader,
    EmptyTape,
  },
  props: {
    tape: {
      required: true,
      type: Object,
    },
  },
  methods: {
    async loadMore(paginate) {
      const { data } = await getTape({
        page: paginate.meta.page + 1,
        records: paginate.meta.records,
      })
      data.items = [...paginate.items, ...data.items]
      return data
    },
    async infiniteHandler(e) {
      const tape = await this.loadMore(this.tape)
      this.$emit('update:tape', tape)
      if (tape.meta.next_exists) {
        e.loaded()
      } else {
        e.complete()
      }
    },
  },
  provide() {
    return {
      getProfileCardHints: [],
    }
  },
}
</script>

<style lang="stylus">
@import "tape.styl"
</style>
