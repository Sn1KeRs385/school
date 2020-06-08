<template>
<div>
    <Index :model="model" />
</div>
</template>

<script>
import Index from "../../../components/CRUD/Index";
import model from "../../../models/House";
export default {
  name: "HouseIndex",
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