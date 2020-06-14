<template lang="pug">
  SettingTabContainer
    template(slot="title") {{ $t('settings.personal.title') }}
    form(v-if="userData" @submit.prevent="submit").personal-tab
      .row.justify-content-xl-start.justify-content-center
        .col-xl.col-auto.offset-xl-5
          .personal-tab__avatar-container
            .personal-tab__avatar-remove(
              v-if="userData.avatar.id"
              @click="removeAvatar"
            )
              TagCloseSVG
            label.personal-tab__avatar-input-label(
              :style="{'background-image': `url(${userData.avatar.url})`}"
            )
              input(
                accept="image/x-png, image/jpeg, image/*"
                type="file"
                @change="changeAvatar"
              )
        .col-12
          .row.justify-content-center
            ValidationList(:validations="avatar.validations")

      .personal-tab__field
        SettingTabField(
          :label="$t('settings.personal.inputs.first-name.label')"
          for-input="firstName"
        )
          TextInput(
            id="firstName"
            v-model="userData.firstname"
            :placeholder="$t('settings.personal.inputs.first-name.placeholder')"
            :is-valid="firstNameIsValid"
            :rules="firstName.rules"
            :validations.sync="firstName.validations"
          )

      .personal-tab__field
        SettingTabField(
          :label="$t('settings.personal.inputs.sur-name.label')"
          for-input="surName"
        )
          TextInput(
            id="surName"
            v-model="userData.surname"
            :placeholder="$t('settings.personal.inputs.sur-name.placeholder')"
            :is-valid="surNameIsValid"
            :rules="surName.rules"
            :validations.sync="surName.validations"
          )

      .personal-tab__field
        SettingTabField(
          :label="$t('settings.personal.inputs.email.label')"
          for-input="email"
        )
          TextInput(
            id="email"
            v-model="userData.email"
            :placeholder="$t('settings.personal.inputs.email.placeholder')"
            :is-valid="emailIsValid"
            :rules="email.rules"
            :validations.sync="email.validations"
          )

      .personal-tab__field
        SettingTabField(
          :label="$t('settings.personal.inputs.date.label')"
          for-input="dateOfBirth"
        )
          DateSelect(
            id="date-of-birth"
            :date.sync="userAge"
            :is-valid="dateIsValid"
            :rules="date.rules"
            :validations.sync="date.validations"
          )
      .personal-tab__field
        SettingTabField(
          :label="$t('settings.personal.inputs.country.label')"
          for-input="country"
        )
          TextInput(
            id="country"
            :placeholder="$t('settings.personal.inputs.country.placeholder')"
            :disabled="true"
            :editable="false"
          )

      .personal-tab__field
        SettingTabField(
          :label="$t('settings.personal.inputs.city.label')"
          for-input="city"
        )
          CityInput(
            id="city"
            :placeholder="$t('settings.personal.inputs.city.placeholder')"
            :is-open.sync="city.isOpen"
            :search.sync="city.search"
            :cities.sync="city.options"
            :city.sync="city.value"
          )

      .personal-tab__field
        SettingTabField(
          :label="$t('settings.personal.inputs.about-me.label')"
          for-input="aboutSelf"
        )
          .personal-tab__field-about-self
            TextArea(
              id="aboutSelf"
              :placeholder="$t('settings.personal.inputs.about-me.placeholder')"
              v-model="userData.about"
            )

      .personal-tab__field
        SettingTabField(
          :label="$t('settings.personal.inputs.gender.label')"
          for-input="male"
        )
          MultiselectorID(
            id="male"
            :value.sync="userData.male"
            :options="$t('settings.personal.inputs.gender.options')"
            :is-open.sync="maleIsOpen"
          )

      .personal-tab__field-socials
        SettingTabField(
          :label="$t('settings.personal.inputs.socials.label')"
        )
          .personal-tab__socials(
            v-for="social in userData.social_networks"
            :key="social.key"
          )
            .personal-tab__socials-logo
              MultiselectorID(
                :id="`social-img-${social.key}`"
                :value.sync="social.id"
                :options="socials"
                :is-open.sync="social.selectIsOpen"
                :min="true"
              )
                template(slot="value" slot-scope="{ opt }")
                  .personal-tab__socials-logo-img
                    img(:src="opt.icon")
                template(slot="item" slot-scope="{ opt }")
                  .personal-tab__socials-logo-img
                    img(:src="opt.icon")
            .personal-tab__socials-login
              TextInput(
                :id="`social-id=${social.key}`"
                v-model="social.identificator"
                :placeholder="socialById(social.id)"
              )
            button(type="button").personal-tab__socials-remove(@click="removeSocial(social.key)")
          .personal-tab__socials-add
            Button(
              secondary
              @click.native="makeSocial"
              min-height="100%"
            ) {{ $t('settings.personal.inputs.socials.add') }}

      .personal-tab__submit-container
        SettingTabField
          Button(
            :submit="formIsValid"
            :disabled="!formIsValid || !formChanged"
          ) {{ $t('settings.personal.submit') }}

</template>

<script>
import { DateValue } from '../../../assets/js/helper-classes'
import { setUserEditInfo, removeUserAvatar } from '../../../plugins/api/user'
import SettingTabContainer from '../SettingTabContainer/SettingTabContainer.vue'
import SettingTabField from '../SettingTabField/SettingTabField.vue'
import TextInput from '../../Interactives/Inputs/TextInput/TextInput.vue'
import DateSelect from '../../Interactives/Inputs/DateSelect/DateSelect.vue'
import CheckBox from '../../Interactives/Inputs/CheckBox/CheckBox.vue'
import TextArea from '../../Interactives/Inputs/TextArea/TextArea.vue'
import Multiselector from '../../Interactives/Inputs/Multiselector/Multiselector.vue'
import MultiselectorID from '../../Interactives/Inputs/Multiselector/MultiselectorID.vue'
import SocialNames from '../../../static/social-names.json'
import Button from '../../Interactives/Controls/Button/Button.vue'
import CropAvatarModal from '../../Modals/CropAvatarModal/CropAvatarModal.vue'
import TagCloseSVG from '../../SVG/CloseTagSVG.vue'
import { RequiredRule, EmailRule, FullDateRule } from '../../../assets/js/validation-rules'
import ValidationList from '../../Interactives/Inputs/ValidationList/ValidationList.vue'
import CityInput from '../../Interactives/Inputs/CityInput/CityInput.vue'
import SuccessText from '../../General/SuccessText/SuccessText.vue'

export default {
  inject: ['setModal'],
  components: {
    ValidationList,
    SettingTabContainer,
    SettingTabField,
    TextInput,
    DateSelect,
    CheckBox,
    TextArea,
    Button,
    Multiselector,
    MultiselectorID,
    TagCloseSVG,
    CityInput,
    SuccessText,
  },
  props: {
    socials: {
      required: true,
      type: Array,
    },
    userData: {
      required: true,
      type: Object,
    },
  },
  data() {
    const { userData } = this
    const { city } = userData
    const $t = this.$t.bind(this)
    let userAge = null
    if (userData.borned_at) {
      const [year, month, day] = userData.borned_at.split('-')
      userAge = new DateValue({ year, month, day })
    } else {
      userAge = new DateValue()
    }
    return {
      avatar: {
        value: null,
        validations: [],
        maxSize: 1024 * 1024 * 15,
      },
      countSocials: 100,
      firstName: {
        rules: [new RequiredRule($t('settings.personal.inputs.first-name.messages.required'))],
        validations: [],
      },
      surName: {
        rules: [new RequiredRule($t('settings.personal.inputs.sur-name.messages.required'))],
        validations: [],
      },
      email: {
        rules: [
          new RequiredRule($t('settings.personal.inputs.email.messages.required')),
          new EmailRule($t('settings.personal.inputs.email.messages.email')),
        ],
        validations: [],
      },
      date: {
        rules: [new FullDateRule($t('settings.personal.inputs.date.messages.full'))],
        validations: [],
      },
      city: {
        value: city,
        search: city ? city.name : '',
        options: [],
        loading: false,
        isOpen: false,
      },
      showInProfile: {
        value: true,
      },
      maleIsOpen: false,
      loading: false,
      userAge,
      formChanged: false,
    }
  },
  computed: {
    firstNameIsValid() {
      return !this.firstName.validations.filter(k => !k.isValid).length
    },
    surNameIsValid() {
      return !this.surName.validations.filter(k => !k.isValid).length
    },
    emailIsValid() {
      return !this.email.validations.filter(k => !k.isValid).length
    },
    dateIsValid() {
      return !this.date.validations.filter(k => !k.isValid).length
    },
    formIsValid() {
      return this.firstNameIsValid && this.surNameIsValid && this.emailIsValid && this.dateIsValid
    },
    userAgeIsFull() {
      return (
        (this.userAge.day === undefined || this.userAge.day) &&
        (this.userAge.month === undefined || this.userAge.month) &&
        (this.userAge.year === undefined || this.userAge.year)
      )
    },
    userAgeFormat() {
      return `${this.userAge.year}-${this.userAge.month}-${this.userAge.day}`
    },
    getCityValue() {
      return this.city.value
    },
  },
  watch: {
    userData: {
      deep: true,
      handler() {
        this.formChanged = true
      },
    },
    userAge: {
      deep: true,
      handler() {
        this.formChanged = true
      },
    },
    getCityValue: {
      deep: true,
      handler() {
        this.formChanged = true
      },
    },
  },
  methods: {
    async removeAvatar() {
      const { data } = await removeUserAvatar(this.userData.avatar.id)
      this.userData.avatar = { url: data.url }
      this.$store.commit('auth/attachUser', { avatar_url: data.url })
    },
    async submit() {
      setTimeout(async () => {
        this.userData.social_networks = this.userData.social_networks.filter(k => {
          return k.id && k.identificator
        })
        if (!this.formIsValid) return
        this.loading = true
        if (this.userAgeIsFull) {
          this.userData.borned_at = this.userAgeFormat
        } else {
          delete this.userData.borned_at
        }
        if (this.city.value) {
          this.userData.city_id = this.city.value.id
        } else {
          delete this.userData.city_id
        }
        await setUserEditInfo({
          ...this.userData,
          social_networks: this.userData.social_networks.map(k => ({
            social_id: k.id,
            identificator: k.identificator,
          })),
          city: undefined,
        })
        this.loading = false
        this.$store.commit('auth/setUser', {
          ...this.$store.state.auth.user,
          firstname: this.userData.firstname,
          surname: this.userData.surname,
        })
        this.$toast.global.success_message({
          message: this.$t('messages.success-change'),
        })
        this.formChanged = false
      })
    },
    changeSocialSelectIsOpen(social, isOpen) {
      social.selectIsOpen = isOpen
    },
    makeSocial() {
      this.countSocials += 1
      this.userData.social_networks.push({
        id: 1,
        key: this.countSocials,
        keyword: SocialNames.FACEBOOK,
        identificator: null,
        selectIsOpen: false,
      })
    },
    removeSocial(key) {
      const index = this.userData.social_networks.findIndex(k => k.key === key)
      this.userData.social_networks.splice(index, 1)
    },
    socialById(socialId) {
      return this.socials.find(k => k.id === socialId).name
    },
    formatBytes(bytes, decimals = 2) {
      const sizes = this.$t('file-input.sizes')
      if (bytes === 0) return `0 ${sizes[0]}`
      const k = 1024
      const dm = decimals < 0 ? 0 : decimals
      const i = Math.floor(Math.log(bytes) / Math.log(k))
      return `${parseFloat((bytes / k ** i).toFixed(dm))} ${sizes[i]}`
    },
    changeAvatar({ target }) {
      this.avatar.validations = []
      if (target.files && target.files[0]) {
        if (target.files[0].size > this.avatar.maxSize) {
          this.avatar.validations.push({
            isValid: false,
            name: 'avatar-max-size',
            message: this.$t('settings.personal.inputs.avatar.messages.size', {
              size: this.formatBytes(this.avatar.maxSize),
            }),
          })
          return
        }
        const reader = new FileReader()
        reader.onload = e => {
          this.avatar.value = e.target.result
          this.showCropAvatarModal(e.target.result, target.files[0])
          target.type = ''
          target.type = 'file'
        }
        reader.readAsDataURL(target.files[0])
      }
    },
    showCropAvatarModal(avatar, file) {
      this.setModal({
        component: CropAvatarModal,
        props: {
          avatar,
          file,
        },
        events: {
          close: this.setModal,
          changeAvatar: newAvatar => {
            this.userData.avatar = newAvatar
            this.$store.commit('auth/attachUser', { avatar_url: newAvatar.url })
          },
        },
      })
    },
  },
}
</script>

<style lang="stylus">
@import "personal-tab.styl"
</style>
