<template lang="pug">
  PageLayout
    .row.no-gutters
      .col-lg.col-12
        SearchUsersList(
          :name.sync="name"
          :usersList.sync="usersList"
          :loading="loadingNewList"
          @showSettingsModal="showSettingsModal"
        )
        NoSsr
          InfiniteLoading(
            v-if="usersList.meta.next_exists && !loadingNewList"
            @infinite="infiniteHandler"
          )
      .col-lg-auto.d-lg-block.d-none
        SearchUsersSettings(
          :name="name"
          :settings="settings"
          :loading="loadingNewList"
          :max-age.sync="settingsModal.props.maxAge"
          :min-age.sync="settingsModal.props.minAge"
          @settingsUpdate="settingsUpdate"
          @clearSettings="clearSettings"
        )
</template>

<script>
import PageLayout from '../General/PageLayout/PageLayout.vue'
import SearchUsersSettings from './SearchUsersSettings/SearchUsersSettings.vue'
import SearchUsersList from './SearchUsersList/SearchUsersList.vue'
import UserSearchSettingsModal from '../Modals/UserSearchSettingsModal/UserSearchSettingsModal.vue'
import { searchUser } from '../../plugins/api/search'
import { PaginateList, RequestTimer } from '../../assets/js/helper-classes'

export default {
  inject: ['setModal'],
  components: {
    PageLayout,
    SearchUsersSettings,
    SearchUsersList,
    UserSearchSettingsModal,
  },
  props: {
    usersList: {
      required: true,
      type: Object,
    },
    settings: {
      required: true,
      type: Object,
    },
    preName: {
      required: true,
      type: String,
    },
  },
  data() {
    const { settings, preName } = this
    return {
      loadingMore: false,
      requestTimer: new RequestTimer(),
      settingsModal: {
        component: UserSearchSettingsModal,
        props: {
          name: preName,
          loading: false,
          minAge: null,
          maxAge: null,
          settings,
        },
        events: {
          'update:min-age': minAge => {
            this.settingsModal.props.minAge = minAge
          },
          'update:max-age': maxAge => {
            this.settingsModal.props.maxAge = maxAge
          },
          settingsUpdate: this.settingsUpdate,
          clearSettings: this.clearSettings,
          close: this.setModal,
        },
      },
    }
  },
  computed: {
    loadingNewList: {
      get() {
        return this.settingsModal.props.loading
      },
      set(loading) {
        this.settingsModal.props.loading = loading
      },
    },
    name: {
      get() {
        return this.settingsModal.props.name
      },
      set(name) {
        this.settingsModal.props.name = name
      },
    },
  },
  watch: {
    name() {
      this.loadingNewList = true
      this.requestTimer.start(async () => {
        const usersList = await this.loadMoreUsers(new PaginateList(15))
        this.$emit('update:users-list', usersList)
        this.loadingNewList = false
      })
    },
  },
  methods: {
    showSettingsModal() {
      this.setModal(this.settingsModal)
    },
    async loadMoreUsers(usersList, settings = this.settings) {
      const result = await searchUser({
        page: usersList.meta.page + 1,
        records: usersList.meta.records,
        fio: this.name || null,
        ...settings,
      })
      result.data.items = [...usersList.items, ...result.data.items]
      return result.data
    },
    async infiniteHandler(e) {
      if (this.loadingMore) return
      this.loadingMore = true
      const usersList = await this.loadMoreUsers(this.usersList)
      this.$emit('update:usersList', usersList)
      if (usersList.meta.next_exists) {
        e.loaded()
      } else {
        e.complete()
      }
      this.loadingMore = false
    },
    async settingsUpdate(settings) {
      this.$emit('update:settings', settings)
      this.loadingNewList = true
      const usersList = await this.loadMoreUsers(new PaginateList(15), settings)
      this.$emit('update:users-list', usersList)
      this.loadingNewList = false
    },
    clearSettings() {
      this.loadingNewList = true
      this.settingsModal.props.minAge = null
      this.settingsModal.props.maxAge = null
      this.name = ''
      this.$emit('clearSettings', {
        callback: async settings => {
          const usersList = await this.loadMoreUsers(new PaginateList(15), settings)
          this.$emit('update:users-list', usersList)
          this.loadingNewList = false
        },
      })
    },
  },
}
</script>

<style lang="stylus">
@import "search-users-page.styl"
</style>
