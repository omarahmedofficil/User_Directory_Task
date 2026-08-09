/* Handles HTTP requests, loading state, and API error responses */

const APP_BASE = window.__APP_BASE__ || "";
const API_BASE = `${APP_BASE}/api`;

function setLoading(isLoading) {
  const bar = document.getElementById("global-loading-bar");
  if (!bar) return;
  bar.classList.toggle("active", isLoading);
}

async function withLoading(promiseFactory) {
  setLoading(true);
  try {
    return await promiseFactory();
  } finally {
    setLoading(false);
  }
}

async function handleResponse(response) {
  if (!response.ok) {
    let body = null;
    let rawText = "";
    try {
      rawText = await response.clone().text();
      body = await response.json();
    } catch (_) {}

    let message =
      (body && body.error) || `Request failed with status ${response.status}`;

    if (body && body.debug) {
      const d = body.debug;
      message += ` [${d.type}: ${d.message} @ ${d.file}:${d.line}]`;
    } else if (!body && rawText) {
      message += ` - ${rawText.slice(0, 500)}`;
    }

    const error = new Error(message);
    error.status = response.status;
    throw error;
  }
  return response.json();
}

export const ApiService = {
  async getUsers({ page = 1, pageSize = 10, searchTerm = "" } = {}) {
    const params = new URLSearchParams({
      page: String(page),
      pageSize: String(pageSize),
    });
    if (searchTerm) params.set("searchTerm", searchTerm);

    return withLoading(async () => {
      const response = await fetch(`${API_BASE}/users?${params.toString()}`);
      return handleResponse(response);
    });
  },

  async getUserById(id) {
    return withLoading(async () => {
      const response = await fetch(
        `${API_BASE}/users/${encodeURIComponent(id)}`,
      );
      return handleResponse(response);
    });
  },
};
