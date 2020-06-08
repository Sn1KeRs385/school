<template>
  <div class="post">
    <div class="post__header">
      <img :src="post.user.avatar" alt="avatar" class="post__avatar" />
      <div class="post__name">
        {{ post.user.name }}
        <div class="post__login">{{ post.user.login }}</div>
      </div>
      <div class="post__menu" />
    </div>
    <div class="post__content">
      <div class="post__title">
        {{ post.title }}
      </div>
      <div class="post__geo-position">
        {{ post.geo.name }}
      </div>
      <img v-if="post.picture" :src="post.picture" alt="avatar" class="post__picture" />
      <p class="post__text">
        {{ text }}
        <span v-if="shortText" class="post__more" @click="viewAllText()">ещё</span>
      </p>
      <div v-if="post.tags" class="post__tags">
        <div v-for="(item, i) in post.tags" :key="i" class="post__tag">{{ item }}</div>
      </div>
      <div class="post__params">
        <div class="post__data">
          {{ post.date }}
        </div>
        <div class="post__stars">
          {{ post.stars }}
        </div>
        <div class="post__likes">
          {{ post.likes }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  scrollToTop: false,
  components: {},
  props: {
    post: {
      default() {
        return {}
      },
      type: Object,
    },
  },
  data() {
    return {
      shortText: true,
    }
  },
  computed: {
    text() {
      return this.shortText ? this.post.short_text : this.post.text
    },
  },
  methods: {
    viewAllText() {
      this.shortText = false
    },
  },
}
</script>

<style src="./post.styl" lang="stylus" scoped></style>
