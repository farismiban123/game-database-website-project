const getSameProjectPhpApiUrl = () => {
  const marker = '/card-manager/frontend/'
  const pathname = window.location.pathname.replaceAll('\\', '/')
  const markerIndex = pathname.indexOf(marker)

  if (markerIndex === -1) {
    return 'http://127.0.0.1:5000'
  }

  return `${window.location.origin}${pathname.slice(0, markerIndex)}/card-manager/backend/api/index.php`
}

const isStaticDevServer = ['5173', '5500', '5501'].includes(window.location.port)
const BASE_URL = isStaticDevServer ? 'http://127.0.0.1:5000' : getSameProjectPhpApiUrl()

const request = async (path, options = {}) => {
  const res = await fetch(`${BASE_URL}${path}`, {
    ...options,
    headers: { 'Content-Type': 'application/json', ...options.headers }
  })

  if (!res.ok) {
    const error = await res.json().catch(() => ({}))
    throw new Error(error.message || 'Request gagal')
  }

  return res.json()
}

export const fetchPlayers = async () => {
  return request('/api/players')
}

export const fetchPlayerById = async (idPlayer) => {
  return request(`/api/players/${encodeURIComponent(idPlayer)}`)
}

export const createPlayer = async (data) => {
  return request('/api/players', {
    method: 'POST',
    body: JSON.stringify(data)
  })
}

export const fetchItems = async (idPlayer) => {
  const query = idPlayer ? `?id_player=${encodeURIComponent(idPlayer)}` : ''
  return request(`/api/items${query}`)
}

export const createItem = async (data) => {
  return request('/api/items', {
    method: 'POST',
    body: JSON.stringify(data)
  })
}

export const fetchQuests = async (idPlayer) => {
  const query = idPlayer ? `?id_player=${encodeURIComponent(idPlayer)}` : ''
  return request(`/api/quests${query}`)
}

export const createQuest = async (data) => {
  return request('/api/quests', {
    method: 'POST',
    body: JSON.stringify(data)
  })
}

export const fetchCards = async () => {
  const res = await fetch(`${BASE_URL}/api/cards`)
  if (!res.ok) throw new Error('Gagal mengambil data kartu')
  return res.json()
}

export const fetchCardById = async (id) => {
  const res = await fetch(`${BASE_URL}/api/cards/${id}`)
  if (!res.ok) throw new Error('Kartu tidak ditemukan')
  return res.json()
}

export const createCard = async (data) => {
  const res = await fetch(`${BASE_URL}/api/cards`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  })
  if (!res.ok) throw new Error('Gagal membuat kartu')
  return res.json()
}

export const updateCard = async (id, data) => {
  const res = await fetch(`${BASE_URL}/api/cards/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  })
  if (!res.ok) throw new Error('Gagal mengupdate kartu')
  return res.json()
}

export const deleteCard = async (id) => {
  const res = await fetch(`${BASE_URL}/api/cards/${id}`, { method: 'DELETE' })
  if (!res.ok) throw new Error('Gagal menghapus kartu')
}
