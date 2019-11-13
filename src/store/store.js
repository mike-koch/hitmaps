import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        game: null,
        mission: null
    },

    mutations: {
        SET_GAME(state, game) {
            state.game = game
        },
        SET_MISSION(state, mission) {
            state.mission = mission
        }
    },
    actions: {
        async loadGame(context, id) {
            var resp = await Vue.prototype.$http.get(
                Vue.prototype.$domain + '/api/v1/games/' + id
            )
            context.commit('SET_GAME', resp.data[0])
        },
    },
})
