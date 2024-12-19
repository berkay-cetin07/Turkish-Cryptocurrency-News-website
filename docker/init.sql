-- Tabloyu sıfırdan oluşturun
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Düz metin şifrelerle dummy verileri ekleyin
INSERT INTO users (username, password) VALUES 
('admin', 'adminpass'),
('user1', 'user1pass'),
('user2', 'user2pass'),
('cem', 'cempass'),
('apo', 'apopass'),
('ahmet', 'ahmetpass'),
('berk', '123'),
('amo', '159');
