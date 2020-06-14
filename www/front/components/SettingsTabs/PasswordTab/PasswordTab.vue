<template lang="pug">
  SettingTabContainer
    template(slot="title") {{ $t('settings.password.title') }}
    .password-tab
      .password-tab__field-old-password
        SettingTabField(
          :label="$t('settings.password.inputs.old-password.label')"
          for-input="old-password"
        )
          PasswordInput(id="old-password" v-model="oldPassword.value")

      .password-tab__field-new-password
        SettingTabField(
          :label="$t('settings.password.inputs.new-password.label')"
          for-input="new-password"
        )
          PasswordInput(id="new-password" v-model="newPassword.value")

      SettingTabField
        Button {{ $t('settings.password.submit') }}

</template>

<script>
import SettingTabContainer from '../SettingTabContainer/SettingTabContainer.vue'
import SettingTabField from '../SettingTabField/SettingTabField.vue'
import PasswordInput from '../../Interactives/Inputs/PasswordInput/PasswordInput.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'

export default {
  components: {
    SettingTabContainer,
    SettingTabField,
    PasswordInput,
    Button,
  },
  data() {
    return {
      oldPassword: {
        value: '',
      },
      newPassword: {
        value: '',
      },
    }
  },
}
</script>

<style lang="stylus">
@import "password-tab.styl"
</style>
