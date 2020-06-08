<template>
<div>
    <Index :model="model" />
</div>
</template>

<script>
import Index from "../../../components/CRUD/Index";
import model from "../../../models/Entrance";
export default {
  name: "EntranceIndex",
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