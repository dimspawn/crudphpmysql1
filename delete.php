<?php
	require_once 'config/sqlquery.php';
	if (!isset($_POST['barangid'])) {
		die(header("Location: index.php"));
	}
	$idBarang = (int) $_POST['barangid'];
	$getRowResult = $sqlqueries->getRowResult($sqlconn, $idBarang);
	if ($getRowResult==null) {
		die(header("Location: index.php"));
	}
	unlink('./files/'.$getRowResult['foto_barang']);
	$sqlqueries->deleteBarang($sqlconn, $getRowResult['id']);
	header("Location: index.php");
?>