CREATE TABLE clients (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  company_name VARCHAR(100),
  post_code VARCHAR(8) NOT NULL,
  address VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone_number VARCHAR(11) NOT NULL,
  regist_date DATETIME NOT NULL,
  update_date DATETIME,
  delete_date DATETIME,
  delete_flg TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE clients_pass (
  client_id INT UNSIGNED PRIMARY KEY,
  password_hash VARCHAR(255),
  FOREIGN KEY(client_id) REFERENCES clients(id)
);

CREATE TABLE upload_photos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  client_id INT UNSIGNED NOT NULL,
  photo_url VARCHAR(100) NOT NULL,
  sample_url VARCHAR(100) NOT NULL,
  price DECIMAL(10, 2),
  upload_date DATETIME NOT NULL,
  update_date DATETIME,
  delete_date DATETIME,
  delete_flg TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE customers (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  family_name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  post_code VARCHAR(8),
  address VARCHAR(100),
  phone_number VARCHAR(11),
  regist_date DATETIME NOT NULL,
  update_date DATETIME,
  delete_date DATETIME,
  delete_flg TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE customers_pass (
  customer_id INT UNSIGNED PRIMARY KEY,
  password_hash VARCHAR(255),
  FOREIGN KEY(customer_id) REFERENCES customers(id)
);

CREATE TABLE customer_photos(
  customer_id INT UNSIGNED NOT NULL,
  photo_id INT UNSIGNED NOT NULL,
  upload_date DATETIME NOT NULL,
  hidden_date DATETIME,
  hidden_flg TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (customer_id, photo_id),
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (photo_id) REFERENCES upload_photos(id)
);

CREATE TABLE cart (
  crt_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer_id INT UNSIGNED NOT NULL,
  photo_id INT UNSIGNED NOT NULL,
  delete_flg TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  INDEX crt_idx(customer_id, delete_flg),
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);
