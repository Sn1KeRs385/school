<template lang="pug">
  .achievements-page
    .container.achievements-page__container
      .row.no-gutters
        .col-lg.col-12
          NuxtLink(
            v-if="!isParentPage"
            class="d-lg-none d-block"
            :to="localePath({ name: 'achievements' })"
          )
            MobileBackTo {{ $t('achievements.back-to') }}
          NuxtChild(:tab.sync="tab" :hints-info="hintsInfo")
        .col-lg-auto.col-12.order-lg-1.order-first.d-lg-block(
          :class="{'d-none': !isParentPage}"
        )
          .achievements-page__menu
            PageNavigationMenuLink(
              v-for="category in getCategories"
              :key="category.id"
              :to="localePath({ name: 'achievements-keyword', params: { keyword: category.keyword } })"
            ) {{ category.name }}
              template(slot="icon")
                img(:src="category.icon")

</template>
<script>
import PageNavigationMenuLink from '../NavMenuLink/PageNavigationMenuLink.vue'
import HealthSVG from '../SVG/HealthSVG.vue'
import FeelingSVG from '../SVG/FeelingSVG.vue'
import RelationsSVG from '../SVG/RelationsSVG.vue'
import SkillsSVG from '../SVG/SkillsSVG.vue'
import MobileBackTo from '../General/MobileBackTo/MobileBackTo.vue'

export default {
  components: {
    PageNavigationMenuLink,
    HealthSVG,
    FeelingSVG,
    RelationsSVG,
    SkillsSVG,
    MobileBackTo,
  },
  props: {
    hintsInfo: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      tab: 'left',
    }
  },
  computed: {
    isParentPage() {
      return `achievements___${this.$i18n.locale}` === this.$route.name
    },
    getCategories() {
      return this.$store.state.progress.categories
    },
  },
}
</script>

<style lang="stylus">
@import "achievements-page.styl"
</style>
