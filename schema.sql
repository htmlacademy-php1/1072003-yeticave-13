CREATE DATABASE yeti_cave DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

USE yeti_cave;
CREATE TABLE user (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  dt_registarion DATETIME DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128) UNIQUE NOT NULL,
  name CHAR(64) NOT NULL,
  password VARCHAR(128) NOT NULL,
  contacts VARCHAR (255)
);

CREATE TABLE category (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title CHAR(64),
  symbol_code CHAR(64)
);

CREATE TABLE lot (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  dt_add DATETIME DEFAULT CURRENT_TIMESTAMP,
  name VARCHAR(255) NOT NULL,
  category VARCHAR(255) NOT NULL,
  url_img VARCHAR(128) NOT NULL,
  start_cost INT NOT NULL,
  dt_end DATETIME,
  step_bet INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  winner_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,

  FOREIGN KEY (user_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (winner_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE bet (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  dt_bet DATETIME DEFAULT CURRENT_TIMESTAMP,
  sum INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  lot_id INT UNSIGNED NOT NULL,

  FOREIGN KEY (user_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (lot_id) REFERENCES lot(id) ON UPDATE CASCADE ON DELETE CASCADE
);

