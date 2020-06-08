<template lang="pug">
  SearchUsersPage(
    :pre-name="name"
    :users-list.sync="usersList"
    :settings.sync="settings"
    @clearSettings="clearSettings"
  )
</template>

<script>
import SearchUsersPage from '../../components/SearchUsersPage/SearchUsersPage.vue'
import { searchUser } from '../../plugins/api/search'

class SearchUserSettings {
  constructor(settings = {}) {
    this.countries = settings.countries || []
    this.cities = settings.cities || []
    this.borned = settings.borded || { start_date: null, end_date: null }
    this.male = settings.male || null
  }
}

export default {
  middleware: 'authenticated',
  components: {
    SearchUsersPage,
  },
  async asyncData({ store }) {
    const name = store.state.headerSearch
    const usersList = await searchUser({ fio: name, records: 15 })
    return {
      usersList: usersList.data,
      settings: new SearchUserSettings(),
      name,
    }
  },
  methods: {
    clearSettings(e) {
      const settings = new SearchUserSettings()
      this.settings = settings
      e.callback(settings)
    },
  },
}
</script>
