CREATE TABLE IF NOT EXISTS category (
  category_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  category VARCHAR(100) NOT NULL,
  img_url VARCHAR(30) NOT NULL
);


CREATE TABLE IF NOT EXISTS price (
  price_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  price INT UNSIGNED NOT NULL
);


CREATE TABLE IF NOT EXISTS clients (
  client_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  first_name_kana VARCHAR(100) NOT NULL,
  last_name_kana VARCHAR(100) NOT NULL,
  company_name VARCHAR(100),
  email VARCHAR(255) NOT NULL,
  phone_number VARCHAR(11) NOT NULL,
  sex TINYINT(1) NOT NULL,
  zip VARCHAR(8) NOT NULL,
  pref VARCHAR(100) NOT NULL,
  city VARCHAR(100) NOT NULL,
  town VARCHAR(100) NOT NULL,
  website VARCHAR(255),
  profile_picture VARCHAR(255),
  regist_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  is_deleted TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
);


CREATE TABLE IF NOT EXISTS client_pass (
  client_id INT UNSIGNED PRIMARY KEY,
  password_hash VARCHAR(255),
  FOREIGN KEY(client_id) REFERENCES clients(client_id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS customers (
  customer_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  first_name_kana VARCHAR(100) NOT NULL,
  last_name_kana VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone_number VARCHAR(11) NOT NULL,
  sex TINYINT(1) NOT NULL,
  zip VARCHAR(8) NOT NULL,
  pref VARCHAR(100) NOT NULL,
  city VARCHAR(100) NOT NULL,
  town VARCHAR(100) NOT NULL,
  profile_picture VARCHAR(255),
  regist_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  is_deleted TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
);


CREATE TABLE IF NOT EXISTS customer_pass (
  customer_id INT UNSIGNED PRIMARY KEY,
  password_hash VARCHAR(255),
  FOREIGN KEY(customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS sessions (
  session_id CHAR(36) NOT NULL PRIMARY KEY,
  user_id INT NOT NULL,
  user_type ENUM('customer', 'client'),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  is_active TINYINT(1) UNSIGNED NOT NULL DEFAULT 1
);


CREATE TABLE IF NOT EXISTS upload_photos (
  photo_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  photo_title VARCHAR(100) NOT NULL,
  client_id INT UNSIGNED NOT NULL,
  photo_url VARCHAR(100) NOT NULL,
  sample_url VARCHAR(100) NOT NULL,
  category INT UNSIGNED NOT NULL,
  price INT UNSIGNED NOT NULL,
  tags JSON NOT NULL,
  is_examined TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  upload_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  is_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  is_deleted TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  FOREIGN KEY (client_id) REFERENCES clients(client_id),
  FOREIGN KEY (category) REFERENCES category(category_id),
  FOREIGN KEY (price) REFERENCES price(price_id)
);


CREATE TABLE IF NOT EXISTS cart (
  crt_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer_id INT UNSIGNED NOT NULL,
  photo_id INT UNSIGNED NOT NULL,
  purchased_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  is_purchased TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  is_deleted TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
  FOREIGN KEY (photo_id) REFERENCES upload_photos(photo_id)
);


-- サンプルデータ
INSERT INTO
  clients (
    username,
    first_name,
    last_name,
    first_name_kana,
    last_name_kana,
    company_name,
    email,
    phone_number,
    sex,
    zip,
    pref,
    city,
    town,
    website,
    profile_picture
  )
VALUES
  (
    'Taro106',
    '太郎',
    '山田',
    'タロウ',
    'ヤマダ',
    'サンプル株式会社',
    'client@example.com',
    '09012345678',
    1,
    '1000001',
    '東京都',
    '千代田区',
    '1-1-1',
    'http://www.example.com',
    'profile1.jpg'
  );


INSERT INTO
  client_pass (client_id, password_hash)
VALUES
  (
    1,
    "$2y$10$PqhMtE4bXlffk/9BpM8EK.jIHiWCxt3LQ9mtWz/x3VsMje/cg0QE"
  );


INSERT INTO
  customers (
    username,
    first_name,
    last_name,
    first_name_kana,
    last_name_kana,
    email,
    phone_number,
    sex,
    zip,
    pref,
    city,
    town,
    profile_picture
  )
VALUES
  (
    'Hanako875',
    '花子',
    '鈴木',
    'ハナコ',
    'スズキ',
    'customer@example.com',
    '08087654321',
    2,
    '100-0002',
    '東京都',
    '千代田区 皇居外苑',
    '1-1-1',
    'profile2.jpg'
  );


INSERT INTO
  customer_pass (customer_id, password_hash)
VALUES
  (
    1,
    "$2y$10$PqhMtE4bXlffk/9BpM8EK.jIHiWCxt3LQ9mtWz/x3VsMje/cg0QE"
  );


INSERT INTO
  category (category, img_url)
VALUES
  ('自然・風景', 'nature.jpg'),
  ('都市・建築', 'city.jpg'),
  ('ポートレート・人物', 'person.jpg'),
  ('動物', 'animal.jpg'),
  ('食べ物・ドリンク', 'foods.jpg'),
  ('ファッション', 'fashion.jpg'),
  ('スポーツ・アクション', 'sports.jpg'),
  ('旅行・観光', 'travel.jpg'),
  ('抽象的・コンセプチュアル', 'abstract.jpg'),
  ('モノクロ・セピア', 'monochrome.jpg'),
  ('マクロ・クローズアップ', 'macro.jpg'),
  ('水中', 'underwater.jpg'),
  ('夜景・夜の写真', 'night_view.jpg'),
  ('パノラマ', 'panorama.jpg'),
  ('タイムラプス・長時間露光', 'time_lapse.jpg'),
  ('航空・ドローン', 'aviation.jpg'),
  ('ストリートフォト', 'street.jpg'),
  ('イベント・フェスティバル', 'event.jpg'),
  ('季節・四季', 'season.jpg'),
  ('インダストリアル・機械', 'machine.jpg'),
  ('風物詩・伝統文化', 'culture.jpg'),
  ('日常生活', 'daily_life.jpg'),
  ('歴史的・古代遺跡', 'history.jpg'),
  ('芸術作品', 'art.jpg'),
  ('サイエンス・科学', 'science.jpg'),
  ('赤ちゃん・乳児', 'baby.jpg'),
  ('音楽・パフォーマンス', 'music.jpg'),
  ('交通・乗り物', 'traffic.jpg'),
  ('インテリア', 'interior.jpg'),
  ('建設・工事', 'construction.jpg'),
  ('スパ・リラクゼーション', 'relaxation.jpg'),
  ('ビジネス・オフィス', 'business.jpg'),
  ('家族・家庭', 'family.jpg'),
  ('ファンタジー・ストーリーテリング', 'fantasy.jpg'),
  ('メディア・ジャーナリズム', 'media.jpg'),
  ('キャラクター・コミック', 'character.jpg');


INSERT INTO
  price (price)
VALUES
  (0),
  (500),
  (2000),
  (4000),
  (6000);