<template lang="pug">
  AdjacentModalContainer(@close="close")
    .system-achievement-users-complete__close(@click="close")
      ModalCloseSVG
    .system-achievement-users-complete__container
      .system-achievement-users-complete__back-to-profile
        MobileBackTo(
          @click.native="close"
        ) {{ $t('users-complete-modal.back-to') }}
      .system-achievement-users-complete
        .system-achievement-users-complete__first-load(v-if="!users")
          .system-achievement-users-complete__first-load-loader
            LoaderRing

        .system-achievement-users-complete__message-info {{ $t('users-complete-modal.message-info') }}
        .system-achievement-users-complete__header {{ getTitle }}
        .system-achievement-users-complete__search-input
          SearchInput(
            id="users-complete-search"
            v-model="name"
            :placeholder="$t('users-complete-modal.search-input.placeholder')"
          )
        .system-achievement-users-complete__search-result-container
          .system-achievement-users-complete__search-result(v-if="users")
            .system-achievement-users-complete__search-result-loader-container(v-if="loadingSearch")
              .system-achievement-users-complete__search-result-loader
                LoaderRing

            NuxtLink(
              class="system-achievement-users-complete__search-result-item"
              v-for="user in users.items"
              :key="user.id"
              :to="localePath({ name: 'id-index-achievements', params: { id: user.id } })"
            )
              MinCardUser(:current-user="user")
            InfiniteLoading(
              v-if="users.meta.next_exists"
              :force-use-infinite-wrapper="forceUseInfiniteWrapper"
              @infinite="infiniteHandler"
            )


</template>

<script>
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import MinCardUser from '../../MinCardUser/MinCardUser.vue'
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'
import { progressUsersComplete } from '../../../plugins/api/progress'
import LoaderRing from '../../General/LoaderRing.vue'
import SearchInput from '../../Interactives/Inputs/SearchInput/SearchInput.vue'
import { RequestTimer, PaginateList } from '../../../assets/js/helper-classes'

export default {
  components: {
    SearchInput,
    AdjacentModalContainer,
    MinCardUser,
    MobileBackTo,
    ModalCloseSVG,
    LoaderRing,
  },
  props: {
    achievement: {
      required: true,
      type: Object,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      users: null,
      loadingMore: false,
      loadingSearch: false,
      name: '',
      searchTimer: new RequestTimer(750),
    }
  },
  computed: {
    getTitle() {
      if (!this.users) return false
      return this.$t('users-complete-modal.title', {
        count: this.users.meta.total,
        name: this.achievement.title,
      })
    },
    screenWidth() {
      return this.$store.state.screenWidth
    },
    forceUseInfiniteWrapper() {
      return this.screenWidth <= 767
        ? '.adjacent-modal-open'
        : '.system-achievement-users-complete__search-result'
    },
  },
  watch: {
    name(name) {
      this.loadingSearch = true
      this.searchTimer.start(() => this.search(name))
    },
  },
  mounted() {
    this.loadUsers()
    if (this.screenWidth <= 767) {
      window.scrollTo({ top: 0 })
    }
  },
  methods: {
    close() {
      this.$emit('close')
    },
    async search(name) {
      this.users = await this.loadMore(new PaginateList(this.users.meta.records), name)
      this.loadingSearch = false
    },
    async loadUsers() {
      const { data } = await progressUsersComplete(this.achievement.id)
      this.users = data
    },
    async loadMore(paginate = this.users, fio = '') {
      const { data } = await progressUsersComplete(this.achievement.id, {
        page: paginate.meta.page + 1,
        records: paginate.meta.records,
        fio,
      })
      data.items = [...paginate.items, ...data.items]
      return data
    },
    async infiniteHandler(e) {
      const paginate = await this.loadMore()
      if (!paginate.meta.next_exists) {
        e.complete()
      } else {
        e.loaded()
      }
      this.users = paginate
    },
  },
}
</script>

<style lang="stylus">
@import "system-achievement-users-complete.styl"
</style>
