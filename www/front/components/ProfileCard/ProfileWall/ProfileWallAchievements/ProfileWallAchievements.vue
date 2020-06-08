<template lang="pug">
  div(v-if="isHiddenAchievements") {{ $t('profile-card.wall.hidden') }}
  div(v-else)
    AchievementCard(
      v-for="(achievement, i) in achievements.items"
      :key="achievement.id"
      :currentUser="currentUser"
      :margin="i >= 1 ? '10px 0 0 0' : '0'"
      :achievement="achievement"
      :show-hints="showHints(i)"
      @update:achievement="updateAchievement(i, $event)"
      @changeLike="changeLikes"
      @remove="remove(i)"
    )
    InfiniteLoading(v-if="achievements.meta.next_exists" :distance="500" @infinite="infiniteHandler")

</template>

<script>
import AchievementCard from '../../../AchievementCard/AchievementCard.vue'
import { getUserAchievements } from '../../../../plugins/api/user'
import { deleteAchievement } from '../../../../plugins/api/progress'

export default {
  inject: ['currentUser', 'changeLikes', 'changePoints', 'showGeneralHints'],
  components: {
    AchievementCard,
  },
  props: {
    achievements: {
      required: true,
      type: Object,
    },
    isMe: {
      required: true,
      type: Boolean,
    },
  },
  data() {
    this.$t.bind(this)
    return {
      countRemoved: 0,
    }
  },
  computed: {
    getUserId() {
      return this.$route.params.id
    },
    isHiddenAchievements() {
      return this.achievements.hidden && !this.isMe
    },
  },
  methods: {
    async remove(index) {
      this.countRemoved += 1
      const achievement = this.achievements.items[index]
      this.$emit('remove:achievement', { index, achievement })
      this.changeLikes(-achievement.likes_count)
      this.changePoints(-achievement.difficulty_point)
      await deleteAchievement(achievement.id)
    },
    async infiniteHandler(e) {
      const paginate = await this.loadMore()
      this.$emit('update:achievements', paginate)
      if (!paginate.meta.next_exists) {
        e.complete()
      } else {
        e.loaded()
      }
    },
    async loadMore(currentPaginate = this.achievements) {
      const { data } = await getUserAchievements(this.getUserId, {
        records: currentPaginate.meta.records,
        page: currentPaginate.meta.page + 1,
        sub: 0,
        offset: this.countRemoved,
      })
      this.countRemoved = 0
      data.items = [...currentPaginate.items, ...data.items]
      return data
    },
    showHints(index) {
      return (
        index === 0 &&
        this.currentUser.id !== this.$store.state.auth.user.id &&
        this.showGeneralHints
      )
    },
    updateAchievement(index, achievement) {
      this.$emit('update:achievement', { index, achievement })
    },
  },
}
</script>
