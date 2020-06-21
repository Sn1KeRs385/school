<template lang="pug">
  .container
    b-button(
      v-if="$store.getters['auth/isAdmin']"
      variant="success"
      @click="openCreateModal"
    ) {{ $t('CRUD_Button.create_button') }}
    .main-content(
      v-for="data in news.items"
    )
      .row.justify-content-end
        b-button.delete-button(
          v-if="$store.getters['auth/isAdmin']"
          variant="danger"
          size="sm"
          @click="deleteMethod(data.id)"
        ) {{ $t('CRUD_Button.delete_button') }}
      b-card.main-content__card(
        v-bind:key="data.id"
        bg-variant="light"
        text-variant="black"
      )
        template(
          v-slot:header
        )
          .main-content__header.text-center {{data.title}}
        b-card-body
          b-card-text(
            v-html="data.text"
          )
        b-card-body
          b-card-sub-title.mb-2 {{nameFormat(data.creator)}}
          b-card-sub-title.mb-2 {{dateFormat(data.created_at)}}


</template>

<script>
import PageLayout from '../components/General/PageLayout/PageLayout.vue'
import CreateModal from '../components/Modals/News/CreateModal/CreateModal.vue'
import { getNews, deleteNews } from '../plugins/api/news'
import { DateStringToLocalString } from '../plugins/datetime_formater'
export default {
  inject: ['setModal'],
  middleware: 'authenticated',
  components: {
    PageLayout,
    CreateModal,
  },
  data(){
    return {
      createModal: {
        component: CreateModal,
        events: {
          close: this.setModal,
          reload: async () => {
            await this.loadNews();
          }
        },
      },
    }
  },
  async asyncData() {
    const [ news] = await Promise.all([
      getNews({
        page: 1,
        records: 5
      }),
    ])
    return {
      news: news.data,
    }
  },
  computed: {
    text() {
      return '<p>Добро пожаловать на сайт муниципального бюджетного учреждения дополнительного образования «Детская школа искусств №2» пгт. Высокий г. Мегиона<p>' +
      '<br><p>35 лет ДШИ №2 является культурным центром посёлка Высокий, это настоящий храм искусства.</p>' +
      '<br><p>«Творим Добро» - таков девиз нашей школы, потому как, педагогами движет Творчество и оно неразрывно связано Верой в то, что они делают важное и нужное дело, с Надеждой на поддержку со стороны родителей, общественности, администрации города, Ханты-Мансийского автономного округа и Любовью к искусству, а главное – к детям!</p>' +
      '<br><p> — Директор ДШИ №2 Кузнецова Г.С.</p>';
    },
  },
  methods: {
    dateFormat(date) {
      return DateStringToLocalString(date);
    },
    nameFormat(creator) {
      if(creator && creator.last_name){
        return `${creator.last_name} `
          + (creator.first_name ? `${creator.first_name.substr(0, 15)}. ` : '')
          + (creator.first_name && creator.patronymic ? `${creator.patronymic.substr(0, 1)}.` : '');
      } else {
        return '';
      }
    },
    openCreateModal() {
      this.setModal(this.createModal)
    },
    async deleteMethod(id) {
      await Promise.all([
        deleteNews(id)
      ]);
      await this.loadNews();
    },
    async loadNews() {
      const [news ] = await Promise.all([
        getNews({
          page: 1,
          records: 5
        }),
      ]);
      this.news = news.data;
    }
  }
}
</script>

<style lang="stylus">
  @import "index.styl"
</style>
