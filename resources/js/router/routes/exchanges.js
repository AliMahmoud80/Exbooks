export default [
  {
    name: 'exchange',
    path: '/exchange/:id',
    component: () =>
      import(/* WebpackChunkName: exchange */ '@/views/Exchanges/Index.vue'),
  },
  {
    name: 'create-exchange',
    path: '/exchange/create',
    component: () =>
      import(
        /* WebpackChunkName: exchange-create */ '@/views/Exchanges/Create.vue'
      ),
    meta: { requireAuth: true },
  },
]
