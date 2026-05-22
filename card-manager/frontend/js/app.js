import { fetchCards, createCard, deleteCard } from './utils/api.js'
import { filterCards, formatDate } from './utils/helpers.js'
import { renderCardGrid } from './components/card.js'
import { openModal, closeModal, initModal } from './components/modal.js'

let allCards = []

const cardGrid     = document.getElementById('card-grid')
const searchInput  = document.getElementById('search-input')
const detailPanel  = document.getElementById('detail-panel')
const addCardForm  = document.getElementById('add-card-form')

// ── Init ──────────────────────────────────────────
async function init() {
  initModal('add-modal')
  document.getElementById('btn-add').addEventListener('click', () => openModal('add-modal'))
  document.getElementById('detail-close').addEventListener('click', closeDetail)
  searchInput.addEventListener('input', handleSearch)
  addCardForm.addEventListener('submit', handleAddCard)
  await loadCards()
}

// ── Load Cards ────────────────────────────────────
async function loadCards() {
  try {
    allCards = await fetchCards()
  } catch {
    // Pakai data dummy kalau backend belum jalan
    allCards = [
      { id: '1', name: 'Iron Chest Plate', description: 'Armor terkuat dari besi tempa.', image: null, createdAt: new Date().toISOString() },
      { id: '2', name: 'Slaying The Dragon', description: 'Kartu legenda dengan kekuatan api.', image: null, createdAt: new Date().toISOString() },
    ]
  }
  render(allCards)
}

// ── Render ────────────────────────────────────────
function render(cards) {
  renderCardGrid(cards, cardGrid, {
    onView: showDetail,
    onDelete: handleDelete
  })
}

// ── Search ────────────────────────────────────────
function handleSearch(e) {
  render(filterCards(allCards, e.target.value))
}

// ── Add Card ──────────────────────────────────────
async function handleAddCard(e) {
  e.preventDefault()
  const name        = document.getElementById('input-name').value.trim()
  const description = document.getElementById('input-desc').value.trim()
  if (!name) return

  try {
    const newCard = await createCard({ name, description })
    allCards.unshift(newCard)
  } catch {
    // Tambah ke local kalau backend belum jalan
    allCards.unshift({ id: Date.now().toString(), name, description, image: null, createdAt: new Date().toISOString() })
  }

  addCardForm.reset()
  closeModal('add-modal')
  render(filterCards(allCards, searchInput.value))
}

// ── Delete Card ───────────────────────────────────
async function handleDelete(id) {
  if (!confirm('Hapus kartu ini?')) return
  try { await deleteCard(id) } catch { /* lanjut */ }
  allCards = allCards.filter(c => c.id !== id)
  closeDetail()
  render(filterCards(allCards, searchInput.value))
}

// ── Detail Panel ──────────────────────────────────
function showDetail(card) {
  document.getElementById('detail-name').textContent = card.name
  document.getElementById('detail-desc').textContent = card.description || 'Tidak ada deskripsi.'
  document.getElementById('detail-date').textContent = formatDate(card.createdAt)
  detailPanel.classList.add('active')
}

function closeDetail() {
  detailPanel.classList.remove('active')
}

// ── Start ─────────────────────────────────────────
init()
