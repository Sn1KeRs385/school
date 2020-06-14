<template>
<div>
    <Index :model="model" />
</div>
</template>

<script>
import Index from "../../../components/CRUD/Index";
import model from "../../../models/Apartment";
export default {
  name: "ApartmentIndex",
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