<template>
  <div class="auth-layout">
    <div class="auth-layout__header">
      <NuxtLink to="/">
        <img src="../assets/image/logo.svg" alt="logotype" class="auth__logo" />
      </NuxtLink>
      <div
        class="auth-layout__language"
        ref="language"
        tabindex="0"
        @focus="toggleLangList()"
        @blur="toggleLangList()"
      >
        <div class="language__button">
          Rus
        </div>
        <div class="language__list" :class="{ language__list_open: langListIsOpen }">
          <div
            v-for="language in languages"
            :key="language.keyword"
            class="language__item"
            :class="{ language__item_select: language.keyword === selectedLang }"
            @click="selectLang(language.keyword)"
          >
            {{ language.name }}
          </div>
        </div>
      </div>
      <div class="auth-layout__button_guest">
        <NuxtLink to="/guest" class="link link_theme_aqua">
          Войти как гость
        </NuxtLink>
      </div>
    </div>
    <div class="auth-layout__content">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-8 col-xl-6 offset-md-2 offset-xl-3">
            <div class="auth-layout__form-container">
              <div class="auth-layout__form-selector">
                <div
                  class="auth-layout__select-item"
                  :class="{ 'auth-layout__select-item_active': selectTab === 'signIn' }"
                  @click="goTab('signIn')"
                >
                  Вход
                </div>
                <div
                  class="auth-layout__select-item"
                  :class="{ 'auth-layout__select-item_active': selectTab === 'signup' }"
                  @click="goTab('signup')"
                >
                  Регистрация
                </div>
                <div
                  class="auth-layout__select_border_aqua"
                  :class="{ 'auth-layout__select_border_aqua_right': selectTab === 'signup' }"
                />
              </div>
              <Nuxt keep-alive />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  components: {
  },
  data() {
    return {
      selectTab: this.$route.name,
      langListIsOpen: false,
      languages: [
        {
          name: 'Russian',
          keyword: 'ru',
        },
        {
          name: 'English',
          keyword: 'en',
        },
        {
          name: 'Chinese',
          keyword: 'ch',
        },
      ],
      selectedLang: 'ru',
    }
  },
  methods: {
    goTab(key) {
      this.selectTab = key
      this.$router.push(`/${key.toLowerCase()}`)
    },
    toggleLangList() {
      this.langListIsOpen = !this.langListIsOpen
    },
    selectLang(keyword) {
      this.selectedLang = keyword
    },
  },
}
</script>

<style src="../assets/style/auth-layout.styl" lang="stylus" scoped></style>
