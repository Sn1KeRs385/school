import { RequestTimer, PaginateList } from '../../../assets/js/helper-classes'

export default {
  props: {
    currentUser: {
      required: true,
      type: Object,
    },
    paginate: {
      required: true,
      type: Object,
    },
    paginateMethod: {
      required: true,
      type: Function,
    },
    loading: {
      required: true,
      type: Boolean,
    },
    search: {
      required: true,
      type: String,
    },
    classItem: {
      required: true,
      type: [String, Array, Object],
    },
    forceUseInfiniteWrapper: {
      required: true,
      type: String,
    },
  },
  data() {
    return {
      requestTimer: new RequestTimer(500),
    }
  },
  watch: {
    async search(search) {
      this.$emit('loadingChange', true)
      const handler = async () => {
        const paginate = await this.loadMore(new PaginateList(this.paginate.meta.records), search)
        this.$emit('changePaginate', paginate)
        this.$emit('loadingChange', false)
      }
      this.requestTimer.start(handler)
    },
  },
  methods: {
    async infiniteHandler(e) {
      const paginate = await this.loadMore()
      this.$emit('changePaginate', paginate)
      if (paginate.meta.next_exists) {
        e.loaded()
      } else {
        e.complete()
      }
    },
    async loadMore(currentPaginate = this.paginate, search = this.search) {
      const { data } = await this.paginateMethod(this.currentUser.id, {
        fio: search,
        page: currentPaginate.meta.next_page,
        records: currentPaginate.meta.records,
      })
      data.items = [...currentPaginate.items, ...data.items]
      return data
    },
  },
}
