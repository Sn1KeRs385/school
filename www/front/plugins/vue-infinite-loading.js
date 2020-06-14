import Vue from 'vue'
import InfiniteLoading from 'vue-infinite-loading'
import InfinityRingLoader from '../components/General/InfinityRingLoader/InfinityRingLoader.vue'

Vue.component('InfiniteLoading', InfiniteLoading)

Vue.use(InfiniteLoading, {
  slots: {
    spinner: InfinityRingLoader,
    error: '',
    noResults: '',
    noMore: '',
  },
})
