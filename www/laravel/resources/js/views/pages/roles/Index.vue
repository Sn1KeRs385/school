<template>
  <div>
    <Index :model="model" />
  </div>
</template>

<script>
import Index from "../../../components/CRUD/Index";
import model from "../../../models/Role";
export default {
  name: "RoleIndex",
  data() {
    return {
      model
    };
  },
  components: {
    Index
  }
};
</script>
