<template lang="pug">
  PriorityCategoriesTab(
    :priorityCategories.sync="priorityCategories"
  )
</template>

<script>
import PriorityCategoriesTab from '../../components/SettingsTabs/PriorityCategoriesTab/PriorityCategoriesTab.vue'
import { getPriorityCategories } from '../../plugins/api/user'

export default {
  components: {
    PriorityCategoriesTab,
  },
  async asyncData() {
    const [priorityCategories] = await Promise.all([getPriorityCategories()])
    return {
      priorityCategories: priorityCategories.data,
    }
  },
}
</script>
