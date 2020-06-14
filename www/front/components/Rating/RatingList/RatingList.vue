<template lang="pug">
  .rating-list(
    :class="{'rating-list--no-result': listIsEmpty}"
  )
    .rating-list__loader-container(v-if="loading")
      .rating-list__loader
        LoaderRing(size="50px" weight="4px")

    .rating-list__header.d-lg-flex.d-none
      .rating-list__header-button-mode(
        :class="{ 'rating-list__header-button-mode--me': showMe }"
      )
        ToolTip(
          v-if="!ratingPageShowed"
          :content="getSettingsHints[0]"
          :hint-number="-1"
          class="tool-tip__mark-container-header tool-tip__mark-container-show-rating"
        )
        Button(
          :secondary="showMe"
          @click.native="changeList(!showMe)"
          :class="showRatingButtonClasses"
        ) {{ $t(`rating.show-${showMe ? 'others' : 'me'}`) }}

      .rating-list__header-params
        ToolTip(
          v-if="!ratingPageShowed"
          :content="getSettingsHints[1]"
          :hint-number="1"
          class="tool-tip__mark-container-header tool-tip__mark-container-likes-rating"
        )
        .rating-list__header-sort-by(
          :class="ratingSortClasses"
          @click="changeList(showMe, !sortLikes)"
        )
          .rating-list__header-sort-by-icon
            SortArrowSVG
          span(v-if="!sortLikes") {{ $t('rating.by-likes') }}
          span(v-else) {{ $t('rating.by-points') }}
    .rating-list__header-button-mode.d-lg-none.d-flex(
      :class="{ 'rating-list__header-button-mode--me': !showMe }"
    )
      ToolTip(
        v-if="!ratingPageShowed"
        :content="getSettingsHints[0]"
        :hint-number="-1"
        class="tool-tip__mark-container-header tool-tip__mark-container-show-rating"
      )
      Button(
        :secondary="showMe"
        @click.native="changeList(!showMe)"
      ) {{ $t(`rating.show-${showMe ? 'others' : 'me'}`) }}
    .rating-list__header-params.d-lg-none.d-flex
      .rating-list__header-settings.d-lg-none.d-flex(
        @click="showSettings"
        :class="settingsClasses"
      )
        .rating-list__header-settings-icon
          RatingSettingsSVG
        | {{ $t('rating.settings-title') }}
      ToolTip(
        v-if="!ratingPageShowed"
        :content="getSettingsHints[2]"
        :hint-number="0"
        class="tool-tip__mark-container-header tool-tip__mark-container-settings-rating"
      )

      .rating-list__header-sort-by(
        :class="ratingSortClasses"
        @click="changeList(showMe, !sortLikes)"
      )
        .rating-list__header-sort-by-icon
          SortArrowSVG
        span(v-if="!sortLikes") {{ $t('rating.by-likes') }}
        span(v-else) {{ $t('rating.by-points') }}
      ToolTip(
        v-if="!ratingPageShowed"
        :content="getSettingsHints[1]"
        :hint-number="1"
        class="tool-tip__mark-container-header tool-tip__mark-container-likes-rating"
      )

    .rating-list__list
      .rating-list__no-result(
        v-if="listIsEmpty"
      ) {{ $t('rating.no-result') }}
      NuxtLink(
        v-for="user in ratingList.items"
        :key="user.id"
        class="rating-list__list-item"
        :class="{'rating-list__list-item--current': user.id === getUser.id}"
        :to="localePath({ name: 'id-index-achievements', params: { id: user.id } })"
      )
        MinCardUser(
          :current-user="user"
          :show-likes="sortLikes"
          :show-rating="!sortLikes"
        )
          template(slot="after")
            .rating-list__list-item-number(
              :class="{ 'rating-list__list-item-number--current': user.id === getUser.id }"
            ) {{ user.increment }}

</template>

<script>
import Button from '../../Interactives/Controls/Button/Button.vue'
import SortArrowSVG from '../../SVG/SortArrowSVG.vue'
import MinCardUser from '../../MinCardUser/MinCardUser.vue'
import LoaderRing from '../../General/LoaderRing.vue'
import RatingSettingsSVG from '../../SVG/RatingSettingsSVG.vue'
import RatingSettingsModal from '../../Modals/RatingSettingsModal/RatingSettingsModal.vue'
import { getRating } from '../../../plugins/api/rating'
import ToolTip from '../../General/ToolTip/ToolTip.vue'
import { userSendHint } from '../../../plugins/api/user'

export default {
  inject: ['setModal'],
  components: {
    ToolTip,
    MinCardUser,
    Button,
    SortArrowSVG,
    LoaderRing,
    RatingSettingsSVG,
    RatingSettingsModal,
  },
  props: {
    ratingList: {
      required: true,
      type: Object,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    sortLikes: {
      required: true,
      type: Boolean,
    },
    settings: {
      required: true,
      type: Object,
    },
    ratingPageShowed: {
      type: Boolean,
      default: null,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      showMe: false,
      ratingSettingsModal: {
        component: RatingSettingsModal,
        props: {
          settings: this.settings,
          loading: false,
        },
        events: {
          close: this.setModal,
          loadingChange: loading => {
            this.ratingSettingsModal.props.loading = loading
          },
          changeRatingList: ratingList => this.$emit('changeRatingList', ratingList),
        },
      },
    }
  },
  computed: {
    getUser() {
      return this.$store.state.auth.user
    },
    listIsEmpty() {
      return !this.ratingList.meta.total
    },
    getSettingsHints() {
      return this.$t('settings.hints')
    },
    getNumberOfHint() {
      return this.$store.state.numberOfHint
    },
    showRatingButtonClasses() {
      return { 'tool-active': this.getNumberOfHint === -1 && !this.ratingPageShowed}
    },
    ratingSortClasses() {
      return {
        'rating-list__header-sort-by--checked':
          this.sortLikes || (this.getNumberOfHint === 1 && !this.ratingPageShowed),
      }
    },
    settingsClasses() {
      return { 'tool-active': this.getNumberOfHint === 0 && !this.ratingPageShowed }
    },
  },
  methods: {
    async changeList(mode, sortLikes = this.sortLikes) {
      window.scrollTo({ top: 0 })
      this.$emit('loadingChange', true)
      this.$emit('update:sort-likes', sortLikes)
      this.showMe = mode
      const result = await getRating({
        offset_rnk: 0,
        direction: '>',
        records: this.ratingList.meta.records,
        show_me: Number(this.showMe),
        sort_likes: Number(sortLikes),
      })
      this.$emit('changeRatingList', result.data)
      this.$emit('loadingChange', false)
    },
    showSettings() {
      this.setModal(this.ratingSettingsModal)
    },
  },
}
</script>

<style lang="stylus">
@import "rating-list.styl"
</style>
