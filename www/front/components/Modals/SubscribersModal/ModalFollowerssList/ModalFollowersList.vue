<template lang="pug">
  div
    div(v-if="!loading")
      slot(name="no-result")
      div(
        :class="classItem"
        v-for="item in paginate.items"
        :key="item.id"
      )
        slot(name="item" :item="item")
    InfiniteLoading(
      v-if="paginate.meta.next_exists && !loading"
      :force-use-infinite-wrapper="forceUseInfiniteWrapper"
      @infinite="infiniteHandler"
    )
      template(slot="spinner")
        slot(name="loader-more")
</template>

<script>
import SubscribersModalListMixin from '../subscribers-modal-list-mixin'

export default {
  mixins: [SubscribersModalListMixin],
}
</script>
