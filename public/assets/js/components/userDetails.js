/* Renders detailed user information and handles navigation */

function initials(fullName) {
  return (fullName || '?')
    .split(' ')
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => part[0].toUpperCase())
    .join('');
}

export function renderUserDetails(user) {
  const wrapper = document.createElement('div');
  wrapper.className = 'detail-page';

  const backButton = document.createElement('button');
  backButton.className = 'back-button';
  backButton.type = 'button';
  backButton.innerHTML = '<span aria-hidden="true">&larr;</span> Back to list';
  backButton.id = 'back-to-list-btn';

  const card = document.createElement('div');
  card.className = 'detail-card';

  const banner = document.createElement('div');
  banner.className = 'detail-card__banner';

  const avatarWrap = document.createElement('div');
  avatarWrap.className = 'detail-card__avatar-wrap';

  const avatar = document.createElement('img');
  avatar.className = 'detail-card__avatar';
  avatar.src = user.avatarUrl || 'https://i.pravatar.cc/150';
  avatar.alt = user.fullName;
  avatar.referrerPolicy = 'no-referrer';
  avatar.onerror = () => {
    avatar.style.display = 'none';
    fallback.style.display = 'flex';
  };

  const fallback = document.createElement('div');
  fallback.className = 'detail-card__avatar-fallback';
  fallback.textContent = initials(user.fullName);
  fallback.style.display = 'none';

  avatarWrap.append(avatar, fallback);

  const name = document.createElement('div');
  name.className = 'detail-card__name';
  name.textContent = user.fullName;

  const title = document.createElement('div');
  title.className = 'detail-card__title';
  title.textContent = user.jobTitle;

  const badge = document.createElement('span');
  badge.className = 'badge badge--blue';
  badge.textContent = user.department?.name || '-';

  card.append(banner, avatarWrap, name, title, badge);

  const infoGrid = document.createElement('div');
  infoGrid.className = 'detail-card__grid';

  const rows = [
    ['Email', user.email],
    ['Department', user.department?.name || '-'],
    ['Department Code', user.department?.code || '-'],
    ['Joined', user.createdAt],
  ];

  rows.forEach(([label, value]) => {
    const row = document.createElement('div');
    row.className = 'detail-card__row';
    const labelEl = document.createElement('span');
    labelEl.className = 'detail-card__row-label';
    labelEl.textContent = label;
    const valueEl = document.createElement('span');
    valueEl.className = 'detail-card__row-value';
    valueEl.textContent = value;
    row.append(labelEl, valueEl);
    infoGrid.appendChild(row);
  });

  card.appendChild(infoGrid);

  wrapper.append(backButton, card);
  return wrapper;
}
