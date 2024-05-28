CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
CREATE TABLE hayvantakibi(
    hayvan_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    tur VARCHAR(100) NOT NULL,
    yas INT (11) NOT NULL,
    tehlikeli_davranis VARCHAR(25) NOT NULL,
    saglik_durumu VARCHAR (100) NOT NULL,
)