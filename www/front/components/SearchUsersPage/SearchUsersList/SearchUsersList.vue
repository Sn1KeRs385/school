<template lang="pug">
  .search-users-list(
    :class="{'search-users-list--no-result': isEmptyList}"
  )
    .search-users-list__loader-container(v-if="loading")
      .search-users-list__loader
        LoaderRing(size="50px" weight="4px")
    .search-users-list__input
      SearchInput(
        id="search-users-list-name"
        :icon-color="inputColorIcon"
        :value="name"
        :placeholder="$t('search-user.placeholder')"
        @changeValue="changeName"
      )
      .search-users-list__settings(@click="showSettingsModal")
        RattingSettingsSVG

    .search-users-list__counter(
      v-if="!isEmptyList"
    ) {{ $t('search-user.result', { total: usersList.meta.total }) }}
    .search-users-list__no-result(v-else)
      .search-users-list__no-result-text
        | {{ $t('search-user.no-result') }}
    .search-users-list__list
      NuxtLink(
        v-for="user in usersList.items"
        :key="user.id"
        class="search-users-list__list-item"
        :to="localePath({ name: 'id-index-achievements', params: { id: user.id } })"
      )
        MinCardUser(:current-user="user")

</template>

<script>
import SearchInput from '../../Interactives/Inputs/SearchInput/SearchInput.vue'
import MinCardUser from '../../MinCardUser/MinCardUser.vue'
import LoaderRing from '../../General/LoaderRing.vue'
import RattingSettingsSVG from '../../SVG/RatingSettingsSVG.vue'

export default {
  components: {
    SearchInput,
    MinCardUser,
    LoaderRing,
    RattingSettingsSVG,
  },
  props: {
    name: {
      required: true,
      type: String,
    },
    usersList: {
      required: true,
      type: Object,
    },
    loading: {
      required: true,
      type: Boolean,
    },
  },
  computed: {
    inputColorIcon() {
      return this.name ? '#222222' : '#AAAAAA'
    },
    isEmptyList() {
      return !this.usersList.items.length
    },
  },
  methods: {
    changeName(name) {
      this.$emit('update:name', name)
    },
    showSettingsModal() {
      this.$emit('showSettingsModal')
    },
  },
}
</script>

<style lang="stylus">
@import "search-users-list.styl"
</style>
