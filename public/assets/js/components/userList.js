/* Renders, loads, searches, and manages the user list */

import { ApiService } from '../services/api.service.js';
import { userListState } from '../services/state.service.js';
import { renderUserCard, renderSkeletonGrid } from './userCard.js';

let sentinelObserver = null;

export function renderUserListPage(container, navigate) {
  container.innerHTML = '';

  const state = userListState.get();

  const toolbar = document.createElement('div');
  toolbar.className = 'list-toolbar';

  const heading = document.createElement('h1');
  heading.className = 'list-toolbar__heading';
  heading.textContent = state.searchTerm ? `Results for "${state.searchTerm}"` : 'All Team Members';

  const countLabel = document.createElement('p');
  countLabel.className = 'list-toolbar__count';
  countLabel.id = 'list-count-label';

  toolbar.append(heading, countLabel);

  const errorBanner = document.createElement('div');
  errorBanner.className = 'error-banner';
  errorBanner.style.display = 'none';

  const grid = document.createElement('div');
  grid.className = 'user-grid';

  const emptyState = document.createElement('div');
  emptyState.className = 'empty-state';
  emptyState.style.display = 'none';
  emptyState.innerHTML = '<div class="empty-state__icon">&#128269;</div><p>No users match your search.</p>';

  const sentinel = document.createElement('div');
  sentinel.id = 'scroll-sentinel';

  const endOfList = document.createElement('div');
  endOfList.className = 'end-of-list';
  endOfList.textContent = "You've reached the end of the list.";
  endOfList.style.display = 'none';

  container.append(toolbar, errorBanner, grid, emptyState, sentinel, endOfList);

  function updateCountLabel() {
    const current = userListState.get();
    if (current.totalPages === null) {
      countLabel.textContent = 'Loading users...';
      return;
    }
    countLabel.textContent = `Showing ${current.users.length} of ${current.totalCount ?? '...'} users`;
  }

  state.users.forEach((user) => {
    grid.appendChild(renderUserCard(user, (id) => navigate(`/users/${id}`)));
  });

  updateCountLabel();
  window.scrollTo(0, state.scrollY || 0);

  function showError(message) {
    errorBanner.textContent = message;
    errorBanner.style.display = 'block';
  }

  function hideError() {
    errorBanner.style.display = 'none';
  }

  async function loadNextPage() {
    const current = userListState.get();
    if (current.isLoading || current.isEndOfList) return;

    userListState.set({ isLoading: true });
    const skeleton = renderSkeletonGrid(4);
    container.insertBefore(skeleton, sentinel);

    try {
      const nextPage = current.page + 1;
      const response = await ApiService.getUsers({
        page: nextPage,
        pageSize: current.pageSize,
        searchTerm: current.searchTerm,
      });

      response.data.forEach((user) => {
        grid.appendChild(renderUserCard(user, (id) => navigate(`/users/${id}`)));
      });

      const isEndOfList = nextPage >= response.totalPages;
      const updatedUsers = [...current.users, ...response.data];

      userListState.set({
        users: updatedUsers,
        page: nextPage,
        totalPages: response.totalPages,
        totalCount: response.totalCount,
        isLoading: false,
        isEndOfList,
      });

      updateCountLabel();
      emptyState.style.display = updatedUsers.length === 0 ? 'block' : 'none';
      endOfList.style.display = isEndOfList && updatedUsers.length > 0 ? 'block' : 'none';
      hideError();
    } catch (error) {
      userListState.set({ isLoading: false });
      showError(error.message || 'Failed to load users.');
    } finally {
      skeleton.remove();
    }
  }

  if (sentinelObserver) sentinelObserver.disconnect();
  sentinelObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) loadNextPage();
      });
    },
    { rootMargin: '200px' },
  );
  sentinelObserver.observe(sentinel);

  window.addEventListener('scroll', () => {
    userListState.set({ scrollY: window.scrollY });
  });

  if (state.page === 0) {
    loadNextPage();
  }
}

export function resetUserListSearch(searchTerm) {
  userListState.reset(searchTerm);
}
