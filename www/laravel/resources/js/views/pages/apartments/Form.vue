<template>
<div>
    <Form :model="model" />
</div>
</template>

<script>
  import Form from "../../../components/CRUD/Form";
  import model from "../../../models/Apartment";
  export default {
    name: "ApartmentForm",
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