<template>
      <b-nav-item-dropdown right no-caret>
        <template slot="button-content">
          <img src="img/avatars/6.jpg" class="img-avatar">
        </template>
        <b-dropdown-header tag="div" class="text-center"><strong>{{getEmail}}</strong></b-dropdown-header>
        <!-- <b-dropdown-item><i class="fa fa-bell-o"></i> Updates<b-badge variant="info">{{itemsCount}}</b-badge></b-dropdown-item>
        <b-dropdown-item><i class="fa fa-envelope-o"></i> Messages<b-badge variant="success">{{itemsCount}}</b-badge></b-dropdown-item>
        <b-dropdown-item><i class="fa fa-tasks"></i> Tasks<b-badge variant="danger">{{itemsCount}}</b-badge></b-dropdown-item>
        <b-dropdown-item><i class="fa fa-comments"></i> Comments<b-badge variant="warning">{{itemsCount}}</b-badge></b-dropdown-item>
        <b-dropdown-header tag="div" class="text-center"><strong>Settings</strong></b-dropdown-header>
        <b-dropdown-item><i class="fa fa-user"></i> Profile</b-dropdown-item>
        <b-dropdown-item><i class="fa fa-wrench"></i> Settings</b-dropdown-item>
        <b-dropdown-item><i class="fa fa-usd"></i> Payments<b-badge variant="light">{{itemsCount}}</b-badge></b-dropdown-item>
        <b-dropdown-item><i class="fa fa-file"></i> Projects<b-badge variant="primary">{{itemsCount}}</b-badge></b-dropdown-item>
        <b-dropdown-divider></b-dropdown-divider>
        <b-dropdown-item><i class="fa fa-shield"></i> Lock Account</b-dropdown-item> -->
        <b-dropdown-item @click="logout()" ><i class="fa fa-lock"></i> Выйти </b-dropdown-item>
      </b-nav-item-dropdown>
</template>
<script>
import Actions from "../../actions";

export default {
  name: "header-dropdown-accnt",
  data: () => {
    return {
      itemsCount: 42
    };
  },
  methods: {
    async logout() {
      await Actions.signout();
    }
  },
  computed: {
    getEmail() {
      return localStorage.getItem("email");
    }
  }
};
</script>
