<?php

include("sessioncheck.php");

$addedby = $_SESSION['userid'];
date_default_timezone_set('Asia/Kolkata');

if (isset($_POST['data'])) {
  $data = new \stdClass();
  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select count(*) as customercount from customer where status = 1");
  $data->customercount = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select count(*) as vendorcount from vendor where status = 1");
  $data->vendorcount = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select count(*) as salescount from sales where status = 1");
  $data->salescount = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select count(*) as purchasecount from purchase where status = 1");
  $data->purchasecount = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "SELECT (SELECT IFNULL(SUM(s.total),0) FROM sales as s WHERE s.status=1) as salesTotal,
                                              (SELECT IFNULL(SUM(r.amount),0) FROM payment_received as r WHERE r.status=1 ) as receivedTotal,
                                              (SELECT IFNULL(SUM(c.pending),0) FROM customer as c WHERE c.status=1 ) as pendingTotal");
  $data->customerPending = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "SELECT (SELECT IFNULL(SUM(p.total),0) FROM purchase as p WHERE p.status=1) as purchaseTotal,
                                              (SELECT IFNULL(SUM(s.amount),0) FROM payment_send as s WHERE s.status=1 ) as sendTotal,
                                              (SELECT IFNULL(SUM(v.pending),0) FROM vendor as v WHERE v.status=1 ) as pendingTotal");
  $data->vendorPending = mysqli_fetch_all($result, MYSQLI_ASSOC);



  echo json_encode($data);
  exit();
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $project ?> : Dashboard</title>
  <link rel="icon" href="../../dist/img/small.png" type="image/x-icon">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

  <!-- daterange picker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">

    <?php include("header.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h4>
          <?= $project ?>
          <small><?= $slogan ?></small>
        </h4>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">User</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3 id="customercount">00</h3>

                <p>Total Customers</p>
              </div>
              <div class="icon">
                <i class="fa fa-users"></i>
              </div>
              <a href="customer.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->          
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3 id="salescount">00</h3>

                <p>Total Bills</p>
              </div>
              <div class="icon">
                <i class="fa fa-file-excel-o"></i>
              </div>
              <a href="sales.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
                <h3 id="customerPending">00</h3>

                <p>Total Pending Customer</p>
              </div>
              <div class="icon">
                <i class="fa fa-inr"></i>
              </div>
              <a href="customer.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3 id="vendorcount">00</h3>

                <p>Total Vendors</p>
              </div>
              <div class="icon">
                <i class="fa fa-user-secret"></i>
              </div>
              <a href="vendors.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3 id="purchasecount">00</h3>

                <p>Total Purchase</p>
              </div>
              <div class="icon">
                <i class="fa fa-cart-plus"></i>
              </div>
              <a href="purchase.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-maroon">
              <div class="inner">
                <h3 id="vendorPending">00</h3>

                <p>Total Pending Vendor</p>
              </div>
              <div class="icon">
                <i class="fa fa-inr"></i>
              </div>
              <a href="vendors.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include("footer.php"); ?>

  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- SlimScroll -->
  <script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="../../bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="../../dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../../dist/js/demo.js"></script>
  <!-- DataTables -->
  <script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <!-- date-range-picker -->
  <script src="../../bower_components/moment/min/moment.min.js"></script>
  <script src="../../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap datepicker -->
  <script src="../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.sidebar-menu').tree()

      //display data card
      function tabledata() {
        $.ajax({
          url: $(location).attr('href'),
          type: 'POST',
          data: {
            'data': 'data'
          },
          success: function(response) {
            // console.log(response); 
            var returnedData = JSON.parse(response);
            console.log(returnedData);            
            $('#customercount').text(returnedData['customercount'][0]['customercount']);
            $('#vendorcount').text(returnedData['vendorcount'][0]['vendorcount']);
            $('#salescount').text(returnedData['salescount'][0]['salescount']);
            $('#purchasecount').text(returnedData['purchasecount'][0]['purchasecount']);

            var cPending = parseFloat(returnedData['customerPending'][0]['salesTotal']) + parseFloat(returnedData['customerPending'][0]['pendingTotal']) - parseFloat(returnedData['customerPending'][0]['receivedTotal']);
            $('#customerPending').text(parseFloat(cPending).toLocaleString('en-IN') );

            var vPending = parseFloat(returnedData['vendorPending'][0]['purchaseTotal']) + parseFloat(returnedData['vendorPending'][0]['pendingTotal']) - parseFloat(returnedData['vendorPending'][0]['sendTotal']);
            $('#vendorPending').text(parseFloat(vPending).toLocaleString('en-IN'));
          }
        });
      }

      tabledata();
    })
  </script>
</body>

</html>