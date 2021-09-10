import { createStore } from 'vuex'

import { userModule } from './modules/user'
import { exchangeModule } from './modules/exchange'
import { locationModule } from './modules/location'

const store = createStore({
  modules: {
    user: userModule,
    exchange: exchangeModule,
    location: locationModule,
  },
})

export default store
