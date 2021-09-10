export default [
  {
    path: '/register',
    component: () =>
      import(/* webpackChunkName: "register" */ '@/views/Users/Register'),
    meta: { requireAuth: false },
  },
  {
    path: '/login',
    component: () =>
      import(/* webpackChunkName: "login" */ '@/views/Users/Login'),
    meta: { requireAuth: false },
  },
  {
    path: '/profile',
    component: () =>
      import(/* webpackChunkName: "profile" */ '@/views/Users/Profile'),
    meta: { requireAuth: true },
  },
]
