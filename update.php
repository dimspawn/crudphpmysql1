<?php
//check jika id dimasukkan
if (!isset($_GET['id'])) {
	die(header("Location: index.php"));
}
include_once 'config/sqlquery.php';
//query data yang akan diambil. redirect index jika hasilnya null
$idBarang = $_GET['id'];
if (!is_numeric($idBarang)) {
	die(header("Location: index.php"));
}else{
	$idBarang = (int) $idBarang;
}
$getRowResult = $sqlqueries->getRowResult($sqlconn, $idBarang);
if ($getRowResult==null) {
	die(header("Location: index.php"));
}
$ale = null;
$aler = null;
$progHalt = false;
if (isset($_POST['proses'])) {
	if (isset($_POST['nmBarang']) && isset($_POST['hrBeli']) && isset($_POST['hrJual']) && isset($_POST['stok'])) {
		$nmBarang = mysqli_real_escape_string($sqlconn, $_POST['nmBarang']);
		$hrBeli = $_POST['hrBeli'];
		$hrJual = $_POST['hrJual'];
		$stok = $_POST['stok'];
		$isNameWasTaken = $sqlqueries->selectNameExistUpdate($sqlconn, $nmBarang, $idBarang);
		//check if nama barang was exist
		if ($isNameWasTaken) {
			$ale = 'Nama barang tidak tersedia';
			$aler = 'danger';	
		}else{
			//check if harga beli, harga jual, & stok is number
			if (!is_numeric($_POST['hrBeli']) || !is_numeric($_POST['hrJual']) || !is_numeric($_POST['stok'])) {
				$ale = 'Harga beli, Harga Jual dan Stok Harus Angka Bulat';
				$aler = 'danger';
			}else{
				//update data
				if (!empty($_FILES['fotoBarang']['name'])) {
					//jika file di upload lagi
					//check if file exceed 100 kb and wrong file
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
		    		$extCheck = finfo_file($finfo, $_FILES['fotoBarang']['tmp_name']);
		    		$fileSize = $_FILES['fotoBarang']['size'];
		    		$isSizePass = ($fileSize<=(100*1024)) ? true : false;
		    		finfo_close($finfo);

		    		if (($extCheck!='image/jpg' && $extCheck!='image/jpeg' && $extCheck!='image/png') || $fileSize==0 || $isSizePass==false) {
		    			$ale = 'File Harus berupa PNG/JPG dengan ukuran maksimum 100 kb';
						$aler = 'danger';
						$progHalt = true;	
		    		}else{
		    			//hapus foto terdahulu
		    			unlink('./files/'.$getRowResult['foto_barang']);
						$ext = '.'.pathinfo($_FILES['fotoBarang']['name'], PATHINFO_EXTENSION);
						$filename = $nmBarang.$ext;
						move_uploaded_file($_FILES['fotoBarang']['tmp_name'], './files/'.$filename);
		    		}
				}else{
					//jika file tidak di upload lagi
					$ext = '.'.pathinfo('./files/'.$getRowResult['foto_barang'], PATHINFO_EXTENSION);
					$filename = $nmBarang.$ext;
					rename('./files/'.$getRowResult['foto_barang'], './files/'.$filename);
				}
				if ($progHalt==false) {
					$hrBeli = (int) $hrBeli;
					$hrJual = (int) $hrJual;
					$stok = (int) $stok;
					$update_data = array(
					'nama_barang'	=>	$nmBarang,
					'foto_barang'	=>	$filename,
					'harga_beli'	=>	$hrBeli,
					'harga_jual'	=>	$hrJual,
					'stok'			=>	$stok
				);
				$sqlqueries->updateBarang($sqlconn,$update_data,$getRowResult['id']);
				header("Location: update.php?id=".$getRowResult['id']);
				}
			}
		}
	}else{
		$ale = 'Harap isi formulir dibawah dengan benar';
		$aler = 'danger';	
	}	
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Update Barang</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="container-fluid">
		<div align="center">
			<h1>Update Barang</h1>	
		</div>
		<div class="col-lg-offset-2 col-lg-8 col-md-offset-2 col-md-8">
			<?php if($ale!=null){ ?>
			<div class="alert alert-<?=$aler; ?> alert-dismissible" role="alert">
	  			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  			<?= $ale; ?>
			</div>
			<?php } ?>
			<div class="panel panel-primary">
      			<div class="panel-heading">Update Barang</div>
      			<div class="panel-body">
      				<form action="<?= $_SERVER['PHP_SELF']; ?>?id=<?=$idBarang; ?>" method="post" enctype='multipart/form-data'>
      					<input type="hidden" id="proses" name="proses" value="update">
      					<div class="col-lg-6 col-md-6">
      						<div class="form-group">
      							<label for="nmBarang">Nama Barang</label>
				    			<input type="text" class="form-control" id="nmBarang" name="nmBarang" placeholder="Nama Barang" value="<?=$getRowResult['nama_barang']; ?>" required>
      						</div>
      					</div>
      					<div class="col-lg-6 col-md-6">
      						<div class="form-group">
      							<label for="hrBeli">Harga Beli</label>
				    			<input type="number" class="form-control" id="hrBeli" name="hrBeli" placeholder="Harga Beli" value="<?=$getRowResult['harga_beli']; ?>" required>
      						</div>
      					</div>
      					<div class="col-lg-6 col-md-6">
      						<div class="form-group">
      							<label for="hrJual">Harga Jual</label>
				    			<input type="number" class="form-control" id="hrJual" name="hrJual" placeholder="Harga Jual" value="<?=$getRowResult['harga_jual']; ?>" required>
      						</div>
      					</div>
      					<div class="col-lg-6 col-md-6">
      						<div class="form-group">
      							<label for="stok">Stok</label>
				    			<input type="number" class="form-control" id="stok" name="stok" placeholder="Stok" value="<?=$getRowResult['stok']; ?>" required>
      						</div>
      					</div>
      					<div class="col-lg-12 col-md-12">
					  		<div class="form-group">
					    		<label for="fotoBarang">Foto</label>
					    		<input type="file" id="fotoBarang" name="fotoBarang">
					    		<p class="help-block">Foto harus berupa JPG/PNG dan berukuran maksimum 100 kb</p>
					  		</div>
					  	</div>
					  	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				  			<button type="submit" class="btn btn-primary">Submit</button>
				  		</div>
				  		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">
				  			<a href="index.php" class="btn btn-default">Home</a>
				  		</div>
      				</form>
      			</div>
      		</div>
		</div>
	</div>
</body>
</html>