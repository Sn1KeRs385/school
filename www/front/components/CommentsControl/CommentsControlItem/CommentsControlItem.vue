<template lang="pug">
  .comments-control-item
    .comments-control-item__avatar-container
      NuxtLink(
        class="comments-control-item__avatar"
        :style="{ 'background-image': `url(${getCurrentUser.avatar_url})` }"
        :to="localePath({ name: 'id-index-achievements', params: { id: getCurrentUser.id } })"
      )
    .comments-control-item__comment
      .comments-control-item__comment-author
        NuxtLink(
          :to="localePath({ name: 'id-index-achievements', params: { id: getCurrentUser.id } })"
        ) {{ getCurrentUser.full_name }}
      .comments-control-item__comment-text {{ comment.description }}
      .comments-control-item__comment-footer
        .comments-control-item__comment-footer-time
          TimeAgo(:date-time="comment.created_at")
        .comments-control-item__comment-footer-reply.d-none {{ $t('comments.to-answer') }}
</template>

<script>
import TimeAgo from '../../General/TimeAgo.vue'

export default {
  components: {
    TimeAgo,
  },
  props: {
    comment: {
      required: true,
      type: Object,
    },
  },
  computed: {
    getCurrentUser() {
      return this.comment.user
    },
  },
}
</script>

<style lang="stylus">
@import "comments-control-item.styl"
</style>
