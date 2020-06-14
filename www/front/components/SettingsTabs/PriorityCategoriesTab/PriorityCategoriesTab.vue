<template lang="pug">
  SettingTabContainer
    template(slot="title") {{ $t('settings.categories.title') }}
    form.priority-categories-tab(@submit.prevent="submit")
      .priority-categories-tab__description {{ $t('settings.categories.description') }}
      .priority-categories-tab__search-input
        Multiselector(
          id="priority-categories-search-input"
          :multiply="true"
          :value="priorityCategories"
          :search.sync="subcategoryName"
          :is-open.sync="isOpen"
          :placeholder="$t('settings.categories.inputs.search.placeholder')"
          :options="options"
          :loading="loadingSearch"
          :with-toggle="false"
          :searchable="true"
          :close-by-select="false"
        )
      .priority-categories-tab__submit-container
        .priority-categories-tab__submit
          Button(
            :submit="changed"
            :disabled="!changed"
            :loading="loadingForm"
          ) {{ $t('settings.categories.submit') }}
</template>

<script>
import SettingTabContainer from '../SettingTabContainer/SettingTabContainer.vue'
import SettingTabField from '../SettingTabField/SettingTabField.vue'
import Multiselector from '../../Interactives/Inputs/Multiselector/Multiselector.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import { searchSubcategories, setPriorityCategories } from '../../../plugins/api/user'
import { RequestTimer } from '../../../assets/js/helper-classes'

export default {
  components: {
    SettingTabContainer,
    SettingTabField,
    Multiselector,
    Button,
  },
  props: {
    priorityCategories: {
      required: true,
      type: Array,
    },
  },
  data() {
    return {
      changed: false,
      requestTimer: new RequestTimer(750),
      subcategoryName: '',
      isOpen: false,
      options: [...this.$store.state.progress.subCategories],
      loadingSearch: false,
      loadingForm: false,
    }
  },
  computed: {
    getSubCategories() {
      return this.$store.state.progress.subCategories
    },
  },
  watch: {
    subcategoryName(name) {
      if (name.length <= 1) {
        this.loadingSearch = false
        this.options = [...this.getSubCategories]
      } else {
        this.loadingSearch = true
        this.requestTimer.start(() => this.search(name))
      }
    },
    priorityCategories: {
      handler() {
        this.changed = true
      },
      deep: true,
    },
  },
  methods: {
    async search(name) {
      const subcategories = await searchSubcategories({ name })
      this.options = subcategories.data
      this.loadingSearch = false
    },
    async submit() {
      this.loadingForm = true
      await setPriorityCategories({
        subcategories: this.priorityCategories.map(k => k.id),
      })
      this.$toast.global.success_message({
        message: this.$t('messages.success-change'),
      })
      this.changed = false
      this.loadingForm = false
    },
  },
}
</script>

<style lang="stylus">
@import "priority-categories-tab.styl";
</style>
