CREATE DATABASE IF NOT EXISTS card_manager;
USE card_manager;

CREATE TABLE IF NOT EXISTS player (
    id_player INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    join_date DATE NOT NULL
) AUTO_INCREMENT = 111;

CREATE TABLE IF NOT EXISTS item (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_player INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,

    FOREIGN KEY (id_player) REFERENCES player(id_player) ON DELETE CASCADE
) AUTO_INCREMENT = 121;

CREATE TABLE IF NOT EXISTS quest (
    id_quest INT AUTO_INCREMENT PRIMARY KEY,
    id_player INT,
    title VARCHAR(100) NOT NULL,
    description TEXT,

    FOREIGN KEY (id_player) REFERENCES player(id_player) ON DELETE CASCADE
) AUTO_INCREMENT = 131;

CREATE TABLE IF NOT EXISTS raw_item (
    id_raw_item INT AUTO_INCREMENT PRIMARY KEY,
    id_item INT,
    title VARCHAR(100) NOT NULL,
    description TEXT,

    FOREIGN KEY (id_item) REFERENCES item(id_item) ON DELETE CASCADE
) AUTO_INCREMENT = 141;