<?php
	/**
	 * 
	 */
	class sqlquery
	{
		function __construct()
		{
			 
		}
		// koneksi database
		public function dbconnect(){
			$servername = "localhost";
			$username = "root";
			$password = "publiccipher21";
			$dbname	= "testpraktek";
			$conn = new mysqli($servername, $username, $password, $dbname);
			return $conn;
		}
		// hitung semua jumlah data
		public function countAll($conn)
		{
			$sql = "SELECT * FROM barang";
			$result = $conn->query($sql);
			return $result->num_rows;
		}
		// ambil semua data di database
		public function queryAll($conn, $offset, $rowPage)
		{
			$sql = "SELECT * FROM barang LIMIT ".$offset.",".$rowPage;
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$res[] = $row;
				}
				$result->free();
				return $res;
			}else{
				return null;
			}
		}
		// hitung jumlah data search
		public function countSearchAll($conn, $search)
		{
			$sql = "SELECT * FROM barang WHERE nama_barang LIKE '%".$search."%'";
			$result = $conn->query($sql);
			return $result->num_rows;
		}
		// ambil search data di database
		public function querySearchAll($conn, $offset, $rowPage, $search)
		{
			$sql = "SELECT * FROM barang WHERE nama_barang LIKE '%".$search."%' LIMIT ".$offset.",".$rowPage;
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$res[] = $row;
				}
				$result->free();
				return $res;
			}else{
				return null;
			}
		}
		// cek ketersediaan insert nama barang
		public function selectNameExist($conn, $nmBarang)
		{
			$sql = "SELECT nama_barang FROM barang WHERE nama_barang = '".$nmBarang."'";
			$result = $conn->query($sql);
			return ($result->num_rows > 0) ? true : false;
		}
		// cek ketersediaan update nama barang
		public function selectNameExistUpdate($conn, $nmBarang, $id)
		{
			$sql = "SELECT nama_barang FROM barang WHERE nama_barang = '".$nmBarang."' AND id <> '".$id."'";
			$result = $conn->query($sql);
			return ($result->num_rows > 0) ? true : false;	
		}
		// insert barang ke database
		public function insertBarang($conn,$insert_data)
		{
			$sql = "INSERT INTO barang (nama_barang, foto_barang, harga_beli, harga_jual, stok) VALUES ('".$insert_data['nama_barang']."','".$insert_data['foto_barang']."','".$insert_data['harga_beli']."','".$insert_data['harga_jual']."','".$insert_data['stok']."')";
			$conn->query($sql);
		}
		// update barang di database
		public function updateBarang($conn,$update_data,$id)
		{
			$sql = "UPDATE barang SET nama_barang = '".$update_data['nama_barang']."',foto_barang = '".$update_data['foto_barang']."',harga_beli = '".$update_data['harga_beli']."',harga_jual = '".$update_data['harga_jual']."',stok = '".$update_data['stok']."' WHERE id = '".$id."'";
			$conn->query($sql);
		}
		// ambil barang berdasarkan id
		public function getRowResult($conn, $id)
		{
			$sql = "SELECT * FROM barang WHERE id = '".$id."'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$res = $row;
				}
				$result->free();
				return $res;
			}else{
				return null;
			}	
		}
		//hapus barang
		public function deleteBarang($conn, $id)
		{
			$sql = "DELETE FROM barang WHERE id = '".$id."'";
			$conn->query($sql);
		}
	}
	//deklarasi object dan koneksi
	$sqlqueries = new sqlquery;
	$sqlconn = $sqlqueries->dbconnect();
	//print error jika terjadi kesalahan koneksi
	if ($sqlconn->connect_error) {
    	die("Connection gagal: Cek Pengaturan username and password di config/sqlquery.php");
	}