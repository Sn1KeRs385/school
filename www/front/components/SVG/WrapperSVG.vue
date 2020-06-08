<!-- eslint-disable -->
<template lang="html">
  <object type="image/svg+xml" :data="src">
    <? xml-stylesheet type="text/css" href="{{ styles }}" ?>
    <slot />
  </object>
</template>

<script>
export default {
  props: {
    src: {
      required: true,
      type: String,
    },
    styles: {
      required: true,
      type: String,
    },
  },
}
</script>
