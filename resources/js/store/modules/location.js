import axios from 'axios'

export const locationModule = {
  actions: {
    fetchCountries() {
      return axios.get('/countries')
    },
    fetchCities() {
      return axios.get('/cities')
    },
  },
}
