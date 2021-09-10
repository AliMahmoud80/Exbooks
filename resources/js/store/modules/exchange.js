import axios from 'axios'

export const exchangeModule = {
  namespaced: true,

  state: {
    exchanges: {},
    pagination: {},
    searchKey: null,
    searchResult: null,
    searchPagination: null,
  },

  getters: {
    exchanges({ exchanges, searchResult }) {
      return searchResult || exchanges
    },
    pagination({ pagination, searchPagination }) {
      return searchPagination || pagination
    },
  },

  mutations: {
    setExchanges(state, exchanges) {
      state.exchanges = exchanges
    },

    setPagination(state, pagination) {
      state.pagination = pagination
    },

    setSearchResult(state, result) {
      state.searchResult = result
    },

    setSearchPagination(state, pagination) {
      state.searchPagination = pagination
    },

    setSearchKey(state, key) {
      state.searchKey = key
    },

    clearSearch(state) {
      state.searchResult = null
      state.searchPagination = null
      state.searchKey = null
    },
  },

  actions: {
    fetch(_, id) {
      return axios.get('/exchange/' + id)
    },

    fetchAll(context, page) {
      return axios.get(`/exchange?page=${page}`).then((res) => {
        context.commit('setExchanges', res.data.data)
        context.commit('setPagination', res.data.meta)
      })
    },

    fetchUserExchanges() {
      return axios.get('/user/exchanges')
    },

    create(_, exchange) {
      // Filter object from empty values.
      Object.keys(exchange).forEach((k) =>
        exchange[k] === undefined || exchange[k].length == 0
          ? delete exchange[k]
          : {}
      )

      const formData = new FormData()

      if (exchange.previewImgs) {
        exchange.previewImgs.forEach((img) => {
          formData.append('preview[]', img.file)
        })
      }

      delete exchange['previewImgs']

      formData.append('data', JSON.stringify(exchange))

      return axios.post('/exchange', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
    },

    delete(_, id) {
      return axios.delete('/exchange/' + id)
    },

    search(context, { key, page }) {
      if (key === '') return

      context.commit('setSearchKey', key)

      return axios.get(`/exchange/search/${key}/${page}`).then((res) => {
        context.commit('setSearchResult', res.data.data || [])
        context.commit('setSearchPagination', res.data)
      })
    },

    paginate(context, page) {
      if (context.state.searchResult) {
        return context.dispatch('search', {
          key: context.state.searchKey,
          page,
        })
      } else {
        return context.dispatch('fetchAll', page)
      }
    },
  },
}
