import { createRouter, createWebHistory } from 'vue-router'
import store from '@/store/sotre'

import Home from '@/views/Home'
import NotFound from '@/views/404'

// Routes
import userRoutes from './routes/user'
import ExchangesRoutes from './routes/exchanges'

const routes = [
  {
    name: 'home',
    path: '/',
    component: Home,
  },
  ...userRoutes,
  ...ExchangesRoutes,
  {
    name: '404',
    path: '/:pathMatch(.*)*',
    component: NotFound,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Auth guard.
router.beforeEach((to) => {
  if (to.meta.requireAuth === true && !store.getters.authenticated) {
    return '/login'
  } else if (to.meta.requireAuth === false && store.getters.authenticated) {
    return '/'
  }
})

export default router
