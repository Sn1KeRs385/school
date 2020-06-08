<template lang="pug">
  PersonalTab(:socials="socials" :userData="userData")
</template>

<script>
import PersonalTab from '../../components/SettingsTabs/PersonalTab/PersonalTab.vue'
import Socials from '../../plugins/api/socials'
import { getUserEditInfo } from '../../plugins/api/user'

export default {
  components: {
    PersonalTab,
  },
  async asyncData({ store, redirect }) {
    if (!store.getters['auth/authorized']) {
      return redirect('/signin')
    }
    const [socials, userData] = await Promise.all([Socials(), getUserEditInfo()])
    userData.data.social_networks.forEach((k, i) => {
      k.selectIsOpen = false
      k.key = i
    })
    return {
      socials: socials.data,
      userData: userData.data,
    }
  },
}
</script>
