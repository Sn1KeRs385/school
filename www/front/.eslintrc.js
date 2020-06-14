module.exports = {
  root: true,
  env: {
    browser: true,
    node: true
  },
  parserOptions: {
    parser: 'babel-eslint'
  },
  extends: [
    'airbnb-vue'
  ],
  // add your custom rules here
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    "vue/no-v-html": "off",
    'import/no-extraneous-dependencies': 0,
    'no-param-reassign': ["error", { "props": false }]
    // 'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off'
  }
}
