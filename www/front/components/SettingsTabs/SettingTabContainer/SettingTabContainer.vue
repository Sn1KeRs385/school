<template lang="pug">
  .setting-tab-container
    .setting-tab-container__header
      slot(name="title")
    slot
</template>

<style lang="stylus">
@import "setting-tab-container.styl"
</style>
