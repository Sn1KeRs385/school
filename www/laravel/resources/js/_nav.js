import models from './models';

let MintMenuNavs = Object.values(models).map(model => {
  return {
    name: model.name,
    url: `/${model.url}`,
    icon: model.icon,
    badge: {}
  }
});

function CRUDlink({
  model,
  badge
}) {
  return {
    name: model.name,
    url: `/${model.url}`,
    icon: model.icon,
    badge
  }
}

export default {

  items: [{
      name: 'Dashboard',
      url: '/dashboard',
      icon: 'icon-speedometer',
    },

    {
      title: true,
      name: 'Справочники',
      class: '',
      wrapper: {
        element: '',
        attributes: {}
      }
    },

    ...MintMenuNavs,

  ]
}