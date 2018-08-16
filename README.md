# CRUD PHP MySQL

Use database .sql file in database folder or use query:

CREATE DATABASE testpraktek;

CREATE TABLE barang (
	id int(11) NOT NULL AUTO_INCREMENT,
  	nama_barang varchar(255) NOT NULL,
  	foto_barang varchar(255) NOT NULL,
  	harga_beli int(11) NOT NULL,
  	harga_jual int(11) NOT NULL,
  	stok int(11) NOT NULL,
  	PRIMARY KEY (id),
  	UNIQUE KEY nama_barang (nama_barang)
);
