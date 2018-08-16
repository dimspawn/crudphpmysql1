<?php include 'config/sqlquery.php';
  $page = 1; //default
  $rowPage = 5; //default
  $limPage = 5; //default
  $limPageMin2 = $limPage - 2;
  $midPage = ceil($limPage / 2);
  $offsetPage = $limPage - $midPage;
  if (isset($_GET['page'])) {
    $page = (is_numeric($_GET['page'])) ? (int) $_GET['page'] : 1;
  }
  if (isset($_GET['rpage'])) {
    $rowPage = (is_numeric($_GET['rpage'])) ? (int) $_GET['rpage'] : 2;
  }
  $offset = ($page * $rowPage) - $rowPage;
  if (!isset($_GET['search'])) {
    $sqlresult = $sqlqueries->queryAll($sqlconn, $offset, $rowPage);
    $dataCount = $sqlqueries->countAll($sqlconn);
    $homebtn = false;
    $clsbtn = "col-lg-offset-6 col-lg-6";
  } else {
    $search = htmlspecialchars($_GET['search']);
    $search = mysqli_real_escape_string($sqlconn, $search);
    $sqlresult = $sqlqueries->querySearchAll($sqlconn, $offset, $rowPage, $search);
    $dataCount = $sqlqueries->countSearchAll($sqlconn, $search);
    $homebtn = true;
    $clsbtn = "col-lg-6";
  }
  $maxPage = ceil($dataCount/$rowPage);
  $page = ($page <=0) ? 1 : $page;
  $page = ($page >=$maxPage) ? $maxPage : $page;
  
  
  if ($maxPage<=$limPage) {
    $startPage = 1;
    $endPage = $maxPage;
  }else{
    $startPageOffset = $midPage;
    $endPageOffset = $maxPage - $offsetPage;
    if ($page<=$startPageOffset) {
      $startPage = 1;
    }elseif($page>=$endPageOffset){
      $startPage = $endPageOffset - $offsetPage;
    }else{
      $startPage = $page - $offsetPage;
    }
    $endPage = $startPage + ($limPage - 1);
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Test Praktek</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="container-fluid">
		<div align="center">
			<h1>Tabel Barang</h1>	
		</div>
    <div class="row">
      <?php if($homebtn){ ?>
      <div class="col-lg-6">
        <a href="index.php" class="btn btn-primary">Home</a>
      </div>
      <?php } ?>
      <div class="<?= $clsbtn; ?>">
        <form action="index.php" method="GET">
          <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" <?= (isset($_GET['search'])) ? 'value="'.$_GET['search'].'"' : ''; ?> placeholder="Search for...">
            <span class="input-group-btn">
              <button class="btn btn-default" type="submit">Go!</button>
            </span>
          </div><!-- /input-group -->
        </form>
      </div><!-- /.col-lg-6 -->   
    </div>
    <?php if ($sqlresult!=null) { ?>
		<table class="table">
			<thead>
	    	<tr>
          <th>No.</th>
	        <th>Nama Barang</th>
          <th colspan="3" style="text-align: center;">Action</th>
	      </tr>
    	</thead>
  		<tbody>
        <?php $k=0;foreach ($sqlresult as $sqres) { ?>
  			<tr>
          <td><?=$k+1; ?></td>
    			<td><?= $sqres['nama_barang'] ?></td>
          <td><button class="btn btn-default" data-toggle="modal" data-target="#myModal<?=$k;?>">Detail</button></td>
          <td><a href="update.php?id=<?=$sqres['id']; ?>" class="btn btn-primary">Update</a></td>
          <td>
            <form action="delete.php" method="post" onsubmit="return confirm('Yakin ingin Hapus?');">
              <input type="hidden" id="barangid" name="barangid" value="<?=$sqres['id']; ?>">
              <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </td>
  			</tr>
        <?php $k++;} ?>
  		</tbody>
		</table>
    <!--pagination-->
    <?php if($maxPage >= 2){ ?>
    <div align="center">
      <nav aria-label="Page navigation">
        <ul class="pagination">
          <li <?= ($page==1) ? 'class="disabled"':''; ?>>
            <a href="index.php?page=1" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>
          <?php for ($i=$startPage; $i <= $endPage; $i++) { ?>
            <li <?= ($page==$i) ? 'class="active"':''; ?>><a href="index.php?page=<?=$i; ?>"><?=$i; ?></a></li>
          <?php } ?>
          <li <?= ($page==$maxPage) ? 'class="disabled"':''; ?>>
            <a href="index.php?page=<?=$maxPage;?>" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
    <?php } ?>
    <?php $i=0;foreach ($sqlresult as $sqres) { ?>
     <!-- Modal -->
    <div class="modal fade" id="myModal<?=$i; ?>" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Read</h4>
          </div>
          <div class="modal-body">
            <div class="row" align="center">
              <img style="max-width: 100%;" src="./files/<?=$sqres['foto_barang']; ?>" alt="images not found">    
            </div>
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6">
                <strong>Nama Barang:</strong>
                <?= $sqres['nama_barang'] ?>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <strong>Stok:</strong>
                <?= $sqres['stok'] ?>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <strong>Harga Beli:</strong>
                <?= $sqres['harga_beli'] ?>
              </div>  
              <div class="col-lg-6 col-md-6 col-sm-6">
                <strong>Harga Jual:</strong>
                <?= $sqres['harga_jual'] ?>
              </div>      
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <?php $i++;} ?>	
    <?php } else{ ?>
      <div align="center">
        <h1>Empty Database</h1>  
      </div>
    <?php } ?>
	</div>
  <div align="center">
    <a href="create.php" class="btn btn-primary">Input Barang</a>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>