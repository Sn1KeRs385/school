<template lang="pug">
  b-card(
    header="Главное меню"
    header-tag="header"
  )
    b-list-group
      b-list-group-item(
        v-for="item in mainMenu"
        :key="item.name"
        :href="currentPage !== item.name ? item.link : ''"
        :class="{'active': currentPage === item.name}"
      ) {{item.name}}
</template>

<script>
export default {
  props: {
    currentPage: {
      required: true,
      type: String,
    },
  },
  computed: {
    mainMenu() {
      return [
        {name:"Главная", link:'/'},
        {name:"Сведения об образовательной организации", link:'/index'},
        {name:"Методическая работа", link:'/index'},
        {name:"Наши достижения", link:'/index'},
        {name:"Галерея", link:'/index'},
        {name:"Информация для поступающих", link:'/index'},
        {name:"Информация для детей и родителей", link:'/index'},
        {name:"Интернет-приёмная", link:'/index'},
        {name:"Дистанционное обучение", link:'/index'},
      ];
    }
  }
}
</script>

<style lang="stylus" scoped>
@import "navigation-menu.styl"
</style>
