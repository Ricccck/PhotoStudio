CREATE DATABASE photostudio_db DEFAULT CHARACTER SET utf8;

GRANT ALL PRIVILEGES ON photostudio_db.* TO photostudio_user @'localhost' IDENTIFIED BY 'photostudio_pass' WITH
GRANT OPTION;

CREATE TABLE clients (
  id int unsigned not null auto_increment primary key,
  name varchar(100) not null,
  company_name varchar(100),
  post_code varchar(8) not null,
  address varchar(100) not null,
  email varchar(255) not null,
  phone_number varchar(11) not null,
  regist_date datetime not null,
  update_date datetime,
  delete_date datetime,
  delete_flg tinyint(1) unsigned not null default 0
);

CREATE TABLE clients_pass (
  client_id int unsigned primary key,
  password_hash varchar(255),
  FOREIGN KEY(client_id) REFERENCES clients(id)
);

CREATE TABLE upload_photo (
  id int unsigned not null auto_increment primary key,
  client_id int unsigned not null,
  photo_url varchar(100) not null,
  sample_url varchar(100) not null,
  price DECIMAL(10, 2),
  upload_date datetime not null,
  update_date datetime,
  delete_date datetime,
  delete_flg tinyint(1) unsigned not null default 0,
  FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE customers (
  id int unsigned not null auto_increment primary key,
  first_name varchar(100) not null,
  family_name varchar(100) not null,
  email varchar(255) not null,
  password varchar(255) not null,
  post_code varchar(8),
  address varchar(100),
  phone_number varchar(11),
  regist_date datetime not null,
  update_date datetime,
  delete_date datetime,
  delete_flg tinyint(1) unsigned not null default 0
);

CREATE TABLE customers_pass (
  customer_id int unsigned primary key,
  password_hash varchar(255),
  FOREIGN KEY(customer_id) REFERENCES customers(id)
);

CREATE TABLE customer_photos(
  customer_id int unsigned not null primary key,
  photo_id int unsigned not null,
  upload_date datetime not null,
  hidden_date datetime,
  hidden_flg tinyint(1) unsigned not null default 0,
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (photo_id) REFERENCES upload_photos(id)
);

CREATE TABLE cart (	
  crt_id int unsigned not null auto_increment primary key,	
  customer_id int unsigned not null,	
  photo_id int unsigned not null,	
  delete_flg tinyint(1) unsigned not null default 0,	
  index crt_idx( customer_id, delete_flg ),
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);