MariaDB [game_db]> DESC items;
+------------------+---------------+------+-----+---------+----------------+
| Field            | Type          | Null | Key | Default | Extra          |
+------------------+---------------+------+-----+---------+----------------+
| item_id          | int(11)       | NO   | PRI | NULL    | auto_increment |
| item_name        | varchar(100)  | NO   |     | NULL    |                |
| item_category    | varchar(5000) | YES  |     | NULL    |                |
| item_description | text          | YES  |     | NULL    |                |
| item_rarity      | varchar(50)   | NO   |     | Common  |                |
+------------------+---------------+------+-----+---------+----------------+
5 rows in set (0.010 sec)

MariaDB [game_db]> DESC quest;
+-------------------+--------------+------+-----+---------+----------------+
| Field             | Type         | Null | Key | Default | Extra          |
+-------------------+--------------+------+-----+---------+----------------+
| quest_id          | int(11)      | NO   | PRI | NULL    | auto_increment |
| quest_name        | varchar(100) | NO   |     | NULL    |                |
| quest_description | text         | YES  |     | NULL    |                |
| quest_reward      | varchar(100) | YES  |     | NULL    |                |
| quest_difficulty  | varchar(50)  | YES  |     | Easy    |                |
+-------------------+--------------+------+-----+---------+----------------+
5 rows in set (0.015 sec)

MariaDB [game_db]> DESC inventory;
+--------------+---------+------+-----+---------+----------------+
| Field        | Type    | Null | Key | Default | Extra          |
+--------------+---------+------+-----+---------+----------------+
| inventory_id | int(11) | NO   | PRI | NULL    | auto_increment |
| player_id    | int(11) | NO   | MUL | NULL    |                |
| item_id      | int(11) | NO   | MUL | NULL    |                |
| modifier_id  | int(11) | YES  |     | NULL    |                |
+--------------+---------+------+-----+---------+----------------+
4 rows in set (0.006 sec)

MariaDB [game_db]> DESC completed_quest;
+--------------------+---------+------+-----+-----------+----------------+
| Field              | Type    | Null | Key | Default   | Extra          |
+--------------------+---------+------+-----+-----------+----------------+
| completed_quest_id | int(11) | NO   | PRI | NULL      | auto_increment | *hapus bagian ini.*
| player_id          | int(11) | NO   | MUL | NULL      |                |
| quest_id           | int(11) | NO   | MUL | NULL      |                |
| date_completed     | date    | YES  |     | curdate() |                |
+--------------------+---------+------+-----+-----------+----------------+
4 rows in set (0.024 sec)

MariaDB [game_db]> DESC player;
+--------------+--------------+------+-----+-----------+----------------+
| Field        | Type         | Null | Key | Default   | Extra          |
+--------------+--------------+------+-----+-----------+----------------+
| player_id    | int(11)      | NO   | PRI | NULL      | auto_increment |
| username     | varchar(100) | NO   |     | NULL      |                |
| player_level | int(11)      | NO   |     | NULL      |                |
| date_joined  | date         | YES  |     | curdate() |                |
+--------------+--------------+------+-----+-----------+----------------+
4 rows in set (0.013 sec)