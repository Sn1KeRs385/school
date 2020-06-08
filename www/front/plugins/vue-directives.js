import Vue from 'vue'

Vue.prototype.$focusElement = {
  id: null,
  isValid: null,
}

Vue.prototype.$setActiveElement = function(component) {
  Vue.prototype.$focusElement.id = component ? component.id : null
  Vue.prototype.$focusElement.isValid = () => (component ? component.isValid : null)
}
