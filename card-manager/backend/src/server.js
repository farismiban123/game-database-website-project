import express from 'express'
import mysql from 'mysql2/promise'
import cors from 'cors'
import dotenv from 'dotenv'
import path from 'node:path'
import { fileURLToPath } from 'node:url'

const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)

dotenv.config({ path: path.resolve(__dirname, '../.env') })

const app = express()
app.use(cors())
app.use(express.json())

const db = await mysql.createPool({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
  port: Number(process.env.DB_PORT || 3306),
  waitForConnections: true,
  connectionLimit: 10
})

const normalizePlayerId = (idPlayer) => {
  if (idPlayer === undefined || idPlayer === null || idPlayer === '') return null
  const parsedId = Number(idPlayer)
  return Number.isFinite(parsedId) ? parsedId : null
}

const handleDatabaseError = (res, error) => {
  if (error.code === 'ER_NO_REFERENCED_ROW_2') {
    return res.status(400).json({ message: 'id_player tidak ditemukan di tabel player' })
  }

  console.error(error)
  return res.status(500).json({ message: 'Terjadi kesalahan pada database' })
}

app.get('/api/health', (req, res) => {
  res.json({ message: 'API card_manager berjalan' })
})

app.get('/api/players', async (req, res) => {
  try {
    const [rows] = await db.query('SELECT * FROM player ORDER BY id_player DESC')
    res.json(rows)
  } catch (error) {
    handleDatabaseError(res, error)
  }
})

app.get('/api/players/:id', async (req, res) => {
  const idPlayer = normalizePlayerId(req.params.id)

  if (!idPlayer) {
    return res.status(400).json({ message: 'id_player tidak valid' })
  }

  try {
    const [players] = await db.query('SELECT * FROM player WHERE id_player = ?', [idPlayer])

    if (players.length === 0) {
      return res.status(404).json({ message: 'Player tidak ditemukan' })
    }

    const [items] = await db.query(
      'SELECT * FROM item WHERE id_player = ? ORDER BY id_item ASC',
      [idPlayer]
    )
    const [quests] = await db.query(
      'SELECT * FROM quest WHERE id_player = ? ORDER BY id_quest ASC',
      [idPlayer]
    )

    res.json({ ...players[0], items, quests })
  } catch (error) {
    handleDatabaseError(res, error)
  }
})

app.post('/api/players', async (req, res) => {
  const { username, join_date } = req.body

  if (!username) {
    return res.status(400).json({ message: 'username wajib diisi' })
  }

  const joinDate = join_date || new Date().toISOString().slice(0, 10)

  try {
    const [result] = await db.query(
      'INSERT INTO player (username, join_date) VALUES (?, ?)',
      [username, joinDate]
    )

    const [rows] = await db.query('SELECT * FROM player WHERE id_player = ?', [result.insertId])
    res.status(201).json(rows[0])
  } catch (error) {
    handleDatabaseError(res, error)
  }
})

app.get('/api/items', async (req, res) => {
  const idPlayer = normalizePlayerId(req.query.id_player)
  const params = []
  const whereClause = idPlayer ? 'WHERE item.id_player = ?' : ''

  if (idPlayer) {
    params.push(idPlayer)
  }

  try {
    const [rows] = await db.query(`
      SELECT item.*, player.username
      FROM item
      LEFT JOIN player ON player.id_player = item.id_player
      ${whereClause}
      ORDER BY item.id_item DESC
    `, params)
    res.json(rows)
  } catch (error) {
    handleDatabaseError(res, error)
  }
})

app.post('/api/items', async (req, res) => {
  const { id_player, name, description } = req.body

  if (!name) {
    return res.status(400).json({ message: 'name wajib diisi' })
  }

  try {
    const [result] = await db.query(
      'INSERT INTO item (id_player, name, description) VALUES (?, ?, ?)',
      [normalizePlayerId(id_player), name, description || null]
    )

    const [rows] = await db.query('SELECT * FROM item WHERE id_item = ?', [result.insertId])
    res.status(201).json(rows[0])
  } catch (error) {
    handleDatabaseError(res, error)
  }
})

app.get('/api/quests', async (req, res) => {
  const idPlayer = normalizePlayerId(req.query.id_player)
  const params = []
  const whereClause = idPlayer ? 'WHERE quest.id_player = ?' : ''

  if (idPlayer) {
    params.push(idPlayer)
  }

  try {
    const [rows] = await db.query(`
      SELECT quest.*, player.username
      FROM quest
      LEFT JOIN player ON player.id_player = quest.id_player
      ${whereClause}
      ORDER BY quest.id_quest DESC
    `, params)
    res.json(rows)
  } catch (error) {
    handleDatabaseError(res, error)
  }
})

app.post('/api/quests', async (req, res) => {
  const { id_player, title, description } = req.body

  if (!title) {
    return res.status(400).json({ message: 'title wajib diisi' })
  }

  try {
    const [result] = await db.query(
      'INSERT INTO quest (id_player, title, description) VALUES (?, ?, ?)',
      [normalizePlayerId(id_player), title, description || null]
    )

    const [rows] = await db.query('SELECT * FROM quest WHERE id_quest = ?', [result.insertId])
    res.status(201).json(rows[0])
  } catch (error) {
    handleDatabaseError(res, error)
  }
})

const port = Number(process.env.PORT || 5000)

app.listen(port, () => {
  console.log(`Backend jalan di http://localhost:${port}`)
})
