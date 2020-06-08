import Vue from 'vue'
import Router from 'vue-router'

// Containers
import Full from '../containers/Full'

// Views
import Dashboard from '../views/Dashboard'

// Views - Pages
import Page404 from '../views/pages/Page404'
import Page500 from '../views/pages/Page500'
import Login from '../views/pages/Login'

import models from '../models';

Vue.use(Router)

let MintRoutes = Object.values(models).map(model => {
  return {
    name: model.name,
    path: `/${model.url}`,
    redirect: `${model.url}/`,
    component: {
      render(c) {
        return c('router-view')
      }
    },
    children: [{
      name: "Таблица",
      path: '/',
      component: require('../views/pages/' + model.url + '/Index')
    }, {
      name: "Добавление",
      path: 'add',
      component: require('../views/pages/' + model.url + '/Form')
    }, {
      name: "Редактирование",
      path: ':id',
      component: require('../views/pages/' + model.url + '/Form')
    }]
  }
});

let router = new Router({
  mode: 'hash', // Demo is living in GitHub.io, so required!
  linkActiveClass: 'open active',
  scrollBehavior: () => ({
    y: 0
  }),
  routes: [{
      path: '/auth',
      redirect: '/404',
      name: 'Auth',
      component: {
        render(c) {
          return c('router-view')
        }
      },
      children: [{
        path: 'login',
        name: 'Login',
        component: Login
      }]
    }, {
      path: '/',
      redirect: '/dashboard',
      name: 'HighTechDom',
      component: Full,
      meta: {
        requiresAuth: true
      },
      children: [{
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard
        },

        ...MintRoutes,

      ]
    },
    {
      path: '404',
      name: 'Page404',
      component: Page404
    },
    {
      path: '500',
      name: 'Page500',
      component: Page500
    }
  ]
})

router.beforeEach((to, from, next) => {

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!localStorage.getItem('access_token')) {
      next({
        path: '/auth/login',
        redirect: to.fullPath
      });
    }
  }
  next();
});

export default router;