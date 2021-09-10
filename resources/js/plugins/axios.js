import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.baseURL = '/api'

// fetch CSRF cookie.
axios.get('/csrf-cookie')
