<template lang="pug">
  .achievements-category
    .achievements-category__tabs
      DoubleTabs(:tab="tab")
        template(slot="left")
          .achievements-category__tab(
            @click="updateTab('left')"
          ) {{ $t('achievements.tabs.left') }}
        template(slot="right")
          ToolTip(
            v-if="!hintsInfo.catalogPageShowed"
            :content="hintsInfo.getAchievementsHints[0]"
            :hint-number="-1"
            class="tool-tip__mark-container-header tool-tip__mark-container-achievement-hall"
          )
          .achievements-category__tab(
            @click="updateTab('right')"
          ) {{ $t('achievements.tabs.right') }}
    .achievements-category__body
      Transition(:name="tab === 'left' ? 'transition-tab-left' : 'transition-tab-right'")
        AchievementsPageList(
          v-if="tab === 'left'"
          :title="titleList"
          :placeholder="searchPlaceholder"
          :category-id="categoryId"
          :list.sync="localList"
          :loading="loadingList"
          :search-result="searchResultList"
          :show-hint="!hintsInfo.catalogPageShowed"
          :achievements-hints="hintsInfo.getAchievementsHints"
          @updateList="updateList"
          @updateTitle="updateTitleList"
        )
        AchievementsPageFame(
          v-if="tab === 'right'"
          :title="titleFame"
          :placeholder="searchPlaceholder"
          :category-id="categoryId"
          :fame.sync="localFame"
          :loading="loadingFame"
          :search-result="searchResultFame"
          @updateList="updateFame"
          @updateTitle="updateTitleFame"
        )
</template>

<script>
import DoubleTabs from '../../General/DoubleTabs/DoubleTabs.vue'
import AchievementsPageList from './AchievementsPageList/AchievementsPageList.vue'
import AchievementsPageFame from './AchievementsPageFame/AchievementsPageFame.vue'
import SearchInput from '../../Interactives/Inputs/SearchInput/SearchInput.vue'
import { RequestTimer } from '../../../assets/js/helper-classes'
import { getProgressListByUser, getProgressFameByUser } from '../../../plugins/api/progress'
import ToolTip from '../../General/ToolTip/ToolTip.vue'

export default {
  components: {
    DoubleTabs,
    SearchInput,
    AchievementsPageList,
    AchievementsPageFame,
    ToolTip,
  },
  // inject: ['getAchievementsHints', 'catalogPageShowed'],
  props: {
    hintsInfo: {
      required: true,
      type: Object,
    },
    categoryId: {
      required: true,
      type: Number,
    },
    list: {
      required: true,
      type: Object,
    },
    fame: {
      required: true,
      type: Object,
    },
    tab: {
      type: String,
      default: 'left',
    },
    searchPlaceholder: {
      type: String,
      default: '',
    },
    titleList: {
      required: true,
      type: String,
    },
    titleFame: {
      required: true,
      type: String,
    },
  },
  data() {
    return {
      requestTimerList: new RequestTimer(),
      requestTimerFame: new RequestTimer(),
      loadingList: false,
      loadingFame: false,
      searchResultList: null,
      searchResultFame: null,
    }
  },
  computed: {
    localList: {
      get() {
        return this.list
      },
      set(newList) {
        this.$emit('update:list', newList)
      },
    },
    localFame: {
      get() {
        return this.fame
      },
      set(fame) {
        this.$emit('update:fame', fame)
      },
    },
  },
  watch: {
    routeCategory() {
      return Number(this.$route.params.subcategory)
    },
    titleList(title) {
      if (title) {
        this.loadingList = true
        this.requestTimerList.start(() => this.searchList(title))
      } else {
        this.requestTimerList.clear()
        this.searchResultList = null
        this.loadingList = false
      }
    },
    titleFame(title) {
      if (title) {
        this.loadingFame = true
        this.requestTimerFame.start(() => this.searchFame(title))
      } else {
        this.requestTimerFame.clear()
        this.searchResultFame = null
        this.loadingFame = false
      }
    },
  },
  methods: {
    async searchList(title) {
      const { data } = await getProgressListByUser({
        category_id: this.categoryId,
        records: 3,
        page: 1,
        title,
      })
      this.searchResultList = title ? data : []
      this.loadingList = false
    },
    async searchFame(title) {
      const { data } = await getProgressFameByUser({
        category_id: this.categoryId,
        records: 3,
        page: 1,
        title,
      })
      this.searchResultFame = data
      this.loadingFame = false
    },
    updateTab(tab) {
      this.$emit('update:tab', tab)
    },
    updateList(list) {
      this.$emit('update:list', list)
    },
    updateFame(fame) {
      this.$emit('update:fame', fame)
    },
    updateTitleList(titleList) {
      this.$emit('update:titleList', titleList)
    },
    updateTitleFame(titleFame) {
      this.$emit('update:titleFame', titleFame)
    },
  },
}
</script>

<style lang="stylus">
@import "achievements-category.styl"
</style>
