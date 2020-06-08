<template lang="pug">
  AchievementsCategory(
    v-if="!notFound"
    :titleList.sync="titleList"
    :titleFame.sync="titleFame"
    :tab="tab"
    :list.sync="list"
    :fame.sync="fame"
    :category-id="categoryId"
    :hints-info="hintsInfo"
    :search-placeholder="$t('achievements.feeling.search-placeholder')"
    @update:tab="updateTab"
  )
</template>

<script>
import AchievementsCategory from '../../../components/AchievementsPage/AcievementsPageCategory/AchievementsCategory.vue'
import { getProgressListByUser, getProgressFameByUser } from '../../../plugins/api/progress'

export default {
  middleware: 'authenticated',
  components: {
    AchievementsCategory,
  },
  props: {
    tab: {
      required: true,
      type: String,
    },
    hintsInfo: {
      required: true,
      type: Object,
    },
  },
  validate({ params, store }) {
    const category = store.state.progress.categories.find(k => k.keyword === params.keyword)
    return !!category
  },
  async asyncData({ params, store, redirect }) {
    const routeCategory = Number(params.subcategory)
    const category = store.state.progress.categories.find(k => k.keyword === params.keyword)
    if (!category) {
      return redirect()
    }
    const categoryId = category.id
    const formData = {
      category_id: categoryId,
      records: 3,
    }
    const [list, fame] = await Promise.all([
      getProgressListByUser(formData),
      getProgressFameByUser(formData),
    ])
    return {
      titleList: '',
      titleFame: '',
      list: {
        ...list.data,
        items: list.data.items.map(k => ({
          ...k,
          expanded: routeCategory === k.id,
        })),
      },
      fame: {
        ...fame.data,
        items: fame.data.items.map(k => ({
          ...k,
          expanded: routeCategory === k.id,
        })),
      },
      categoryId,
      notFound: false,
    }
  },
  methods: {
    updateTab(tab) {
      this.$emit('update:tab', tab)
    },
  },
}
</script>
