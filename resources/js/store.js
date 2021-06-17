import { isLoggedIn, logOut } from "./shared/utils/auth"

export default {
    state: {
        lastSearch: {
            from:null,
            to:null
        },
        basket:{
            items:[]
        },
        isLoggedIn : false,
        user:{}
    },
    mutations: {
        setLastSearch(state, payload) {
            state.lastSearch = payload
        },
        addToBasket(state, payload) {
            state.basket.items.push(payload)
        },
        removeFromBasket(state, payload) {
            state.basket.items = state.basket.items.filter(item => item.bookable.id !== payload)
        },
        setBasket(state, payload) {
            state.basket = payload
        },
        setUser(state, payload) {
            state.user = payload
        },
        setLoggedIn(state, payload) {
            state.isLoggedIn = payload
        }
    },
    actions: {
        setLastSearch(context, payload) {
            context.commit('setLastSearch', payload)
            localStorage.setItem('lastSearch', JSON.stringify(payload))
        },
        loadLocalstorage(context) {
            const lastSearch = localStorage.getItem('lastSearch')

            if (lastSearch) {
                context.commit('setLastSearch', JSON.parse(lastSearch))   
            }

            const basket = localStorage.getItem('basket')

            if (basket) {
                context.commit('setBasket', JSON.parse(basket))
            }
        },
        addToBasket({commit,state}, payload) {
            commit('addToBasket', payload)
            localStorage.setItem('basket',JSON.stringify(state.basket))
        },
        removeFromBasket({commit,state},payload) {
            commit('removeFromBasket', payload)
            localStorage.setItem('basket',JSON.stringify(state.basket))
        },
        clearBasket({commit,state,payload}) {
            commit('setBasket', {items: []})
            localStorage.setItem('basket', JSON.stringify(state.basket))
        },
        async loadUser({commit,dispatch}) {
            if (isLoggedIn()) {
                try {
                    const user = (await axios.get('/user')).data
                    commit('setUser', user)
                    commit('setLoggedIn', true)
                } catch (error) {
                    //logout called only when user is unauthorized
                    // if( error.response.status == 401) {
                    //     dispatch('logout')
                    // }

                    dispatch('logout')

                }
            }
        },
        logout({commit}) {
            commit('setLoggedIn', false)
            commit('setUser', {})
            logOut()
        }

    },
    getters: {
        itemsInBasketCount : (state) => state.basket.items.length,
        inBasketAlready(state) {
            return function (bookableId) {
                var bookableObj = state.basket.items.find(item => item.bookable.id === bookableId)
                
                if (bookableObj === undefined) {
                    return false;
                }

                return bookableObj
            }; 
        }
    }
}