export const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('id-ID', {
    year: 'numeric', month: 'long', day: 'numeric'
  })
}

export const truncateText = (text, maxLength = 80) => {
  if (!text || text.length <= maxLength) return text
  return text.slice(0, maxLength) + '...'
}

export const filterCards = (cards, query) => {
  if (!query) return cards
  const q = query.toLowerCase()
  return cards.filter(c =>
    c.name?.toLowerCase().includes(q) ||
    c.description?.toLowerCase().includes(q)
  )
}
