<template lang="pug">
  NotFoundPage(v-if="error.statusCode === 404")
  UnknowingError(v-else)
</template>

<script>
import NotFoundPage from '../components/NotFoundPage/NotFoundPage.vue'
import UnknowingError from '../components/UnknowingError/UnknowingError.vue'

export default {
  components: {
    NotFoundPage,
    UnknowingError,
  },
  props: {
    error: {
      required: true,
      type: null,
    },
  },
}
</script>
