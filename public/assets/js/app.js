/* Main App Controller */

import { renderUserListPage, resetUserListSearch } from './components/userList.js';
import { renderUserDetails } from './components/userDetails.js';
import { ApiService } from './services/api.service.js';

const APP_BASE = window.__APP_BASE__ || '';
const appEl = document.getElementById('app');
const searchInput = document.getElementById('search-input');
const brandLink = document.getElementById('brand-link');

if (brandLink) {
  brandLink.setAttribute('href', `${APP_BASE}/`);
}

function toFullPath(appPath) {
  return appPath === '/' ? `${APP_BASE}/` : `${APP_BASE}${appPath}`;
}

function toAppPath(fullPath) {
  let path = fullPath;
  if (APP_BASE && path.startsWith(APP_BASE)) {
    path = path.slice(APP_BASE.length);
  }
  return path || '/';
}

function navigate(appPath, { replace = false } = {}) {
  const fullPath = toFullPath(appPath);
  if (replace) {
    history.replaceState({}, '', fullPath);
  } else {
    history.pushState({}, '', fullPath);
  }
  render();
}

async function render() {
  const appPath = toAppPath(window.location.pathname);
  const detailMatch = appPath.match(/^\/users\/(.+)$/);

  if (detailMatch) {
    searchInput.style.visibility = 'hidden';
    await renderDetailRoute(detailMatch[1]);
    return;
  }

  searchInput.style.visibility = 'visible';
  renderUserListPage(appEl, navigate);
}

async function renderDetailRoute(id) {
  appEl.innerHTML = '<div class="skeleton-grid"><div class="skeleton-card"></div></div>';

  try {
    const user = await ApiService.getUserById(id);
    appEl.innerHTML = '';
    const detailsEl = renderUserDetails(user);
    appEl.appendChild(detailsEl);

    document.getElementById('back-to-list-btn').addEventListener('click', () => {
      navigate('/');
    });
  } catch (error) {
    appEl.innerHTML = `<div class="error-banner">${error.message || 'User not found.'}</div>`;
  }
}

let searchDebounce = null;
searchInput.addEventListener('input', (event) => {
  clearTimeout(searchDebounce);
  const value = event.target.value;
  searchDebounce = setTimeout(() => {
    resetUserListSearch(value);
    renderUserListPage(appEl, navigate);
  }, 300);
});

document.addEventListener('click', (event) => {
  const link = event.target.closest('a[data-link]');
  if (!link) return;
  event.preventDefault();
  navigate('/');
});

window.addEventListener('popstate', render);

render();
