<template>
  <div>
    <Form :model="model" />
  </div>
</template>

<script>
import Form from "../../../components/CRUD/Form";
import model from "../../../models/User";
export default {
  name: "UserForm",
  data() {
    return {
      model
    };
  },
  components: {
    Form
  }
};
</script>
