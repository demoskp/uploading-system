import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

axios.defaults.baseURL = 'http://localhost:3000/api'


Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        quotes: []
    },
    mutations: {
        storeQuotes(state, data) {
            state.quotes = data
        }
    },
    actions: {
        submitQuote(context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('/quote/submit',
                    payload,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                    .then(response => {
                        resolve(response.data.data)
                    })
                    .catch(errors => {
                        reject(errors.response.data.errors)
                    })
            })
        },
        fetchAllQuotes(context) {
            return new Promise((resolve, reject) => {
                axios.get('/quotes')
                    .then(response => {
                        context.commit('storeQuotes', response.data.data)
                        resolve(response)
                    })
                    .catch(errors => {
                        reject(errors.response)
                    })
            });
        }
    },
    modules: {}
})
