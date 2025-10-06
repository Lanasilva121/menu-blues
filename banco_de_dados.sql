CREATE TABLE categorias 
( 
 id_categoria INT PRIMARY KEY AUTO_INCREMENT,  
 categoria VARCHAR(20) NOT NULL  
); 

CREATE TABLE produtos 
( 
 id_produto INT PRIMARY KEY AUTO_INCREMENT,  
 nome VARCHAR(50) NOT NULL,  
 descricao TEXT,  
 preco FLOAT NOT NULL,  
 foto TEXT NOT NULL,  
 id_categoria INT  
); 

ALTER TABLE produtos ADD FOREIGN KEY(id_categoria) REFERENCES categorias (id_categoria)
