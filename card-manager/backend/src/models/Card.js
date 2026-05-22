const { v4: uuidv4 } = require('uuid')

class Card {
  constructor({ name, description, image = null }) {
    this.id = uuidv4()
    this.name = name
    this.description = description
    this.image = image
    this.createdAt = new Date().toISOString()
  }
}

module.exports = Card
