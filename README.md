TODO:
- Popular com texto e imagem final (10)
- Database aguardando ser conectada!

CREATE DATABASE IF NOT EXISTS 'aw2-2'
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;
 
USE 'aw2-2';
 
CREATE TABLE IF NOT EXISTS ranking (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(60) NOT NULL,
    pontos     INT NOT NULL,
);
