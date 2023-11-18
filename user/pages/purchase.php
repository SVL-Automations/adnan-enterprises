<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];
$todaydate = date('Y-m-d');



if (isset($_POST['tabledata'])) {
    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");
    $result = mysqli_query($connection, "SELECT p.*,DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, v.name as vendorname, v.mobile as vendormobile from purchase as p
                                            LEFT join vendor as v ON p.vendorid = v.id
                                            WHERE p.status = 1
                                            order by p.date desc ");
    $data->list = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "Select id,name,mobile from vendor where status='1' order by name");
    $data->vendorlist = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}


//Add  Bill
if (isset($_POST['Add'])) {

    $msg = new \stdClass();
    $vendorid = mysqli_real_escape_string($connection, $_POST['vendorid']);
    $date = mysqli_real_escape_string($connection, $_POST['date']);
    $vehicle = mysqli_real_escape_string($connection, $_POST['vehicle']);

    $caretquantity = mysqli_real_escape_string($connection, $_POST['caretquantity']);
    $caretrate = mysqli_real_escape_string($connection, $_POST['caretrate']);

    $box5kgquantity = mysqli_real_escape_string($connection, $_POST['box5kgquantity']);
    $box5kgrate = mysqli_real_escape_string($connection, $_POST['box5kgrate']);

    $paperquantity = mysqli_real_escape_string($connection, $_POST['paperquantity']);
    $paperrate = mysqli_real_escape_string($connection, $_POST['paperrate']);

    $tapequantity = mysqli_real_escape_string($connection, $_POST['tapequantity']);
    $taperate = mysqli_real_escape_string($connection, $_POST['taperate']);

    $tawimquantity = mysqli_real_escape_string($connection, $_POST['tawimquantity']);
    $tawimrate = mysqli_real_escape_string($connection, $_POST['tawimrate']);

    $brboxquantity = mysqli_real_escape_string($connection, $_POST['brboxquantity']);
    $brboxrate = mysqli_real_escape_string($connection, $_POST['brboxrate']);

    $planetboxquantity = 0; //mysqli_real_escape_string($connection, $_POST['planetboxquantity']);
    $planetboxrate = 0; //mysqli_real_escape_string($connection, $_POST['planetboxrate']);

    $whiterimquantity = mysqli_real_escape_string($connection, $_POST['whiterimquantity']);
    $whiterimrate = mysqli_real_escape_string($connection, $_POST['whiterimrate']);

    $pinkrimquantity = mysqli_real_escape_string($connection, $_POST['pinkrimquantity']);
    $pinkrimrate = mysqli_real_escape_string($connection, $_POST['pinkrimrate']);


    $total = mysqli_real_escape_string($connection, $_POST['total']);

    $discount = 0;
    $charges = 0;


    $created = date("Y-m-d H:i:s");
    $updated = date("Y-m-d H:i:s");

    $res = mysqli_query($connection, "INSERT INTO `purchase`(`vendorid`, `date`, `vehicle`, 
                                                            `caret_rate`, `box5kg_rate`, `paper_rate`, `tape_rate`, `tawim_rate`, `brbox_rate`, `planetbox_rate`, `whiterim_rate`,`pinkrim_rate`,
                                                            `caret_quantity`, `box5kg_quantity`, `paper_quantity`, `tape_quantity`, `tawim_quantity`, `brbox_quantity`, `planetbox_quantity`, `whiterim_quantity`,`pinkrim_quantity`,
                                                            `discount`, `other_charges`, `lastupdateby`, `status`,`total`)
                                    VALUES('$vendorid','$date','$vehicle',
                                            '$caretrate','$box5kgrate','$paperrate','$taperate','$tawimrate','$brboxrate','$planetboxrate','$whiterimrate','$pinkrimrate',
                                            '$caretquantity','$box5kgquantity','$paperquantity','$tapequantity','$tawimquantity','$brboxquantity','$planetboxquantity','$whiterimquantity','$pinkrimquantity',
                                            '$discount','$charges','$userid','1','$total')
                                    ");
    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Purchase Bill Added Successfully.";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = "Please Try Again";
        $msg->type = "alert alert-danger alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

//Delete Received Bill
if (isset($_POST['delete'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");

    $id = mysqli_real_escape_string($connection, trim(strip_tags($_POST['deleteid'])));


    $res = mysqli_query($connection, "UPDATE `purchase` SET status=0 WHERE id = '$id' ");

    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Purchase Bill Deleted Successfully.";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = " Some data is missing or Please Try Again.";
        $msg->type = "alert alert-danger alert-dismissible ";
    }


    echo json_encode($msg);
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $project ?> : Add Purchase Details</title>
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
    <!-- DataTables -->
    <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">


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
                    <li><a href="#"> Vendor </a></li>
                    <li class="active"> Purchase </li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"> Purchase Details </h3>
                                <a class="btn btn-social-icon btn-success pull-right" title="Add Purchase" data-toggle="modal" data-target="#modaladdpurchase"><i class="fa fa-plus"></i></a>
                                <a class="btn btn-social-icon btn-warning pull-right" title="Generate Bill" data-toggle="modal" data-target="#modalpurchasebill" style="margin-right: 5px !important;"><i class="fa fa-file-excel-o"></i></a>
                            </div>
                            <div class="alert " id="alertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
                                <p id="msg"></p>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body  table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class='text-center'>Update</th>
                                            <th class='text-center'>SrNo </th>
                                            <th class='text-center'>Date </th>                                            
                                            <th class='text-center'>Vendor Name </th>
                                            <th class='text-center'>Vehicle number </th>
                                            <th class='text-center'>Caret </th>
                                            <th class='text-center'>5KG Box </th>
                                            <th class='text-center'>Paper </th>
                                            <th class='text-center'>Tape </th>
                                            <th class='text-center'>Tawim </th>
                                            <th class='text-center'>BR box </th>
                                            <th class='text-center'>White Rim </th>
                                            <th class='text-center'>Pink Rim </th>
                                            <th class='text-center'>Total </th>
                                        </tr>
                                    </thead>

                                    <tbody id="tbody">

                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th class='text-center'>Update</th>
                                            <th class='text-center'>SrNo </th>
                                            <th class='text-center'>Date </th>
                                            <th class='text-center'>Vendor Name </th>
                                            <th class='text-center'>Vehicle number </th>
                                            <th class='text-center'>Caret </th>
                                            <th class='text-center'>5KG Box </th>
                                            <th class='text-center'>Paper </th>
                                            <th class='text-center'>Tape </th>
                                            <th class='text-center'>Tawim </th>
                                            <th class='text-center'>BR box </th>
                                            <th class='text-center'>White Rim </th>
                                            <th class='text-center'>Pink Rim </th>
                                            <th class='text-center'>Total </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <!-- /.box-footer-->
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- Add  purchase modal -->
        <form id="addpurchase" action="" method="post">
            <div class="modal fade" id="modaladdpurchase" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Add Purchase Details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="addalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                <p id="addmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Vendor Name </label>
                                <select class="form-control select2 " style="width: 100%;" required name="vendorid" id="vendorid">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Date</label>
                                <input type="date" class="form-control" id="date" name="date" max=<?= date('Y-m-d') ?>>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Vehicle Number </label>
                                <input type="text" class="form-control" placeholder="Enter Vehicle number" id="vehicle" name="vehicle" required>
                            </div>

                            <div class="alert alert-info" style="padding: 0px !important;">
                                Caret Details
                            </div>

                            <div class="form-group row ">
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Caret Quantity</label>
                                    <input type="number" step="any" class="form-control" placeholder="Quantity" name="caretquantity" id="caretquantity" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Caret Rate</label>
                                    <input type="number" step="any" class="form-control" placeholder="Rate per item" name="caretrate" id="caretrate" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Total</label>
                                    <input readonly type="number" step="any" class="form-control" placeholder="Total" name="carettotal" id="carettotal" required min="0" value="0">
                                </div>
                            </div>

                            <div class="alert alert-info" style="padding: 0px !important;">
                                5KG Box Details
                            </div>

                            <div class="form-group row ">

                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">5KG Box Quantity</label>
                                    <input type="number" step="any" class="form-control" placeholder="Quantity" name="box5kgquantity" id="box5kgquantity" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">5KG Box Rate</label>
                                    <input type="number" step="any" class="form-control" placeholder="Rate per item" name="box5kgrate" id="box5kgrate" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Total</label>
                                    <input readonly type="number" step="any" class="form-control" placeholder="Total" name="box5kgtotal" id="box5kgtotal" required min="0" value="0">
                                </div>

                            </div>

                            <div class="alert alert-info" style="padding: 0px !important;">
                                Paper Details
                            </div>

                            <div class="form-group row ">
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Paper Quantity</label>
                                    <input type="number" step="any" class="form-control" placeholder="Quantity" name="paperquantity" id="paperquantity" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Paper Rate</label>
                                    <input type="number" step="any" class="form-control" placeholder="Rate per item" name="paperrate" id="paperrate" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Total</label>
                                    <input readonly type="number" step="any" class="form-control" placeholder="Total" name="papertotal" id="papertotal" required min="0" value="0">
                                </div>

                            </div>

                            <div class="alert alert-info" style="padding: 0px !important;">
                                Tape Details
                            </div>

                            <div class="form-group row ">
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Tape Quantity</label>
                                    <input type="number" step="any" class="form-control" placeholder="Quantity" name="tapequantity" id="tapequantity" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Tape Rate</label>
                                    <input type="number" step="any" class="form-control" placeholder="Rate per item" name="taperate" id="taperate" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Total</label>
                                    <input readonly type="number" step="any" class="form-control" placeholder="Total" name="tapetotal" id="tapetotal" required min="0" value="0">
                                </div>

                            </div>

                            <div class="alert alert-info" style="padding: 0px !important;">
                                Tawim Details
                            </div>

                            <div class="form-group row ">
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Tawim Quantity</label>
                                    <input type="number" step="any" class="form-control" placeholder="Quantity" name="tawimquantity" id="tawimquantity" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Tawim Rate</label>
                                    <input type="number" step="any" class="form-control" placeholder="Rate per item" name="tawimrate" id="tawimrate" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Total</label>
                                    <input readonly type="number" step="any" class="form-control" placeholder="Total" name="tawimtotal" id="tawimtotal" required min="0" value="0">
                                </div>

                            </div>

                            <div class="alert alert-info" style="padding: 0px !important;">
                                BR box Details
                            </div>

                            <div class="form-group row ">
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">BR box Quantity</label>
                                    <input type="number" step="any" class="form-control" placeholder="Quantity" name="brboxquantity" id="brboxquantity" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1"> BR box Rate</label>
                                    <input type="number" step="any" class="form-control" placeholder="Rate per item" name="brboxrate" id="brboxrate" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Total</label>
                                    <input readonly type="number" step="any" class="form-control" placeholder="Total" name="brboxtotal" id="brboxtotal" required min="0" value="0">
                                </div>

                            </div>

                            <div class="alert alert-info" style="padding: 0px !important;">
                                White Rim Details
                            </div>

                            <div class="form-group row ">
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">White Rim Quantity</label>
                                    <input type="number" step="any" class="form-control" placeholder="Quantity" name="whiterimquantity" id="whiterimquantity" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">White Rim Rate</label>
                                    <input type="number" step="any" class="form-control" placeholder="Rate per item" name="whiterimrate" id="whiterimrate" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Total</label>
                                    <input readonly type="number" step="any" class="form-control" placeholder="Total" name="whiterimtotal" id="whiterimtotal" required min="0" value="0">
                                </div>

                            </div>

                            <div class="alert alert-info" style="padding: 0px !important;">
                                Pink Rim Details
                            </div>

                            <div class="form-group row ">
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Pink Rim Quantity</label>
                                    <input type="number" step="any" class="form-control" placeholder="Quantity" name="pinkrimquantity" id="pinkrimquantity" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Pink Rim Rate</label>
                                    <input type="number" step="any" class="form-control" placeholder="Rate per item" name="pinkrimrate" id="pinkrimrate" required min="0" value="0">
                                </div>
                                <div class="col-lg-4 col-xs-12">
                                    <label for="exampleInputEmail1">Total</label>
                                    <input readonly type="number" step="any" class="form-control" placeholder="Total" name="pinkrimtotal" id="pinkrimtotal" required min="0" value="0">
                                </div>

                            </div>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Total</label>
                                <input readonly type="number" step="any" class="form-control" placeholder="Other charges" name="total" id="total" required min="0" value="0">
                            </div>

                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Add" value="Add">
                            <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Add Purchase</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add purchase modal -->

         <!-- Add  purchase bill modal -->
         <form id="generatepurchasebill" action="purchasebill.php" method="get">
            <div class="modal fade" id="modalpurchasebill" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Generate Bill</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="addalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                <p id="addmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Vendor Name </label>
                                <select class="form-control select2 " style="width: 100%;" required name="vendorid" id="vendorid">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Start Date</label>
                                <input type="date" class="form-control" id="sdate" name="sdate" max=<?= date('Y-m-d') ?>>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">End Date</label>
                                <input type="date" class="form-control" id="edate" name="edate" max=<?= date('Y-m-d') ?>>
                            </div>
                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Add" value="Add">
                            <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Generate Bill</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add sales bill modal -->

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
    <!-- Select2 -->
    <script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>

    <script>
        $(document).ready(function() {

            $('.sidebar-menu').tree()

            $('[data-toggle="tooltip"]').tooltip();

            //display data table
            function tabledata() {
                $('#vendorid').empty();
                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'tabledata': 'tabledata'
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        //console.log(returnedData);
                        var srno = 0;
                        $.each(returnedData['list'], function(key, value) {
                            srno++;
                            button1 = '';
                            button2 = '';


                            button1 = '<button type="submit" name="Delete" id="Delete" ' +
                                'data-deleteid="' + value.id + '"' +
                                '" class="btn btn-xs btn-danger delete-button" style= "margin:5px" title=" Delete purchase bill " ><i class="fa fa-times"></i></button>';
                            // button2 = '<a ' +
                            //     'data-viewid="' + value.id + '"' +
                            //     '" class="btn btn-xs btn-success view-button" style= "margin:5px" title=" View purchase bill"  href="rptpurchasebill.php?id=' + value.id + '" ><i class="fa fa-eye"></i></a>';


                            var caretTotal = parseFloat(value.caret_rate) * parseFloat(value.caret_quantity);
                            var box5kgTotal = parseFloat(value.box5kg_rate) * parseFloat(value.box5kg_quantity);
                            var paperTotal = parseFloat(value.paper_rate) * parseFloat(value.paper_quantity);
                            var tapeTotal = parseFloat(value.tape_rate) * parseFloat(value.tape_quantity);
                            var tawimTotal = parseFloat(value.tawim_rate) * parseFloat(value.tawim_quantity);
                            var brboxTotal = parseFloat(value.brbox_rate) * parseFloat(value.brbox_quantity);
                            // var planetTotal = parseFloat(value.planetbox_rate) * parseFloat(value.planetbox_quantity);
                            var whiterimTotal = parseFloat(value.whiterim_rate) * parseFloat(value.whiterim_quantity);
                            var pinkrimTotal = parseFloat(value.pinkrim_rate) * parseFloat(value.pinkrim_quantity);

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-center">' + button1 + button2 + '</td>' +
                                '<td class="text-center">' + srno + '</td>' +
                                '<td class="text-center">' + value.niceDate + '</td>' +
                                '<td class="text-center">' + value.vendorname + '</td>' +                                
                                '<td class="text-center">' + value.vehicle + '</td>' +

                                // '<td class="text-center">' + value.caret_rate + '</td>' +
                                '<td class="text-center">' + value.caret_quantity + '</td>' +
                                // '<td class="text-center">' + parseFloat(value.caret_rate) * parseFloat(value.caret_quantity) + '</td>' +

                                // '<td class="text-center">' + value.box5kg_rate + '</td>' +
                                '<td class="text-center">' + value.box5kg_quantity + '</td>' +
                                // '<td class="text-center">' + parseFloat(value.box5kg_rate) * parseFloat(value.box5kg_quantity) + '</td>' +

                                // '<td class="text-center">' + value.paper_rate + '</td>' +
                                '<td class="text-center">' + value.paper_quantity + '</td>' +
                                // '<td class="text-center">' + parseFloat(value.paper_rate) * parseFloat(value.paper_quantity) + '</td>' +

                                // '<td class="text-center">' + value.tape_rate + '</td>' +
                                '<td class="text-center">' + value.tape_quantity + '</td>' +
                                // '<td class="text-center">' + parseFloat(value.tape_rate) * parseFloat(value.tape_quantity) + '</td>' +

                                // '<td class="text-center">' + value.tawim_rate + '</td>' +
                                '<td class="text-center">' + value.tawim_quantity + '</td>' +
                                // '<td class="text-center">' + parseFloat(value.tawim_rate) * parseFloat(value.tawim_quantity) + '</td>' +

                                // '<td class="text-center">' + value.brbox_rate + '</td>' +
                                '<td class="text-center">' + value.brbox_quantity + '</td>' +
                                // '<td class="text-center">' + parseFloat(value.brbox_rate) * parseFloat(value.brbox_quantity) + '</td>' +

                                // '<td class="text-center">' + value.planetbox_rate + '</td>' +
                                // '<td class="text-center">' + value.planetbox_quantity + '</td>' +
                                // '<td class="text-center">' + parseFloat(value.planetbox_rate) * parseFloat(value.planetbox_quantity) + '</td>' +                                

                                // '<td class="text-center">' + value.whiterim_rate + '</td>' +
                                '<td class="text-center">' + value.whiterim_quantity + '</td>' +
                                // '<td class="text-center">' + parseFloat(value.whiterim_rate) * parseFloat(value.whiterim_quantity) + '</td>' +

                                // '<td class="text-center">' + value.pinkrim_rate + '</td>' +
                                '<td class="text-center">' + value.pinkrim_quantity + '</td>' +
                                // '<td class="text-center">' + parseFloat(value.pinkrim_rate) * parseFloat(value.pinkrim_quantity) + '</td>' +

                                '<td class="text-center">' + parseFloat(parseFloat(caretTotal) + parseFloat(box5kgTotal) + parseFloat(paperTotal) + parseFloat(tapeTotal) + parseFloat(tawimTotal) + parseFloat(brboxTotal) + parseFloat(whiterimTotal) + parseFloat(pinkrimTotal)) + '</td>' +
                                //'<td class="text-center">' + value.lastupdate + '</td>' +

                                '</tr>';
                            $('#example1 tbody').append(html);
                        });

                        $('.select2').append(new Option("Select Vendor", ""));


                        $.each(returnedData['vendorlist'], function(key, value) {
                            $('.select2').append(new Option(value.name, value.id));
                        });

                        $('#example1').DataTable({
                            stateSave: true,
                            destroy: true,
                        });
                        //Initialize Select2 Elements
                        $('.select2').select2()
                    }
                });
            }

            tabledata();



            $(document).on("click", ".delete-button", function(e) {

                var id = $(this).data('deleteid');
                $('#alertclass').removeClass();
                $('#msg').empty();
                e.preventDefault();

                if (confirm('Are you sure to remove this record ?')) {

                    $.ajax({
                        url: $(location).attr('href'),
                        dataType: "json",
                        type: 'POST',
                        data: {
                            delete: 'delete',
                            deleteid: id
                        },
                        encode: true,
                        success: function(response) {
                            //console.log(response);                      
                            var returnedData = response;

                            if (returnedData['value'] == 1) {
                                $('#alertclass').addClass(returnedData['type']);
                                $('#msg').append(returnedData['data']);
                                $("#alertclass").show();
                                tabledata();
                            } else {
                                $('#alertclass').addClass(returnedData['type']);
                                $('#msg').append(returnedData['data']);
                                $("#alertclass").show();
                                tabledata();
                            }
                        }
                    });
                }
            });

            //add purchase bill
            $('#addpurchase').submit(function(e) {
                $('#add').prop('disabled', true);
                $('#addalertclass').removeClass();
                $('#addmsg').empty();

                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: $('#addpurchase').serialize(),
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        // console.log(returnedData);
                        $('#add').prop('disabled', false);
                        if (returnedData['value'] == 1) {
                            $('#addpurchase')[0].reset();
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                            tabledata();
                        } else {
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                        }
                    }
                });

            });


            //Calculation
            //caretrate
            $("#caretrate").change(function(e) {
                $("#carettotal").val(
                    (isNaN(parseFloat($("#caretrate").val())) ? 0 : parseFloat($("#caretrate").val())) *
                    (isNaN(parseFloat($("#caretquantity").val())) ? 0 : parseFloat($("#caretquantity").val())));
                totalCalculation();
            });

            $("#caretquantity").change(function(e) {
                $("#carettotal").val(
                    (isNaN(parseFloat($("#caretrate").val())) ? 0 : parseFloat($("#caretrate").val())) *
                    (isNaN(parseFloat($("#caretquantity").val())) ? 0 : parseFloat($("#caretquantity").val())));
                totalCalculation();
            });

            //5KG Box
            $("#box5kgrate").change(function(e) {
                $("#box5kgtotal").val(
                    (isNaN(parseFloat($("#box5kgrate").val())) ? 0 : parseFloat($("#box5kgrate").val())) *
                    (isNaN(parseFloat($("#box5kgquantity").val())) ? 0 : parseFloat($("#box5kgquantity").val())));
                totalCalculation();
            });

            $("#box5kgquantity").change(function(e) {
                $("#box5kgtotal").val(
                    (isNaN(parseFloat($("#box5kgrate").val())) ? 0 : parseFloat($("#box5kgrate").val())) *
                    (isNaN(parseFloat($("#box5kgquantity").val())) ? 0 : parseFloat($("#box5kgquantity").val())));
                totalCalculation();
            });

            //Paper
            $("#paperrate").change(function(e) {
                $("#papertotal").val(
                    (isNaN(parseFloat($("#paperrate").val())) ? 0 : parseFloat($("#paperrate").val())) *
                    (isNaN(parseFloat($("#paperquantity").val())) ? 0 : parseFloat($("#paperquantity").val())));
                totalCalculation();
            });

            $("#paperquantity").change(function(e) {
                $("#papertotal").val(
                    (isNaN(parseFloat($("#paperrate").val())) ? 0 : parseFloat($("#paperrate").val())) *
                    (isNaN(parseFloat($("#paperquantity").val())) ? 0 : parseFloat($("#paperquantity").val())));
                totalCalculation();
            });

            //Tape
            $("#taperate").change(function(e) {
                $("#tapetotal").val(
                    (isNaN(parseFloat($("#taperate").val())) ? 0 : parseFloat($("#taperate").val())) *
                    (isNaN(parseFloat($("#tapequantity").val())) ? 0 : parseFloat($("#tapequantity").val())));
                totalCalculation();
            });

            $("#tapequantity").change(function(e) {
                $("#tapetotal").val(
                    (isNaN(parseFloat($("#taperate").val())) ? 0 : parseFloat($("#taperate").val())) *
                    (isNaN(parseFloat($("#tapequantity").val())) ? 0 : parseFloat($("#tapequantity").val())));
                totalCalculation();
            });

            //Tawim
            $("#tawimrate").change(function(e) {
                $("#tawimtotal").val(
                    (isNaN(parseFloat($("#tawimrate").val())) ? 0 : parseFloat($("#tawimrate").val())) *
                    (isNaN(parseFloat($("#tawimquantity").val())) ? 0 : parseFloat($("#tawimquantity").val())));
                totalCalculation();
            });

            $("#tawimquantity").change(function(e) {
                $("#tawimtotal").val(
                    (isNaN(parseFloat($("#tawimrate").val())) ? 0 : parseFloat($("#tawimrate").val())) *
                    (isNaN(parseFloat($("#tawimquantity").val())) ? 0 : parseFloat($("#tawimquantity").val())));
                totalCalculation();
            });

            //BR box

            $("#brboxrate").change(function(e) {
                $("#brboxtotal").val(
                    (isNaN(parseFloat($("#brboxrate").val())) ? 0 : parseFloat($("#brboxrate").val())) *
                    (isNaN(parseFloat($("#brboxquantity").val())) ? 0 : parseFloat($("#brboxquantity").val())));
                totalCalculation();
            });


            $("#brboxquantity").change(function(e) {
                $("#brboxtotal").val(
                    (isNaN(parseFloat($("#brboxrate").val())) ? 0 : parseFloat($("#brboxrate").val())) *
                    (isNaN(parseFloat($("#brboxquantity").val())) ? 0 : parseFloat($("#brboxquantity").val())));
                totalCalculation();
            });

            //planetbox

            // $("#planetboxrate").change(function(e) {
            //     $("#planetboxtotal").val(
            //         (isNaN(parseFloat($("#planetboxrate").val())) ? 0 : parseFloat($("#planetboxrate").val())) *
            //         (isNaN(parseFloat($("#planetboxquantity").val())) ? 0 : parseFloat($("#planetboxquantity").val())));
            //     totalCalculation();
            // });

            // $("#planetboxquantity").change(function(e) {
            //     $("#planetboxtotal").val(
            //         (isNaN(parseFloat($("#planetboxrate").val())) ? 0 : parseFloat($("#planetboxrate").val())) *
            //         (isNaN(parseFloat($("#planetboxquantity").val())) ? 0 : parseFloat($("#planetboxquantity").val())));
            //     totalCalculation();
            // });

            //whiterim

            $("#whiterimrate").change(function(e) {
                $("#whiterimtotal").val(
                    (isNaN(parseFloat($("#whiterimrate").val())) ? 0 : parseFloat($("#whiterimrate").val())) *
                    (isNaN(parseFloat($("#whiterimquantity").val())) ? 0 : parseFloat($("#whiterimquantity").val())));
                totalCalculation();
            });

            $("#whiterimquantity").change(function(e) {
                $("#whiterimtotal").val(
                    (isNaN(parseFloat($("#whiterimrate").val())) ? 0 : parseFloat($("#whiterimrate").val())) *
                    (isNaN(parseFloat($("#whiterimquantity").val())) ? 0 : parseFloat($("#whiterimquantity").val())));
                totalCalculation();
            });

            //pinkrim

            $("#pinkrimrate").change(function(e) {
                $("#pinkrimtotal").val(
                    (isNaN(parseFloat($("#pinkrimrate").val())) ? 0 : parseFloat($("#pinkrimrate").val())) *
                    (isNaN(parseFloat($("#pinkrimquantity").val())) ? 0 : parseFloat($("#pinkrimquantity").val())));
                totalCalculation();
            });

            $("#pinkrimquantity").change(function(e) {
                $("#pinkrimtotal").val(
                    (isNaN(parseFloat($("#pinkrimrate").val())) ? 0 : parseFloat($("#pinkrimrate").val())) *
                    (isNaN(parseFloat($("#pinkrimquantity").val())) ? 0 : parseFloat($("#pinkrimquantity").val())));
                totalCalculation();
            });

            function totalCalculation() {
                $("#total").val(
                    (isNaN(parseFloat($("#carettotal").val())) ? 0 : parseFloat($("#carettotal").val())) +
                    (isNaN(parseFloat($("#box5kgtotal").val())) ? 0 : parseFloat($("#box5kgtotal").val())) +
                    (isNaN(parseFloat($("#papertotal").val())) ? 0 : parseFloat($("#papertotal").val())) +
                    (isNaN(parseFloat($("#tapetotal").val())) ? 0 : parseFloat($("#tapetotal").val())) +
                    (isNaN(parseFloat($("#tawimtotal").val())) ? 0 : parseFloat($("#tawimtotal").val())) +
                    (isNaN(parseFloat($("#brboxtotal").val())) ? 0 : parseFloat($("#brboxtotal").val())) +
                    (isNaN(parseFloat($("#whiterimtotal").val())) ? 0 : parseFloat($("#whiterimtotal").val())) +
                    (isNaN(parseFloat($("#pinkrimtotal").val())) ? 0 : parseFloat($("#pinkrimtotal").val()))
                    // (isNaN(parseFloat($("#planetboxtotal").val())) ? 0 : parseFloat($("#planetboxtotal").val()))
                );
            }
        })
    </script>
</body>

</html>