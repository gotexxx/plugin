export default {
    namespaced: true,

    state() {
        return {
            popup: {},
            personaCustomerIdsAdd: null,
            personaCustomerIdsDelete: null,
            isLoading: false
        };
    },

    mutations: {
        setPopup(state, popup) {
            state.popup = popup;
        },

        setPersonaCustomerIdsAdd(state, customerIds) {
            state.personaCustomerIdsAdd = customerIds;
        },

        setPersonaCustomerIdsDelete(state, customerIds) {
            state.personaCustomerIdsDelete = customerIds;
        },

        setIsLoading(state, isLoading) {
            state.isLoading = isLoading;
        }
    }
};
