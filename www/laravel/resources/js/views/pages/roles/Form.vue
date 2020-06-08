<template>
  <div>
    <Form :model="model" />
  </div>
</template>

<script>
import Form from "../../../components/CRUD/Form";
import model from "../../../models/Role";
export default {
  name: "RoleForm",
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
