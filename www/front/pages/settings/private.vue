<template lang="pug">
  PrivateTab(:privacyOption="privacyOption")
</template>

<script>
import PrivateTab from '../../components/SettingsTabs/PrivateTab/PrivateTab.vue'
import { getPrivacyOptions } from '../../plugins/api/user'

export default {
  components: {
    PrivateTab,
  },
  async asyncData() {
    const [privacyOption] = await Promise.all([getPrivacyOptions()])
    privacyOption.data.forEach(k => {
      k.selectIsOpen = false
    })
    return {
      privacyOption: privacyOption.data,
    }
  },
}
</script>
