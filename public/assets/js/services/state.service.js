/* User List State Service */

function createUserListState() {
  let state = {
    users: [],
    page: 0,
    pageSize: 10,
    totalPages: null,
    totalCount: null,
    searchTerm: '',
    isLoading: false,
    isEndOfList: false,
    scrollY: 0,
  };

  const listeners = new Set();

  return {
    get() {
      return state;
    },
    set(partial) {
      state = { ...state, ...partial };
      listeners.forEach((listener) => listener(state));
    },
    reset(searchTerm = '') {
      state = {
        users: [],
        page: 0,
        pageSize: 10,
        totalPages: null,
        totalCount: null,
        searchTerm,
        isLoading: false,
        isEndOfList: false,
        scrollY: 0,
      };
    },
    subscribe(listener) {
      listeners.add(listener);
      return () => listeners.delete(listener);
    },
  };
}

export const userListState = createUserListState();
