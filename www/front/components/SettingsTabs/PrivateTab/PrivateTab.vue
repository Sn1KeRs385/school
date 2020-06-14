<template lang="pug">
  SettingTabContainer
    template(slot="title") {{ $t('settings.privacy.title') }}
    form.private-tab(v-if="privacyOption" @submit.prevent="submit")
      .private-tab__field(
        v-for="option in privacyOption"
        :key="option.group_keyword"
      )
        SettingTabField(
          :for-input="option.group_keyword"
          :label="option.group"
        )
          MultiselectorID(
            :id="option.group_keyword"
            :value.sync="option.selected_id"
            :options="option.values"
            :is-open.sync="option.selectIsOpen"
            :min="true"
          )
      SettingTabField
        Button(:submit="true" :disabled="!form.changed") {{ $t('settings.privacy.submit') }}
</template>

<script>
import SettingTabContainer from '../SettingTabContainer/SettingTabContainer.vue'
import SettingTabField from '../SettingTabField/SettingTabField.vue'
import MultiselectorID from '../../Interactives/Inputs/Multiselector/MultiselectorID.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import { setPrivacy } from '../../../plugins/api/user'

export default {
  components: {
    SettingTabContainer,
    SettingTabField,
    MultiselectorID,
    Button,
  },
  props: {
    privacyOption: {
      required: true,
      type: Array,
    },
  },
  data() {
    return {
      form: {
        changed: false,
      },
    }
  },
  computed: {
    getOptValues() {
      return this.privacyOption.map(opt => opt.selected_id)
    },
  },
  watch: {
    getOptValues: {
      handler() {
        this.form.changed = true
      },
    },
  },
  methods: {
    async submit() {
      const privacy = this.privacyOption.map(k => ({
        section_id: k.group_id,
        value_id: k.selected_id,
      }))
      await setPrivacy({ privacy })
      this.$toast.global.success_message({
        message: this.$t('messages.success-change'),
      })
      this.form.changed = false
    },
  },
}
</script>

<style lang="stylus">
@import "private-tab.styl"
</style>
