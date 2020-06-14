<template lang="pug">
  .comments-control
    .comments-control__header
      .comments-control__header-title {{ $t('comments.title', { total: comments.meta.total }) }}
    .comments-control__wrapper
      .comments-control__body(:id="id")
        .comments-control__load-more(v-if="loadableMore")
          LoaderRing(v-if="loadingMore" size="30px" weight="2px")
          span(v-else @click="loadMore") {{ $t('comments.load-more') }}
        CommentsControlItem(
          :id="`${id}-comment-${comment.id}`"
          v-for="comment in comments.items"
          :key="comment.id"
          :comment="comment"
        )
      .comments-control__footer-container
        .comments-control__footer
          InputComment(
            id="comment-input"
            :placeholder="$t('comments.input.placeholder')"
            v-model="newComment.value"
            @send="createComment"
          )

</template>

<script>
import CommentsControlItem from './CommentsControlItem/CommentsControlItem.vue'
import InputComment from './InputComment/InputComment.vue'
import LoaderRing from '../General/LoaderRing.vue'

export default {
  components: {
    CommentsControlItem,
    InputComment,
    LoaderRing,
  },
  props: {
    id: {
      required: true,
      type: null,
    },
    comments: {
      required: true,
      type: Object,
    },
    loadingMore: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      newComment: {
        value: '',
      },
    }
  },
  computed: {
    loadableMore() {
      return this.comments.items.length !== this.comments.meta.total
    },
    screenWidth() {
      return this.$store.state.screenWidth
    },
    screenHeight() {
      return this.$store.state.screenHeight
    },
  },
  mounted() {
    const unwatch = this.$watch('screenWidth', () => {
      const lastComment = this.comments.items[this.comments.items.length - 1]
      if (lastComment && this.screenWidth >= 992) {
        this.scrollToComment(lastComment)
      }
      unwatch()
    })
  },
  methods: {
    scrollToComment(comment) {
      setTimeout(() => {
        const container = this.screenWidth >= 992 ? `#${this.id}` : 'body'
        this.$scrollTo(`#${this.id}-comment-${comment.id}`, 1, { container })
      })
    },
    createComment(text) {
      const { scrollToComment } = this
      this.newComment.value = ''
      this.$emit('createComment', { text, scrollToComment })
    },
    loadMore() {
      this.$emit('loadMore', {
        direction: '<',
        commentId: this.comments.items[0].id,
      })
    },
  },
}
</script>

<style lang="stylus">
@import "comments-control.styl"
</style>
