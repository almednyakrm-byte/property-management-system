CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE properties (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE contracts (
  id INT AUTO_INCREMENT,
  property_id INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (property_id) REFERENCES properties(id)
);

CREATE TABLE payments (
  id INT AUTO_INCREMENT,
  contract_id INT NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  payment_date DATE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (contract_id) REFERENCES contracts(id)
);

CREATE TABLE transactions (
  id INT AUTO_INCREMENT,
  payment_id INT NOT NULL,
  transaction_date DATE NOT NULL,
  transaction_type ENUM('deposit', 'withdrawal') NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (payment_id) REFERENCES payments(id)
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO properties (name, address)
VALUES ('Property 1', 'Address 1'),
       ('Property 2', 'Address 2'),
       ('Property 3', 'Address 3');

INSERT INTO contracts (property_id, start_date, end_date)
VALUES (1, '2022-01-01', '2022-12-31'),
       (2, '2022-02-01', '2022-11-30'),
       (3, '2022-03-01', '2022-10-31');

INSERT INTO payments (contract_id, amount, payment_date)
VALUES (1, 1000.00, '2022-01-15'),
       (2, 1200.00, '2022-02-15'),
       (3, 1500.00, '2022-03-15');

INSERT INTO transactions (payment_id, transaction_date, transaction_type, amount)
VALUES (1, '2022-01-15', 'deposit', 1000.00),
       (2, '2022-02-15', 'deposit', 1200.00),
       (3, '2022-03-15', 'deposit', 1500.00);