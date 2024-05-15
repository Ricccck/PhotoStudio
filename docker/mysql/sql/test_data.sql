INSERT INTO clients (
    name,
    company_name,
    post_code,
    address,
    email,
    phone_number,
    regist_date
  )
VALUES (
  "佐藤太郎",
  "株式会社テスト",
  "100-8111",
  "東京都千代田区千代田1-1",
  "test@example.com",
  "123-4567-8910",
  NOW()
);