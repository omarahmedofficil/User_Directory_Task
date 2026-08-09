/* Renders user information and handles card interactions */

const DEPARTMENT_COLORS = {
  Engineering: 'badge--blue',
  'Human Resources': 'badge--pink',
  Marketing: 'badge--orange',
  Sales: 'badge--green',
  Finance: 'badge--purple',
};

function initials(fullName) {
  return fullName
    .split(' ')
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => part[0].toUpperCase())
    .join('');
}

export function renderUserCard(user, onSelect) {
  const card = document.createElement('article');
  card.className = 'user-card';
  card.setAttribute('role', 'button');
  card.tabIndex = 0;

  const avatarWrap = document.createElement('div');
  avatarWrap.className = 'user-card__avatar-wrap';

  const avatar = document.createElement('img');
  avatar.className = 'user-card__avatar';
  avatar.src = user.avatarUrl || 'https://i.pravatar.cc/150';
  avatar.alt = user.fullName;
  avatar.loading = 'lazy';
  avatar.referrerPolicy = 'no-referrer';
  avatar.onerror = () => {
    avatar.style.display = 'none';
    fallback.style.display = 'flex';
  };

  const fallback = document.createElement('div');
  fallback.className = 'user-card__avatar-fallback';
  fallback.textContent = initials(user.fullName || '?');
  fallback.style.display = 'none';

  avatarWrap.append(avatar, fallback);

  const name = document.createElement('div');
  name.className = 'user-card__name';
  name.textContent = user.fullName;

  const title = document.createElement('div');
  title.className = 'user-card__title';
  title.textContent = user.jobTitle;

  const badge = document.createElement('span');
  const colorClass = DEPARTMENT_COLORS[user.departmentName] || 'badge--gray';
  badge.className = `badge ${colorClass}`;
  badge.textContent = user.departmentName;

  const meta = document.createElement('div');
  meta.className = 'user-card__meta';
  meta.innerHTML = '<span>View profile</span><span aria-hidden="true">&rarr;</span>';

  card.append(avatarWrap, name, title, badge, meta);

  const select = () => onSelect(user.id);
  card.addEventListener('click', select);
  card.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      select();
    }
  });

  return card;
}

export function renderSkeletonGrid(count = 6) {
  const grid = document.createElement('div');
  grid.className = 'skeleton-grid';
  for (let i = 0; i < count; i++) {
    const card = document.createElement('div');
    card.className = 'skeleton-card';
    grid.appendChild(card);
  }
  return grid;
}
