-- Script para adicionar as colunas de jogos semelhantes à tabela existente
USE projeto_vitrine;

ALTER TABLE jogos 
ADD COLUMN jogo_semelhante_1 INT NULL,
ADD COLUMN jogo_semelhante_2 INT NULL,
ADD FOREIGN KEY (jogo_semelhante_1) REFERENCES jogos(id) ON DELETE SET NULL,
ADD FOREIGN KEY (jogo_semelhante_2) REFERENCES jogos(id) ON DELETE SET NULL;
