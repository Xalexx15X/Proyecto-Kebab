use tiendadekebab;

select * from usuario;

select * from usuario_tiene_alergenos;

select * from alergenos;

select * from usuario_tiene_alergenos;

select * from alergenos_tiene_ingredientes; 

select * from direccion;

select * from ingredientes;

select * from kebab;

select * from ingredientes_tiene_kebab;

select * from alergenos_tiene_ingredientes;

select * from pedidos;

select * from linea_pedido;


INSERT INTO alergenos (nombre, foto) VALUES ('Trigo','url_foto');
INSERT INTO alergenos (nombre, foto) VALUES ('Semola','url_foto');

INSERT INTO usuario_tiene_alergenos (usuario_id_usuario, alergenos_id_alergenos) VALUES (1,1);

SELECT * FROM usuario WHERE id_usuario = 1;
SELECT * FROM alergenos WHERE id_alergenos = 1;





