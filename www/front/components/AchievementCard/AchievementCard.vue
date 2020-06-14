<template lang="pug">
  .achievement-card(ref="root" :style="{ margin: margin }")
    .achievement-card__header
      NuxtLink(
        class="achievement-card__header-user-card"
        :to="localePath({ name: 'id-index-achievements', params: { id: localAchievement.user.id } })"
      )
        MinCardUser(:current-user="getUnionUser" :with-arrow="false")
    .achievement-card__body
      slot(name="linkToAchievement")
        NuxtLink(
          ref="linkToCard"
          class="achievement-card__title"
          :to="localePath({ name: 'id-achievement-achievementId', params: { id: localAchievement.user.id, achievementId: achievement.id } })"
        ) {{ achievement.title }}
      .achievement-card__title-section
        span.achievement-card__title-section-geo(
          v-if="achievement.geo"
        ) {{ achievement.geo }}
        .achievement-card__title-settings
          img(:src="getCategoryIcon")
        span {{ localAchievement.category_name }}
      .achievement-card__media-info(v-if="isMediaLoading") {{ getMediaLoading }}
      client-only
        .achievement-card__media(v-if="getMediaCollection.length")
          Carousel(
            ref="mediaCarousel"
            :loop="true"
            :per-page="1"
            :adjustable-height="false"
            :mouse-drag="true"
            :navigation-enabled="true"
            :navigationPrevLabel="getPrevSliderIcon"
            :navigationNextLabel="getNextSliderIcon"
            @pageChange="currentSlide = $event"
          )
            template(slot="pagination")
              .achievement-card__media-pagination(v-if="mediaHasTwoOrMore")
                .achievement-card__media-pagination-elem-container(
                  v-for="i in getMediaCollection.length"
                  @click="carouselGoToPage(i - 1)"
                )
                  .achievement-card__media-pagination-elem(
                    :class="getPaginationElemClasses(i)"
                  )
            client-only
              Slide(v-for="media in getMediaCollection" :key="media.id")
                .achievement-card__media-container
                  <!--                  .achievement-card__media-mask(v-if="mediaHasTwoOrMore")-->
                  .achievement-card__image(v-show="media.type === 'image'")
                    img.d-block(:src="media.url")
                  .achievement-card__video(v-show="media.type === 'video'")
                    video.d-block(:src="media.url" controls @click.prevent)
      .achievement-card__description {{ getDescription }}
        span.achievement-card__description-show-all(
          v-show="!showFullDescription"
          @click="showFullDescription = true"
        ) ещё
        .achievement-card__description-subcategories(v-if="showFullDescription")
          NuxtLink(
            class="achievement-card__description-subcategory"
            v-for="subcategory in localAchievement.subcategories"
            :key="subcategory.id"
            :to="localePath({ name: 'achievements-keyword-subcategory', params: { keyword: getCategory.keyword, subcategory: subcategory.id } })"
          ) {{ subcategory.name }}
        AchievementDescription(v-if="withDescription" :achievement="localAchievement")
      .achievement-card__info
        .achievement-card__info-time
          TimeAgo(:date-time="localAchievement.moderate_at" :timeout="10000")
        .achievement-card__info-points
          StarAchievementCardSVG
        | {{ localAchievement.difficulty_point }}
        .achievement-card__info-likes
          LikeSVG
        | {{ localAchievement.likes_count }}
      .achievement-card__footer
        .achievement-card__footer-item
          .achievement-card__footer-item-content(
            :class="{ 'like': hasLike }"
            @click="changeLike"
          )
            LikeAchievementCardSVG
            .achievement-card__footer-item-text {{ $t('progress-card.likes') }}
        .achievement-card__footer-item
          .achievement-card__footer-item-content(
            @click="showShareModal"
          )
            RepostAchievementCardSVG
            .achievement-card__footer-item-text {{ $t('progress-card.share') }}
        .achievement-card__footer-item(
          v-if="!localAchievement.show_edit"
          :class="fakeButtonClasses"
        )
          ToolTip(
            v-if="showHints"
            :content="getProfileCardHints[5]"
            :hint-number="0"
            class="tool-tip__mark-container-header tool-tip__mark-container-profile-fake"
          )
          .achievement-card__footer-item-content(
            :class="{ 'disabled': isFake }"
            @click="setFake"
          )
            FakeAchievementCardSVG
            .achievement-card__footer-item-text {{ $t('progress-card.fake') }}
        .achievement-card__footer-item(
          v-if="!localAchievement.show_delete"
          :class="targetButtonClasses"
        )
          ToolTip(
            v-if="showHints"
            :content="getProfileCardHints[4]"
            :hint-number="1"
            class="tool-tip__mark-container-header tool-tip__mark-container-profile-like"
          )
          .achievement-card__footer-item-content(
            :class="{ 'disabled': !asTarget }"
            @click="toTarget"
          )
            PlusAchievementCardSVG
            .achievement-card__footer-item-text {{ getIsTargetText }}
        .achievement-card__footer-item(v-if="localAchievement.show_edit")
          .achievement-card__footer-item-content(
            @click="showEditModal"
          )
            EditAchievementCardSVG
            .achievement-card__footer-item-text {{ $t('progress-card.edit') }}
        .achievement-card__footer-item(v-if="localAchievement.show_delete")
          .achievement-card__remove-question
            ProgressRemoveQuestion(
              :visible.sync="removeVisible"
              :title="$t('remove-progress.achievements.title')"
              @resolve="removeAchievement"
              @reject="removeVisible = false"
            )
          .achievement-card__footer-item-content(
            @click="removeVisible = true"
          )
            RemoveAchievementCardSVG
            .achievement-card__footer-item-text {{ $t('progress-card.remove') }}
</template>

<script>
import cloneDeep from 'lodash.clonedeep'
import MinCardUser from '../MinCardUser/MinCardUser.vue'
import WrapperSVG from '../SVG/WrapperSVG.vue'
import StarAchievementCardSVG from '../SVG/StarAchievementCardSVG.vue'
import LikeSVG from '../SVG/LikesSVG.vue'
import LikeAchievementCardSVG from '../SVG/LikeAchievementCardSVG.vue'
import RepostAchievementCardSVG from '../SVG/RepostAchievementCardSVG.vue'
import FakeAchievementCardSVG from '../SVG/FakeAchievementCardSVG.vue'
import PlusAchievementCardSVG from '../SVG/PlusAchievementCardSVG.vue'
import EditAchievementCardSVG from '../SVG/EditAchievementCardSVG.vue'
import RemoveAchievementCardSVG from '../SVG/RemoveAchievementCardSVG.vue'
import AchievementDescription from '../ProgressDescription/ProgressDescription.vue'
import TimeAgo from '../General/TimeAgo.vue'
import ShareModal from '../Modals/ShareModal/ShareModal.vue'
import ToolTip from '../General/ToolTip/ToolTip.vue'
import AchievementEditModal from '../Modals/AchievementEditModal/AchievementEditModal.vue'
import {
  progressLike,
  achievementAsTarget,
  achievementIsFake,
  progressView,
  showUserAchievement,
  // deleteAchievement,
} from '../../plugins/api/progress'
import 'hooper/dist/hooper.css'
import ProgressRemoveQuestion from '../ProgressRemoveQuestion/ProgressRemoveQuestion.vue'

export default {
  inject: ['setModal', 'getProfileCardHints'],
  components: {
    MinCardUser,
    StarAchievementCardSVG,
    LikeSVG,
    LikeAchievementCardSVG,
    RepostAchievementCardSVG,
    FakeAchievementCardSVG,
    PlusAchievementCardSVG,
    EditAchievementCardSVG,
    RemoveAchievementCardSVG,
    AchievementDescription,
    TimeAgo,
    ToolTip,
    WrapperSVG,
    ProgressRemoveQuestion,
  },
  props: {
    currentUser: {
      type: Object,
      default: null,
    },
    withDescription: {
      type: Boolean,
      default: false,
    },
    achievement: {
      required: true,
      type: Object,
    },
    margin: {
      type: String,
      default: '0',
    },
    showHints: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    const { achievement } = this
    this.$t.bind(this)
    return {
      localAchievement: cloneDeep(achievement),
      loadingLike: false,
      isTryingMediaLoad: false,
      showFullDescription: achievement.comment.length <= 200,
      currentSlide: 0,
      removeVisible: false,
      owner: {
        id: this.achievement.id,
        refresh: this.refreshAchievement,
      },
    }
  },
  computed: {
    // Vue-carousel Принимает только String (даже не передать component или url)
    // Для параметров labels, поэтому приходиться так передавать
    getPrevSliderIcon() {
      return `<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle opacity="1" r="29.5" transform="matrix(-1 0 0 1 30 30)" stroke="#08a8a1"/>
        <path d="M36 15L21 30L36 45" stroke="#08a8a1" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      `
    },
    getNextSliderIcon() {
      return `<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle opacity="1" cx="30" cy="30" r="29.5" stroke="#08a8a1"/>
        <path d="M24 15L39 30L24 45" stroke="#08a8a1" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      `
    },
    getUnionUser() {
      return {
        ...this.localAchievement.user,
        ...this.currentUser,
      }
    },
    hasView: {
      get() {
        return this.localAchievement.has_view
      },
      set(val) {
        this.localAchievement.has_view = val
      },
    },
    hasLike: {
      get() {
        return this.localAchievement.has_like
      },
      set(val) {
        this.localAchievement.has_like = val
      },
    },
    asTarget: {
      get() {
        return this.localAchievement.show_target
      },
      set(val) {
        this.localAchievement.show_target = val
      },
    },
    isFake: {
      get() {
        return this.localAchievement.has_fake
      },
      set(val) {
        this.localAchievement.has_fake = val
      },
    },
    getRoot() {
      return this.$refs.root
    },
    getIsTargetText() {
      if (this.asTarget) {
        return this.$t('progress-card.to-target')
      }
      return this.$t('progress-card.already-target')
    },
    isMediaLoading() {
      return (
        this.achievement.media_not_finished_count !== 0 &&
        this.achievement.media_not_finished_count !== undefined
      )
    },
    getMediaLoading() {
      return this.$t('progress-card.media-loading', {
        files: this.achievement.media_not_finished_count,
      })
    },
    getDescription() {
      return this.showFullDescription
        ? this.localAchievement.comment
        : `${this.localAchievement.comment.substring(0, 155)}...`
    },
    getUser() {
      return this.$store.state.auth.user
    },
    getLink() {
      return `${window.location.origin}${this.$refs.linkToCard.to}`
    },
    getImages() {
      return this.localAchievement.progress_files.progress_image || []
    },
    getVideos() {
      return this.localAchievement.progress_files.progress_video || []
    },
    getMediaCollection() {
      const collection = []
      this.getImages.forEach(image => {
        collection.push({ type: 'image', ...image })
      })
      this.getVideos.forEach(video => {
        collection.push({ type: 'video', ...video })
      })
      collection.sort((l, r) => {
        if (l.id > r.id) return 1
        if (l.id < r.id) return -1
        return 0
      })
      return collection
    },
    getMediaCarousel() {
      return this.$refs.mediaCarousel
    },
    getCategoryIcon() {
      return this.localAchievement.category_icon
    },
    getCategory() {
      return this.$store.state.progress.categories.find(
        k => k.id === this.localAchievement.category.id,
      )
    },
    mediaHasTwoOrMore() {
      return this.getMediaCollection.length > 1
    },
    getNumberOfHint() {
      return this.$store.state.numberOfHint
    },
    canDeleteAchievement() {
      return this.localAchievement.show_delete
    },
    fakeButtonClasses() {
      return {
        'tool-active':
          this.getNumberOfHint === 0 && !this.localAchievement.show_edit && this.showHints,
      }
    },
    targetButtonClasses() {
      return {
        'tool-active':
          this.getNumberOfHint === 1 && !this.localAchievement.show_delete && this.showHints,
      }
    },
  },
  watch: {
    getMediaCollection(collection) {
      if (this.currentSlide > collection.length - 1) {
        this.carouselGoToPage(collection.length - 1)
      }
    },
    localAchievement: {
      handler(localAchievement) {
        this.$emit('update:achievement', cloneDeep(localAchievement))
      },
      deep: true,
    },
    isMediaLoading: {
      handler(value) {
        if (value) {
          this.$store.dispatch('addWatchMedia', this.owner)
        } else {
          this.$store.dispatch('removeWatchMedia', this.owner)
        }
      },
    },
  },
  mounted() {
    if (!this.hasView) {
      this.checkInView()
      window.addEventListener('scroll', this.checkInView)
    }
    if (this.isMediaLoading) {
      this.watcherManipulate(true)
    }
  },
  beforeDestroy() {
    if (!this.hasView) {
      window.removeEventListener('scroll', this.checkInView)
    }
    this.$store.dispatch('removeWatchMedia', this.owner)
  },
  methods: {
    watcherManipulate(value) {
      if (value) {
        this.$store.dispatch('addWatchMedia', this.owner)
      } else {
        this.$store.dispatch('removeWatchMedia', this.owner)
      }
    },
    carouselGoToPage(i) {
      if (this.getMediaCarousel) {
        this.getMediaCarousel.goToPage(i, 'pagination')
      }
    },
    async getUserAchievement() {
      return showUserAchievement(this.localAchievement.id)
    },
    async refreshAchievement() {
      const res = await this.getUserAchievement()
      if (res.status) {
        if (!this.checkIsMediaLoading(res.data)) {
          this.localAchievement = res.data
        }
      }
    },
    checkIsMediaLoading(achievement) {
      return (
        achievement.media_not_finished_count !== 0 &&
        achievement.media_not_finished_count !== undefined
      )
    },
    async changeLike() {
      this.loadingLike = true
      const oldLike = !this.hasLike
      this.$emit('changeLike', oldLike ? 1 : -1)
      this.hasLike = !this.hasLike
      this.localAchievement.likes_count += 1 * oldLike ? 1 : -1
      await progressLike({
        user_progress_id: this.localAchievement.id,
      })
      this.loadingLike = false
    },
    async toTarget() {
      if (!this.asTarget) return
      this.asTarget = false
      await achievementAsTarget(this.localAchievement.id)
    },
    async setFake() {
      if (this.isFake) return
      this.isFake = true
      await achievementIsFake(this.localAchievement.id)
    },
    showShareModal() {
      let image = 'http://dev-liga.mintrocket.ru:8066/post_picture.png'
      const achievementImages = this.localAchievement.progress_files.progress_image
      if (achievementImages.length > 0) {
        image = achievementImages[0].url
      }
      const points = this.localAchievement.difficulty_point
      const title = this.canDeleteAchievement
        ? this.$t('progress-card.my-share-message', {
            achievement: this.localAchievement.title,
            points,
          })
        : this.$t('progress-card.share-message', {
            achievement: this.localAchievement.title,
          })
      this.setModal({
        bodyClass: 'modal-open',
        component: ShareModal,
        props: {
          url: this.getLink,
          title,
          image,
        },
        events: {
          close: this.setModal,
        },
      })
    },
    showEditModal() {
      const achievement = {
        ...this.localAchievement,
        files: this.getMediaCollection.map(k => ({
          ...k,
          data: k.url,
          progress: 100,
        })),
      }
      this.setModal({
        component: AchievementEditModal,
        props: {
          achievement,
        },
        events: {
          close: newAchievement => {
            this.localAchievement.progress_files.progress_image = newAchievement.files.filter(
              k => k.type === 'image',
            )
            this.localAchievement.progress_files.progress_video = newAchievement.files.filter(
              k => k.type === 'video',
            )
            this.setModal()
          },
          submit: (newAchievement, message) => {
            this.$toast.global.success_message({ message })
            this.localAchievement.geo = newAchievement.geo
            this.localAchievement.comment = newAchievement.comment
          },
        },
      })
    },
    async checkInView() {
      if (this.hasView) return
      const isView = this.isView()
      if (isView) {
        this.hasView = true
        await progressView(this.localAchievement.id)
        window.removeEventListener('scroll', this.checkInView)
      }
    },
    isView() {
      const doc = document.documentElement
      const docHalfHeight = window.innerHeight / 2
      const docViewTop = (window.pageYOffset || doc.scrollTop) - (doc.clientTop || 0)
      const docViewMiddle = docViewTop + docHalfHeight
      const elemViewBottom = this.getRoot.offsetTop + this.getRoot.clientHeight

      return (
        elemViewBottom >= docViewMiddle - docHalfHeight &&
        elemViewBottom <= docViewMiddle + docHalfHeight
      )
    },
    async removeAchievement() {
      this.removeVisible = false
      this.$emit('remove', this.localAchievement)
    },
    getPaginationElemClasses(index) {
      const { currentSlide } = this
      return { 'achievement-card__media-pagination-elem--active': currentSlide === index - 1 }
    },
  },
}
</script>

<style lang="stylus">
@import "achievement-card.styl"
</style>
