<template>
<div>
    <Form :model="model" />
</div>
</template>

<script>
  import Form from "../../../components/CRUD/Form";
  import model from "../../../models/House";
  export default {
    name: "HouseForm",
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