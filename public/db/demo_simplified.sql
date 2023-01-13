/* playground initial database create & seed */

SET default_storage_engine=INNODB;

-- Registered users
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id int PRIMARY KEY AUTO_INCREMENT,
  email varchar(255) UNIQUE,
  name varchar(200),
  created_at datetime NOT NULL,
  password char(128)
);

-- Categories we can assign to items
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  id int PRIMARY KEY AUTO_INCREMENT,
  name varchar(250) NOT NULL UNIQUE
);

INSERT INTO categories (name) VALUES
('Produce'),
('Deli'),
('Meat'),
('Fish'),
('Canned'),
('Snacks'),
('Baby'),
('Pet'),
('Drinks'),
('Bakery'),
('Dairy'),
('Condiments'),
('Clothing'),
('Miscellaneous'),
('Baking Supplies'),
('Breakfast'),
('Grains & Pasta');

-- Items entered by users for which we track prices
DROP TABLE IF EXISTS items;
CREATE TABLE items (
  id int PRIMARY KEY AUTO_INCREMENT,
  name varchar(512),
  category_id int NULL
  -- FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE NULL,
);

-- Items can be shared by many users. This specifies which items each user has
-- in their list, along with the settings the user has for each item
DROP TABLE IF EXISTS item_user;
CREATE TABLE item_user (
  user_id int NOT NULL,
  item_id int NOT NULL,
  completed_at datetime,
  quantity int NULL,
  PRIMARY KEY (user_id,item_id)
  -- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  -- FOREIGN KEY (item_id) REFERENCES Items (id) ON DELETE CASCADE
);