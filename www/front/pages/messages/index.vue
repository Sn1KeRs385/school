<template lang="pug">
  .chat
    div(
      v-if="openChatInfo"
    )
      b-table.chat__table-with-chat(
        :items="messages"
        :fields="fieldsMessages"
        sticky-header
      )
        template(
          v-slot:head(message)="data"
        )
          .row
            .col-3
              b-button(
                variant="dark"
                @click="closeChat"
              )
                b-icon-arrow-left-circle
                span {{" Назад"}}
            .col-9.chat__table__header-center-text
              b {{`${openChatInfo.last_name} ${openChatInfo.first_name} ${openChatInfo.patronymic || ''}`}}

        template(
          v-slot:cell(message)="data"
        )
          .row(
            :class="{'justify-content-end': data.item.recipient_id === openChatInfo.id}"
            v-if="(data.index === 0) || ((data.index - 1) >= 0 && messages[data.index - 1].sender_id !== data.item.sender_id)"
          )
            b {{`${data.item.sender.last_name} ${data.item.sender.first_name} ${data.item.sender.patronymic || ''}`}}
          .row(
            :class="{'justify-content-end': data.item.recipient_id === openChatInfo.id}"
          )
            div(
              v-html="data.item.text"
            )
          .row(
            :class="{'justify-content-end': data.item.recipient_id === openChatInfo.id}"
          )
            p.chat__table__date {{getLocaleDate(data.item.created_at)}}
      b-textarea(
        v-model="messageToSend"
        rows="3"
        max-rows="100"
        placeholder="Введите сообщение"
      )
      br
      .row.justify-content-end
        b-button(
          variant="primary"
          @click="sendMessageButton"
        ) {{"Отправить"}}
    div(
      v-if="!openChatInfo"
    )
      b-table.chat__table(
        :items="chats"
        :fields="fieldsChats"
        sticky-header
      )
        template(
          v-slot:cell(chat)="data"
        )
          b {{`${data.item.last_name} ${data.item.first_name} ${data.item.patronymic || ''}`}}
          p(
            v-if="data.item.unread_counter > 0"
          ) {{`Новых сообщений: ${data.item.unread_counter}`}}
          p.chat__table__date {{getLocaleDate(data.item.created_at)}}
        template(
          v-slot:cell(action)="data"
        )
          b-button(
            variant="success"
            @click="openChat(data.item)"
          )
            b-icon-arrow-right-circle
</template>

<script>

  import { DateStringToLocalString } from '../../plugins/datetime_formater'
import { url, getChats, getMessages, sendMessage } from '../../plugins/api/message'

export default {
  middleware: 'authenticated',
  data(){
    return {
      canTimer: true,
      openChatInfo: null,
      messages: [],
      messageToSend: null,
      fieldsChats: [
        {
          key: "chat",
          label: "Чат"
        },
        {
          key: "action",
          label: "Перейти",
          class: "chat__table__button"
        }
      ],
      fieldsMessages: [
        {
          key: "message",
          label: "Сообщения"
        }
      ],
    }
  },
  async asyncData(){
    const [ data ] = await Promise.all([
      getChats(),
    ])
    return{
      chats: data.data,
    }
  },
  created() {
    setTimeout(this.loadChats, 1);
  },
  beforeDestroy() {
    this.canTimer = false;
  },
  methods: {
    async loadChats() {
      const [ data ] = await Promise.all([
        getChats(),
      ])
      this.chats = data.data;
      if(this.canTimer) {
        setTimeout(this.loadChats, 2000);
      }
    },
    async loadMessages(userId) {
      const [ data ] = await Promise.all([
        getMessages(userId),
      ])
      this.messages = data.data;
    },
    async loadMessagesFromTimer(){
      if(this.openChatInfo && this.canTimer){
        await this.loadMessages(this.openChatInfo.id);
        setTimeout(this.loadMessagesFromTimer, 2000);
      }
    },
    getLocaleDate(date){
      return DateStringToLocalString(date);
    },
    async openChat(user){
      await this.loadMessages(user.id);
      this.openChatInfo = user;
      setTimeout(this.loadMessagesFromTimer, 1);
    },
    closeChat(){
      this.openChatInfo = null;
      this.messages = [];
    },
    async sendMessageButton(){
      await Promise.all([
        sendMessage(this.openChatInfo.id, this.messageToSend),
      ])
      this.messageToSend = null;
      await this.loadMessages(this.openChatInfo.id);
    },
  }
}
</script>

<style src="./messages.styl" lang="stylus" scoped></style>
