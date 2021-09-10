import axios from 'axios'

export const userModule = {
  getters: {
    user() {
      return JSON.parse(localStorage.getItem('user')) || null
    },
    authenticated(_, getters) {
      return getters.user !== null
    },
  },
  mutations: {
    setUser(_, user) {
      localStorage.setItem('user', JSON.stringify(user))
    },
  },
  actions: {
    fetchCurrentUser() {
      return axios.get('/user')
    },

    register(
      _,
      { name, email, phone_number, password, password_confirmation }
    ) {
      return axios.post('/register', {
        name,
        email,
        phone_number,
        password,
        password_confirmation,
      })
    },

    login(_, { email, password }) {
      return axios.post('/login', { email, password, remember: true })
    },

    logout() {
      localStorage.removeItem('user')
      return axios.get('/logout')
    },

    update({ getters }, { user, updatePassword }) {
      if (updatePassword) {
        return axios.put('/user/' + getters.user.id, {
          current_password: user.currentPassword,
          new_password: user.newPassword,
          new_password_confirmation: user.passwordConfirmation,
          update_password: updatePassword,
        })
      } else {
        return axios.put('/user/' + getters.user.id, {
          name: user.name,
          email: user.email,
          phone_number: user.phoneNumber,
          current_password: user.currentPassword,
          update_password: updatePassword,
        })
      }
    },
  },
}
