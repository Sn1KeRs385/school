<template lang="pug">
  SettingTabContainer
    template(slot="title") {{ $t('settings.phone.title') }}
    .phone-tab__description
      | {{ $t('settings.phone.description', { phone: '+7 908 654-22-09' }) }}

    .phone-tab
      .phone-tab__field
        SettingTabField(
          :label="$t('settings.phone.inputs.phone.label')"
          for-input="phone"
        )
          .phone-tab__phone-input-container
            TextInput(id="phone" v-model="phone.value" placeholder="+7 908 654-22-09")
          .phone-tab__phone-verify
            .phone-tab__button-send-code
              Button(secondary)
                template(
                  slot="multiline-text"
                ) {{ $t('settings.phone.inputs.phone.confirm') }}
            .phone-tab__input-code
              TextInput(
                id="sms-code"
                :placeholder="$t('settings.phone.inputs.sms-code.placeholder')"
                disabled
              )
      SettingTabField
        Button {{ $t('settings.phone.submit') }}

</template>

<script>
import SettingTabContainer from '../SettingTabContainer/SettingTabContainer.vue'
import SettingTabField from '../SettingTabField/SettingTabField.vue'
import TextInput from '../../Interactives/Inputs/TextInput/TextInput.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'

export default {
  components: {
    SettingTabContainer,
    SettingTabField,
    TextInput,
    Button,
  },
  data() {
    return {
      phone: {
        value: '',
      },
    }
  },
}
</script>

<style lang="stylus">
@import "phone-tab.styl"
</style>
