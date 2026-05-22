USE card_manager;

INSERT INTO player (username, join_date) VALUES
('Arthur', '2026-05-01'),
('Luna', '2026-05-02'),
('Drake', '2026-05-03');

INSERT INTO item (id_player, name, description) VALUES
(111, 'Iron Sword', 'Basic sword made from iron'),
(111, 'Steel Shield', 'Shield with solid defense'),
(112, 'Magic Wand', 'Wand infused with mana'),
(112, 'Hunter Bow', 'Bow for long range attacks'),
(113, 'Dragon Armor', 'Armor crafted from dragon scales'),
(113, 'Healing Potion', 'Potion to restore health'),
(111, 'Fire Dagger', 'Small dagger with fire element'),
(112, 'Ice Staff', 'Staff that controls ice magic'),
(113, 'Thunder Hammer', 'Hammer infused with lightning'),
(111, 'Shadow Cloak', 'Cloak that increases stealth');

INSERT INTO quest (id_player, title, description) VALUES
(111, 'Goblin Hunt', 'Defeat 10 goblins in the forest'),
(111, 'Lost Relic', 'Find the ancient relic'),
(112, 'Mage Trial', 'Pass the magic academy test'),
(112, 'Forest Patrol', 'Protect the forest village'),
(113, 'Dragon Slayer', 'Defeat the mountain dragon'),
(113, 'Potion Delivery', 'Deliver healing potions safely'),
(111, 'Bandit Camp', 'Destroy the bandit hideout'),
(112, 'Frozen Cave', 'Explore the frozen cave'),
(113, 'Thunder Peak', 'Climb the thunder mountain'),
(111, 'Shadow Mission', 'Infiltrate enemy territory');

INSERT INTO raw_item (id_item, title, description) VALUES
-- Iron Sword
(121, 'Iron Ore', 'Main material for forging iron sword'),
(121, 'Wood Handle', 'Used as sword grip'),

-- Steel Shield
(122, 'Steel Ingot', 'Main shield material'),
(122, 'Leather Strap', 'Used to hold the shield'),

-- Magic Wand
(123, 'Magic Crystal', 'Core magical component'),
(123, 'Oak Wood', 'Base material for wand'),

-- Hunter Bow
(124, 'Flexible Wood', 'Bow frame material'),
(124, 'Strong String', 'Bow string component'),

-- Dragon Armor
(125, 'Dragon Scale', 'Rare armor material'),
(125, 'Titanium Plate', 'Reinforcement material'),

-- Healing Potion
(126, 'Herb Leaf', 'Basic healing ingredient'),
(126, 'Pure Water', 'Potion mixture base'),

-- Fire Dagger
(127, 'Flame Stone', 'Adds fire attribute'),
(127, 'Iron Blade', 'Base dagger material'),

-- Ice Staff
(128, 'Ice Crystal', 'Contains ice energy'),
(128, 'Ancient Wood', 'Strong magical wood'),

-- Thunder Hammer
(129, 'Thunder Core', 'Lightning energy source'),
(129, 'Heavy Iron', 'Hammer body material'),

-- Shadow Cloak
(130, 'Dark Fabric', 'Main cloak material'),
(130, 'Shadow Essence', 'Enhances stealth ability');