<template lang="pug">
  AdjacentModalContainer(@close="close")
    .user-stats-category-modal__close(@click="close")
      ModalCloseSVG
    .user-stats-category-modal__container
      .user-stats-category-modal__back-to-profile
        MobileBackTo(@click.native="close") {{ $t('users-stats-category-modal.back-to') }}
      .user-stats-category-modal
        .user-stats-category-modal__first-load(v-if="!stats")
          .user-stats-category-modal__first-load-loader
            LoaderRing
        .user-stats-category-modal__header
          .user-stats-category-modal__icon
            img(:src="category.icon")
          .user-stats-category-modal__name
            div {{ category.name }}
            .user-stats-category-modal__see-all
              NuxtLink(
                :to="localePath({ name: 'achievements-keyword', params: { keyword: category.keyword } })"
              ) {{ $t('users-stats-category-modal.see-all') }}
          .user-stats-category-modal__points {{ stats && stats.category.points }}
        .user-stats-category-modal__categories-container
          .user-stats-category-modal__categories(v-if="stats")
            .user-stats-category-modal__categories-no-result(
              v-if="!stats.meta.total"
            ) {{ isMe ? $t('users-stats-category-modal.no-achievements') : $t('users-stats-category-modal.no-achievements-other') }}
              br
              NuxtLink(
                v-if="isMe"
                class="user-stats-category-modal__categories-no-result-link-to-catalog"
                :to="localePath({ name: 'achievements-keyword', params: { keyword: category.keyword } })"
              ) {{ $t('users-stats-category-modal.go-to-catalog') }}
            UserStatsSubcategory(
              v-for="subcategory in stats.items"
              :key="subcategory.id"
              :id="`subcategory-${subcategory.id}`"
              :category="category"
              :subcategory="subcategory"
              :current-user="currentUser"
              :container="container"
            )
            .user-stats-category-modal__load-more(v-if="stats.meta.next_exists")
              Button(
                :disabled="loadingMore"
                :loading="loadingMore"
                :secondary="true"
                @click.native="loadMore"
              ) {{ $t('users-stats-category-modal.load-more') }}

</template>

<script>
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import MinCardUser from '../../MinCardUser/MinCardUser.vue'
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'
import UserStatsSubcategory from './UserStastSubcategory/UserStatsSubcategory.vue'
import { userStatsByCategory } from '../../../plugins/api/user'
import Button from '../../Interactives/Controls/Button/Button.vue'
import LoaderRing from '../../General/LoaderRing.vue'

export default {
  components: {
    AdjacentModalContainer,
    MinCardUser,
    MobileBackTo,
    ModalCloseSVG,
    UserStatsSubcategory,
    Button,
    LoaderRing,
  },
  props: {
    currentUser: {
      required: true,
      type: Object,
    },
    category: {
      required: true,
      type: Object,
    },
  },
  data() {
    this.$t.bind(this)
    const { user } = this.$store.state.auth
    return {
      loadingMore: false,
      stats: null,
      isMe: this.currentUser.id === user.id,
    }
  },
  computed: {
    screenWidth() {
      return this.$store.state.screenWidth
    },
    container() {
      return this.screenWidth <= 767 ? 'body' : '.user-stats-category-modal__categories'
    },
  },
  mounted() {
    this.loadStats()
    if (this.screenWidth <= 767) {
      window.scrollTo({ top: 0 })
    }
  },
  methods: {
    async loadStats() {
      const { data } = await userStatsByCategory(this.currentUser.id, {
        category_id: this.category.id,
        page: 1,
      })
      this.stats = data
    },
    async loadMore() {
      if (this.loadingMore) return
      this.loadingMore = true
      const { data } = await userStatsByCategory(this.currentUser.id, {
        category_id: this.category.id,
        page: this.stats.meta.page + 1,
      })
      data.items = [...this.stats.items, ...data.items]
      this.stats = data
      this.loadingMore = false
    },
    close() {
      this.$emit('close')
    },
  },
}
</script>

<style lang="stylus">
@import "user-stats-category-modal.styl"
</style>
