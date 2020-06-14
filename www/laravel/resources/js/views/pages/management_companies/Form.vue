<template>
<div>
    <Form :model="model" />
</div>
</template>

<script>
  import Form from "../../../components/CRUD/Form";
  import model from "../../../models/ManagementCompany";
  export default {
    name: "ManagementCompanyForm",
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