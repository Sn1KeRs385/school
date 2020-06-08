export default function({ app, store, redirect }) {
  if (!store.getters['auth/authorized']) {
    return redirect(app.localePath('signin'))
  }
  return redirect()
}
