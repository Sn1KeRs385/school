<template lang="pug">
  .system-achievements-card
    .system-achievements-card__back-to
      MobileBackTo(@click.native="goBackPage") {{ $t('system-progress.back-to') }}
    .system-achievements-card__content
      .system-achievements-card__header
        .system-achievements-card__header-section
          | {{ $t('system-progress.in-stripping') }}
          .system-achievements-card__header-settings
            GearSVG
          | {{ achievement.category_name }}
        .system-achievements-card__header-title {{ achievement.title }}
        .system-achievements-card__header-tags
          NuxtLink.system-achievements-card__header-tag(
            v-for="subcategory in achievement.subcategories"
            :key="subcategory.id"
            :to="localePath({ name: 'achievements-keyword-subcategory', params: { keyword: getCategory.keyword, subcategory: subcategory.id } })"
          ) {{ `${subcategory.name} ` }}
      .system-achievements-card__options
        .system-achievements-card__options-item-container
          .system-achievements-card__options-item
            .system-achievements-card__options-item-title {{ achievement.difficulties_point }}
            .system-achievements-card__options-item-text {{ $t('system-progress.points') }}

        .system-achievements-card__options-item-container
          .system-achievements-card__options-item
            .system-achievements-card__options-item-title {{ achievement.target_count }}
            .system-achievements-card__options-item-text {{ $t('system-progress.in-order-to') }}

        .system-achievements-card__options-item-container
          .system-achievements-card__options-item(@click="showUsersCompleteModal")
            .system-achievements-card__options-item-title {{ achievement.user_progresses_count }}
            .system-achievements-card__options-item-text.arrow {{ $t('system-progress.done') }}
      .system-achievements-card__item(v-if="achievement.description")
        .system-achievements-card__item-title {{ $t('system-progress.description') }}
        .system-achievements-card__item-text {{ achievement.description }}
      .system-achievements-card__item
        .system-achievements-card__item-title {{ $t('system-progress.conditions') }}
        .system-achievements-card__item-text {{ achievement.condition.title }}
      .system-achievements-card__make-goal
        Button(
          @click.native="asTarget"
          :loading="asTargetLoading"
          :disabled="targetExists"
        ) {{ getButtonTargetText }}
          template(slot="before")
            .system-achievements-card__make-goal-button-plus
              PlusSVG

</template>

<script>
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo'
import GearSVG from '../../SVG/GearSVG.vue'
import ArrowBoldSVG from '../../SVG/ArrowBoldSVG.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import PlusSVG from '../../SVG/PlusSVG.vue'
import ExclamationMarkSVG from '../../SVG/ExclamationMarkSVG.vue'
import SystemAchievementUsersComplete from '../../Modals/SystemAchievementUsersComplete/SystemAchievementUsersComplete.vue'
import { systemAchievementAsTarget } from '../../../plugins/api/progress'

export default {
  inject: ['setModal'],
  components: {
    MobileBackTo,
    GearSVG,
    ArrowBoldSVG,
    Button,
    PlusSVG,
    ExclamationMarkSVG,
  },
  props: {
    achievement: {
      required: true,
      type: Object,
    },
  },
  data() {
    return {
      asTargetLoading: false,
    }
  },
  computed: {
    getButtonTargetText() {
      if (this.targetExists) {
        return this.$t('system-progress.added-target')
      }
      return this.$t('system-progress.to-target')
    },
    targetExists: {
      get() {
        return this.achievement.target_exists
      },
      set(val) {
        this.achievement.target_exists = val
      },
    },
    getCategory() {
      return this.$store.state.progress.categories.find(k => k.id === this.achievement.category_id)
    },
  },
  methods: {
    goBackPage() {
      this.$router.back()
    },
    async asTarget() {
      if (this.targetExists) {
        return
      }
      this.achievement.target_count += 1
      this.targetExists = true
      await systemAchievementAsTarget(this.achievement.id)
    },
    showUsersCompleteModal() {
      if (!this.achievement.user_progresses_count) return
      this.setModal({
        component: SystemAchievementUsersComplete,
        props: {
          achievement: this.achievement,
        },
        events: {
          close: this.setModal,
        },
      })
    },
  },
}
</script>

<style lang="stylus">
@import "system-achievement-card.styl"
</style>
