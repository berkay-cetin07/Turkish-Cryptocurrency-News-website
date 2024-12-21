-- Tabloyu sıfırdan oluşturun
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

-- Dummy veriler
INSERT INTO users (username, password, role) VALUES
('admin', 'adminpass', 'admin'),
('user1', 'user1pass', 'user'),
('user2', 'user2pass', 'user'),
('cem', 'cempass', 'user'),
('apo', 'apopass', 'user'),
('ahmet', 'ahmetpass', 'user'),
('berk', '123', 'user'),
('amo', '159', 'user');
