import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.baseURL = process.env.VUE_APP_SERVER_HOST + '/api'

// fetch CSRF cookie.
axios.get('/csrf-cookie')
