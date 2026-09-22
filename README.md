TODO:
- Database aguardando ser conectada!

CREATE DATABASE IF NOT EXISTS aw2_2;
 
USE aw2_2;
 
CREATE TABLE IF NOT EXISTS ranking (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(60) NOT NULL,
    pontos     INT NOT NULL
);
