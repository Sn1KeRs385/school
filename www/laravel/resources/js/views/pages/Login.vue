<template>
  <div class="app flex-row align-items-center">
    <div class="container">
      <b-row class="justify-content-center">
        <b-col md="6">
          <b-card-group>
            <b-card no-body class="p-4">
              <b-card-body>
                <h1>Войти</h1>
                <p class="text-muted"></p>

                <form @submit.prevent="signin()">
                  
                  <b-input-group class="mb-3">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="icon-user"></i></span></div>
                    <input v-model="username" type="text" class="form-control" placeholder="Логин пользователя">
                  </b-input-group>
                  
                  <b-input-group class="mb-4">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="icon-lock"></i></span></div>
                    <input v-model="password" type="text" class="form-control" placeholder="Пароль">
                  </b-input-group>
                  
                  <b-row>
                    <b-col cols="6">
                      <b-button type="submit" variant="primary" class="px-4">Войти</b-button>
                    </b-col>
                  </b-row>

                </form>
                
              </b-card-body>
            </b-card>
            <!-- <b-card no-body class="text-white bg-primary py-5 d-md-down-none" style="width:44%">
              <b-card-body class="text-center">
                <div>
                  <h2>Sign up</h2>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                  <b-button variant="primary" class="active mt-3">Register Now!</b-button>
                </div>
              </b-card-body>
            </b-card> -->
          </b-card-group>
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
import Actions from "../../actions";

export default {
  name: "Login",
  data() {
    return {
      username: "",
      password: ""
    };
  },
  notifications: {
    showMsg({ type, title, message }) {
      return {
        type,
        title,
        message
      };
    }
  },
  methods: {
    async signin() {
      let { username, password } = this;
      if (!(await Actions.signinByUsername({ username, password }))) {
        this.showMsg({
          type: "error",
          title: "Ошибка",
          message: "Не удалось отправить смс"
        });
      }
    }
  }
};
</script>
