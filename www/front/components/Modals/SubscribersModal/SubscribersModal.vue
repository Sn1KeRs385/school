<template lang="pug">
  AdjacentModalContainer(@close="close")
    .subscribers-modal__close(@click="close")
      ModalCloseSVG
    .subscribers-modal__container
      .subscribers-modal__back-to-profile
        MobileBackTo(
          @click.native="close"
        ) {{ $t('subscribers-modal.back-to') }}
      .subscribers-modal
        .subscribers-modal__header
          DoubleTabs(v-if="isMe" :tab="currentTab")
            template(slot="left")
              .subscribers-modal__tab(
                @click="changeTab('left')"
              ) {{ $t('subscribers-modal.tabs.left') }}
            template(slot="right")
              .subscribers-modal__tab(
                @click="changeTab('right')"
              ) {{ $t('subscribers-modal.tabs.right') }}

          .subscribers-modal__search-input-container
            SearchInput(
              id="subscribers-search"
              :placeholder="currentList.placeholder"
              v-model="currentList.search"
            )

        .subscribers-modal__search-result-container
          .subscribers-modal__search-result-loader-container(v-if="currentList.loading")
            .subscribers-modal__search-result-loader
              LoaderRing
          .subscribers-modal__search-result
            KeepAlive
              transition(:name="currentList.transition")
                component(
                  class-item="subscribers-modal__search-result-item"
                  :is="currentList.component"
                  :search="currentList.search"
                  :loading="currentList.loading"
                  :force-use-infinite-wrapper="forceUseInfiniteWrapper"
                  v-bind="currentList.props"
                  v-on="currentList.events"
                )
                  template(slot="no-result")
                    .subscribers-modal__search-result-no-result(
                      v-if="currentListEmpty"
                    ) {{ currentList.noResult() }}
                  template(slot="item" slot-scope="{ item }")
                    NuxtLink(
                      class="d-block"
                      :to="localePath({ name: 'id-index-achievements', params: { id: item.id } })"
                    )
                      MinCardUser(:current-user="item")
                  template(slot="loader-more")
                    .subscribers-modal__more-loader-container
                      LoaderRing(size="40px")

</template>

<script>
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import SearchInput from '../../Interactives/Inputs/SearchInput/SearchInput.vue'
import LoaderRing from '../../General/LoaderRing.vue'
import MinCardUser from '../../MinCardUser/MinCardUser.vue'
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo.vue'
import DoubleTabs from '../../General/DoubleTabs/DoubleTabs.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'
import ModalSubscribersList from './ModalSubscribersList/ModalSubscribersList.vue'
import ModalFollowersList from './ModalFollowerssList/ModalFollowersList.vue'
import { getUserFollowers, getUserSubscribers } from '../../../plugins/api/user'

export default {
  components: {
    AdjacentModalContainer,
    SearchInput,
    LoaderRing,
    MinCardUser,
    MobileBackTo,
    DoubleTabs,
    ModalCloseSVG,
  },
  props: {
    preSelectedTab: {
      type: String,
      default: 'left',
    },
    isMe: {
      required: true,
      type: Boolean,
    },
    currentUser: {
      required: true,
      type: Object,
    },
    paginateSubscribers: {
      required: true,
      type: Object,
    },
    paginateFollowers: {
      required: true,
      type: Object,
    },
  },
  data() {
    const $t = this.$t.bind(this)
    const { preSelectedTab, paginateSubscribers, paginateFollowers } = this
    return {
      currentTab: preSelectedTab,
      tabs: {
        left: {
          placeholder: $t('subscribers-modal.subscribers-form.placeholder'),
          search: '',
          transition: 'transition-tab-left',
          component: ModalSubscribersList,
          loading: false,
          noResult() {
            return $t('subscribers-modal.subscribers-form.no-result', { search: this.search })
          },
          props: {
            currentUser: this.currentUser,
            paginate: paginateSubscribers,
            paginateMethod: getUserSubscribers,
          },
          events: {
            changePaginate: paginate => {
              this.tabs.left.props.paginate = paginate
            },
            loadingChange: loading => {
              this.tabs.left.loading = loading
            },
          },
        },
        right: {
          placeholder: $t('subscribers-modal.followers-form.placeholder'),
          search: '',
          transition: 'transition-tab-right',
          component: ModalFollowersList,
          loading: false,
          noResult() {
            return $t('subscribers-modal.followers-form.no-result', { search: this.search })
          },
          props: {
            currentUser: this.currentUser,
            paginate: paginateFollowers,
            paginateMethod: getUserFollowers,
          },
          events: {
            changePaginate: paginate => {
              this.tabs.right.props.paginate = paginate
            },
            loadingChange: loading => {
              this.tabs.right.loading = loading
            },
          },
        },
      },
    }
  },
  computed: {
    currentList() {
      return this.tabs[this.currentTab]
    },
    currentListEmpty() {
      return !this.currentList.props.paginate.items.length
    },
    screenWidth() {
      return this.$store.state.screenWidth
    },
    forceUseInfiniteWrapper() {
      return this.screenWidth <= 767 ? '.adjacent-modal-open' : '.subscribers-modal__search-result'
    },
  },
  mounted() {
    if (this.screenWidth <= 767) {
      window.scrollTo({ top: 0 })
    }
  },
  methods: {
    changeTab(tab) {
      this.currentTab = tab
    },
    close() {
      this.$emit('close')
    },
  },
}
</script>

<style lang="stylus">
@import "subscribers-modal.styl"
</style>
