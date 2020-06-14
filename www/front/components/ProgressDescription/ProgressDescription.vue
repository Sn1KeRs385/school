<template lang="pug">
  .progress-description 
    .progress-description__heading {{ $t('progress-description.heading') }}
    .progress-description__title {{ $t('progress-description.category-title') }}
    .progress-description__text {{ progress.category.name }}
    .progress-description__title {{ $t('progress-description.description-title') }}
    .progress-description__text {{ getProgressDescription }}
    .progress-description__title {{ $t('progress-description.conditions-title') }}
    .progress-description__text {{ progress.condition.title }}
    .progress-description__title {{ $t('progress-description.subcategories-title') }}
    .progress-description__tags
      Tag(
        v-for="subcategory in progress.subcategories"
        :key="subcategory.id"
      ) {{ subcategory.name }}
    .progress-description__title {{ $t('progress-description.difficulty-title') }}
    .progress-description__text {{ progress.difficulty.name }}
    .progress-description__title {{ $t('progress-description.placement-title') }}
    .progress-description__text {{ getRequiredGeo }}
    NuxtLink(
     class="progress-description__link"
     :to="localePath({ name: 'achievement-id', params: { id: progress.id } })"
    ) {{ $t('progress-description.go-to-card') }}
      .progress-description__link-arrow
        ArrowBoldSVG
</template>

<script>
import ArrowBoldSVG from '../SVG/ArrowBoldSVG.vue'
import Tag from '../General/Tag/Tag.vue'

export default {
  components: {
    ArrowBoldSVG,
    Tag,
  },
  props: {
    progress: {
      required: true,
      type: Object,
    },
  },
  computed: {
    // eslint-disable-next-line vue/return-in-computed-property
    getProgressDescription() {
      return this.progress.description
        ? this.progress.description
        : this.$t('progress-description.default')
    },
    getRequiredGeo() {
      return this.progress.required_geo
        ? this.progress.required_geo
        : this.$t('progress-description.default')
    },
  },
}
</script>

<style lang="stylus">
@import "progress-description.styl"
</style>
