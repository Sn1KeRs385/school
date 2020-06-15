<template lang="pug">
  .notification-control(
    @click="clickHeaderHandler"
    ref="control"
  )
    .notification-control__header(
      :class="{'notification-control__header--is-open': isOpen}"
      @click="changeIsOpen(!isOpen)"
      @mousedown.prevent
    )
      .notification-control__name
        b-icon-bell
        span(
          :class="{'notification-control__have-unread-notification': notificationUnread > 0}"
        ) {{notificationUnread > 0 ? ` (${notificationUnread})` : ''}}
      .notification-control__header-arrow(
        :class="{'notification-control__header-arrow--is-open': isOpen}"
      )
    .notification-control__menu(v-if="isOpen")
      .notification-control__menu-item(
        v-if="notifications.length === 0"
      ) Уведомления отсуствуют
      .notification-control__menu-item(
        v-for="item in notifications"
        :class="{'.notification-control__menu-item__link': item.link, '.notification-control__menu-item__default': !item.link}"
      )
        b-icon-trash(
          @click="deleteNotification(item.id)"
        )
        span {{item.title}}
        .notification-control__menu-my-notification(
          v-if="item.text"
          v-html="item.text"
        )
        .notification-control__menu-my-notification.date(
          v-if="item.created_at"
        ) {{getLocaleDate(item.created_at)}}


</template>
<!--.notification-control__menu-item.notification-control__menu-notifications-->
<!--.notification-control__menu-notifications-text Уведомления-->
<!--.notification-control__menu-notifications-counter 68-->
<script>
import ClosableByClickMixin from '../../../assets/js/vue-mixins/closable-by-click'
import ToolTip from '../../General/ToolTip/ToolTip.vue'
import { all, del } from '../../../plugins/api/api'
import { readAll } from '../../../plugins/api/notification'
const url = 'notifications'

export default {
  components: {
    ToolTip,
  },
  mixins: [ClosableByClickMixin('isOpen', 'changeIsOpen', 'getControl')],
  data() {
    return {
      isOpen: false,
      notifications: [],
      notificationUnread: 0,
    }
  },
  mounted() {
    setTimeout(this.loadNotification, 100);
  },
  computed: {
    getControl() {
      return this.$refs.control
    },
    getUser() {
      return this.$store.state.auth.user
    },
    getFullName() {
      return `${this.getUser.first_name} ${this.getUser.last_name}` || this.$t('header.no-name-label')
    },
  },
  methods: {
    clickHeaderHandler(e) {
      setTimeout(() => {
        if (e.target.tagName === 'A') {
          this.changeIsOpen(false)
        }
      }, 0)
    },
    async changeIsOpen(isOpen) {
      this.isOpen = isOpen;
      if(this.notifications.length > 0 && isOpen) {
        await Promise.all([
          readAll(this.notifications[0].created_at),
        ])
        this.notificationUnread = 0;
      }
    },
    getLocaleDate(date){
      return (new Date(date)).toLocaleString()
    },
    async loadNotification(setTimer = true) {
      const [ dataNotification ] = await Promise.all([
        all(url),
      ])
      this.notifications = dataNotification.data;

      let unread = 0;
      this.notifications.forEach(item => {
        if(!item.read_at)
          unread++;
      })
      this.notificationUnread = unread;
      if(setTimer) {
        setTimeout(this.loadNotification, 2000);
      }
    },
    goUrl(url) {
      this.$router.push(url)
    },
    async deleteNotification(id) {
      await Promise.all([
        del(id, url),
      ])
      await this.loadNotification(false);
    },
  },
}
</script>

<style lang="stylus">
@import "notification-control.styl"
</style>
