import axios from "axios";

const state = {
    token: localStorage.getItem('authToken') || '',
    user: localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null,
};

const mutations = {
    SET_TOKEN(state, token) {
        state.token = token;
        localStorage.setItem('authToken', token);
    },
    REMOVE_TOKEN(state) {
        state.token = '';
        localStorage.removeItem('authToken');
    },
    SET_USER(state, user) {
        state.user = user;
        localStorage.setItem('user', JSON.stringify(user));
    },
    CLEAR_USER(state) {
        state.user = null;
        localStorage.removeItem('user');
    }
};

const actions = {
    async setToken({ commit }, token) {
        commit('SET_TOKEN', token);
    },
    async fetchUser({ commit, state }) {
        if (state.token) {
            try {
                const response = await axios.get('/api/user', {
                    headers: {
                        Authorization: `Bearer ${state.token}`
                    }
                });
                commit('SET_USER', response.data);
            } catch (error) {
                console.error('Failed to fetch user data: ', error);
                commit('CLEAR_USER');
            }
        }
    },
    async logout({ commit }) {
        commit('REMOVE_TOKEN');
        commit('CLEAR_USER');
    }
};

const getters = {
    token: (state) => state.token,
    user: (state) => state.user,
    isAuthenticated: (state) => !!state.token,
};

export default {
    state,
    mutations,
    actions,
    getters
};
