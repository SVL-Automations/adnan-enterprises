<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];

$todaydate = date('Y-m-d');
$today = date('Y-m-d') . ' 00:00:00';
$yesterday = date('Y-m-d', strtotime("-1 days")) . ' 00:00:00';
$firstday = date('Y-m-01');
$firstdaylastmonth =  date("Y-n-j", strtotime("first day of previous month"));
$lastdaylastmont = date("Y-n-j", strtotime("last day of previous month"));


if (isset($_POST['tabledata'])) {
    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");
    $result = mysqli_query($connection, "SELECT ra.*,l.name as receivedname,c.billname as billname FROM `received_amount` as ra 
                                        LEFT JOIN `login` as l ON ra.receivedby = l.id
                                        LEFT JOIN customer as c ON ra.customerid = c.id
                                        order by ra.date DESC");
    $data->list = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "Select id,billname from customer where status='1' order by billname");
    $data->custlist = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "Select id, name from login where status='1' order by name");
    $data->recvlist = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($data);
    exit();
}


//Add Receive Bill
if (isset($_POST['Add'])) {

    $msg = new \stdClass();

    $customerid = mysqli_real_escape_string($connection, $_POST['customerid']);
    $type = mysqli_real_escape_string($connection, $_POST['type']);
    $amount = mysqli_real_escape_string($connection, $_POST['amount']);
    $receivedby = mysqli_real_escape_string($connection, $_POST['receivedby']);
    $remark = mysqli_real_escape_string($connection, $_POST['remark']);
    $date = mysqli_real_escape_string($connection, $_POST['date']);
    $created = date("Y-m-d H:i:s");
    $updated = date("Y-m-d H:i:s");

    $res = mysqli_query($connection, "INSERT INTO `received_amount`
                                            (`customerid`, `type`, `amount`, `receivedby`, `date`,
                                            `remark`, `created`, `updated`, `status`)
                                    VALUES('$customerid','$type','$amount','$receivedby','$date',
                                            '$remark','$created','$updated','1')
                                    ");
    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Received Amount Added Successfully";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = "Please Try Again";
        $msg->type = "alert alert-danger alert-dismissible ";
    }
    echo json_encode($msg);
    exit();
}

//Edit Received Bill
if (isset($_POST['Edit'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");

    $id = mysqli_real_escape_string($connection, trim(strip_tags($_POST['id'])));
    $editcustomerid = mysqli_real_escape_string($connection, $_POST['editcustomerid']);
    $edittype = mysqli_real_escape_string($connection, $_POST['edittype']);
    $editamount = mysqli_real_escape_string($connection, $_POST['editamount']);
    $editreceivedby = mysqli_real_escape_string($connection, $_POST['editreceivedby']);
    $editremark = mysqli_real_escape_string($connection, $_POST['editremark']);
    $editdate = mysqli_real_escape_string($connection, $_POST['editdate']);
    $created = date("Y-m-d H:i:s");
    $updated = date("Y-m-d H:i:s");

    $res = mysqli_query($connection, "UPDATE `received_amount` SET
                                            `type`='$edittype', `amount`='$editamount', `receivedby`='$editreceivedby',
                                             `date`='$editdate',`remark`='$editremark', `updated`='$updated' 
                                             where id = '$id'                                           
                                    ");

    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Received Bill Update Successfully.";
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
    <title><?= $project ?> : Add Received Bill</title>
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
    <style>
        tfoot input {
            width: 50%;
            padding: 3px;
            box-sizing: border-box;
        }
    </style>
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
                    <li><a href="#"> Customer </a></li>
                    <li class="active"> Received Bill </li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Received Bill Details </h3>
                                <a class="btn btn-social-icon btn-success pull-right" title="Add Received Bill" data-toggle="modal" data-target="#modaladdrbill"><i class="fa fa-plus"></i></a>
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
                                            <th class='text-center'>SrNo </th>
                                            <th class='text-center'>Customer Name </th>
                                            <th class='text-center'>Date </th>
                                            <th class='text-center'>Type </th>
                                            <th class='text-center'>Amount </th>
                                            <th class='text-center'>Received By</th>
                                            <th class='text-center'>Remark</th>
                                            <th class='text-center'>Last Update </th>
                                            <th class='text-center'>Update</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class='text-center'>SrNo </th>
                                            <th class='text-center'>Customer Name </th>
                                            <th class='text-center'>Date </th>
                                            <th class='text-center'>Type </th>
                                            <th class='text-center'>Amount </th>
                                            <th class='text-center'>Received By</th>
                                            <th class='text-center'>Remark</th>
                                            <th class='text-center'>Last Update </th>
                                            <th class='text-center'>Update</th>
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
        <!-- Add received bill modal -->
        <form id="addrbill" action="" method="post">
            <div class="modal fade" id="modaladdrbill" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Add Received Bill Details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="addalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                <p id="addmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Customer Name </label>
                                <select class="form-control select2 " style="width: 100%;" required name="customerid" id="customerid">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Date</label>
                                <input type="date" class="form-control" id="date" name="date" max=<?=date('Y-m-d')?>>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Type </label>
                                <select class="form-control select2 " style="width: 100%;" required name="type" id="type">
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Online">Online</option>
                                    <option value="UPI">UPI</option>                                    
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Amount</label>
                                <input type="number" step="any" class="form-control" placeholder="Enter Amount" id="amount" name="amount" required min="0">
                            </div>                           

                            <div class="form-group">
                                <label for="exampleInputEmail1">Remark</label>
                                <textarea class="form-control" rows="3" placeholder="Enter Remark" name="remark" id='remark' required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Received By</label>
                                <select class="form-control select2 " style="width: 100%;" required name="receivedby" id="receivedby">
                                </select>
                            </div>


                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Add" value="Add">
                            <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Add Received Bill</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add Received bill modal -->

        <!-- Edit Received bill modal -->
        <form id="editrbill" action="" method="post">
            <div class="modal fade" id="modaleditrbill" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-red">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Customer Edit</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="editalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#editalertclass').hide()">×</button>
                                <p id="editmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Customer Name </label>                                
                                <input type="text" class="form-control" placeholder="Enter Amount" id="editcustomerid" name="editcustomerid" readonly>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Date</label>
                                <input type="date" class="form-control" id="editdate" name="editdate" max=<?=date('Y-m-d')?>>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Type </label>
                                <select class="form-control select2 " style="width: 100%;" required name="edittype" id="edittype">
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Online">Online</option>
                                    <option value="UPI">UPI</option>                                    
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Amount</label>
                                <input type="number" step="any" class="form-control" placeholder="Enter Amount" id="editamount" name="editamount" required min="0">
                            </div>                           

                            <div class="form-group">
                                <label for="exampleInputEmail1">Remark</label>
                                <textarea class="form-control" rows="3" placeholder="Enter Remark" name="editremark" id='editremark' required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Received By</label>
                                <select class="form-control select2 " style="width: 100%;" required name="editreceivedby" id="editreceivedby">
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="id" id="editid">
                            <input type="hidden" name="Edit" value="Edit">
                            <button type="submit" name="Edit" value="Edit" id='Edit' class="btn btn-success">Edit Received Bill</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Edit Admin modal -->
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
                $('#receivedby').empty();
                $('#editreceivedby').empty();
                $('#customerid').empty();
               

                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

                $.ajax({
                    url: 'receivedbill.php',
                    type: 'POST',
                    data: {
                        'tabledata': 'tabledata'
                    },
                    success: function(response) {
                         //console.log(response); 
                        var returnedData = JSON.parse(response);
                        // console.log(returnedData);
                        var srno = 0;
                        $.each(returnedData['list'], function(key, value) {
                            srno++;
                            button1 = '';

                            button1 = '<button type="submit" name="Edit" id="Edit" ' +
                                'data-editid="' + value.id + '" data-customerid="' + value.billname +
                                '" data-receivedby="' + value.receivedby + '" data-type="' + value.type +
                                '" data-remark="' + value.remark + '" data-date="' + value.date +
                                '" data-amount="' + value.amount + 
                                '" class="btn btn-xs btn-warning edit-button" style= "margin:5px" title=" Edit Received Bill " data-toggle="modal" data-target="#modaleditrbill"><i class="fa fa-edit"></i></button>';

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-center">' + srno + '</td>' +
                                '<td class="text-center">' + value.billname + '</td>' +
                                '<td class="text-center">' + value.date + '</td>' +
                                '<td class="text-center">' + value.type + '</td>' +
                                '<td class="text-center">' + value.amount + '</td>' +
                                '<td class="text-center">' + value.receivedname + '</td>' +
                                '<td class="text-center">' + value.remark + '</td>' +
                                '<td class="text-center">' + value.updated + '</td>' +
                                '<td class="text-center">' + button1 + '</td>' +
                                '</tr>';
                            $('#example1 tbody').append(html);
                        });

                        $('#customerid').append(new Option("Select Customer", ""));
                        

                        $.each(returnedData['custlist'], function(key, value) {
                            $('#customerid').append(new Option(value.billname , value.id));                        
                        });

                        $('#receivedby').append(new Option("Select Receiver", ""));
                        $('#editreceivedby').append(new Option("Select Receiver", ""));

                        $.each(returnedData['recvlist'], function(key, value) {
                            $('#receivedby').append(new Option(value.name , value.id));
                            $('#editreceivedby').append(new Option(value.name , value.id));
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

            $(document).on("click", ".edit-button", function(e) {

                $('#editalertclass').removeClass();
                $('#editmsg').empty();
                
                // $(".modal-body #editcustomerid").val( $(this).data('customerid'));
                $(".modal-body #editcustomerid").val($(this).data("customerid"));
                
                $(".modal-body #editamount").attr("value", $(this).data('amount'));
                $(".modal-body #editremark").val($(this).data('remark'));
                $("#editdate").val($(this).data('date'));
                $(".modal-body #edittype").val( $(this).data('type'));
                $("#edittype").trigger('change');                 
                $(".modal-body #editreceivedby").val($(this).data('receivedby'));
                $("#editreceivedby").trigger('change'); 
                
                
                
                $("#editid").val($(this).data('editid'));
            });

            //add received bill
            $('#addrbill').submit(function(e) {

                $('#addalertclass').removeClass();
                $('#addmsg').empty();

                e.preventDefault();

                $.ajax({
                    url: 'receivedbill.php',
                    type: 'POST',
                    data: $('#addrbill').serialize(),
                    success: function(response) {
                        console.log(response);
                        var returnedData = JSON.parse(response);
                        // console.log(returnedData);

                        if (returnedData['value'] == 1) {
                            $('#addrbill')[0].reset();
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

            //edit received bill 
            $('#editrbill').submit(function(e) {
                $('#editalertclass').removeClass();
                $('#editmsg').empty();
                e.preventDefault();

                $.ajax({
                    url: 'receivedbill.php',
                    type: 'POST',
                    data: $('#editrbill').serialize(),
                    success: function(response) {
                           //console.log(response);                      
                        var returnedData = JSON.parse(response);

                        if (returnedData['value'] == 1) {
                            $('#editalertclass').addClass(returnedData['type']);
                            $('#editmsg').append(returnedData['data']);
                            $("#editalertclass").show();
                            tabledata();
                        } else {
                            $('#editalertclass').addClass(returnedData['type']);
                            $('#editmsg').append(returnedData['data']);
                            $("#editalertclass").show();
                        }
                    }
                });

            });


        })
    </script>
</body>

</html>