<template>
<div>
    <Form :model="model" />
</div>
</template>

<script>
  import Form from "../../../components/CRUD/Form";
  import model from "../../../models/Entrance";
  export default {
    name: "EntranceForm",
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