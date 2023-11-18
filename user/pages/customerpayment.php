<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];

if (isset($_POST['tabledata'])) {
    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");
    $result = mysqli_query($connection, "SELECT r.*,DATE_FORMAT(r.date,'%d/%m/%Y') AS niceDate , c.name as customername, c.mobile as customermobile FROM `payment_received` as r                                         
                                        LEFT JOIN customer as c ON r.customerid = c.id
                                        WHERE r.status = 1
                                        order by r.date DESC ");
    $data->list = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "Select id,name from customer where status='1' order by name");
    $data->custlist = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}


//Add Receive Bill
if (isset($_POST['Add'])) {

    $msg = new \stdClass();

    $customerid = mysqli_real_escape_string($connection, $_POST['customerid']);
    $type = mysqli_real_escape_string($connection, $_POST['type']);
    $amount = mysqli_real_escape_string($connection, $_POST['amount']);
    $remark = mysqli_real_escape_string($connection, $_POST['remark']);
    $date = mysqli_real_escape_string($connection, $_POST['date']);

    $res = mysqli_query($connection, "INSERT INTO `payment_received`(`customerid`, `date`, `amount`, `mode`, `details`, `updatedby`,`status`)                                           
                                    VALUES('$customerid','$date','$amount','$type', '$remark','$userid','1')
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

//Delete Received Bill
if (isset($_POST['delete'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");

    $id = mysqli_real_escape_string($connection, trim(strip_tags($_POST['deleteid'])));


    $res = mysqli_query($connection, "UPDATE `payment_received` SET `status`= 0 where id = '$id'");

    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Received Bill Deleted Successfully.";
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
    <title><?= $project ?> : Add Received Payment</title>
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
                    <li class="active"> Received Payment </li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Received Payment Details </h3>
                                <a class="btn btn-social-icon btn-success pull-right" title="Add Received Payment" data-toggle="modal" data-target="#modaladdrbill"><i class="fa fa-plus"></i></a>
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
                                            <th class='text-center'>Mobile Name </th>
                                            <th class='text-center'>Date </th>
                                            <th class='text-center'>Type </th>
                                            <th class='text-center'>Amount </th>
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
                                            <th class='text-center'>Mobile Name </th>
                                            <th class='text-center'>Date </th>
                                            <th class='text-center'>Type </th>
                                            <th class='text-center'>Amount </th>
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
                                <input type="date" class="form-control" id="date" name="date" max=<?= date('Y-m-d') ?>>
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
                $('#customerid').empty();
                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

                $.ajax({
                    url: $(location).attr('href'),
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

                            button1 = '<button type="submit" name="Delete" id="Delete" ' +
                                'data-deleteid="' + value.id +
                                '" class="btn btn-xs btn-danger delete-button" style= "margin:5px" title=" Delete Received Bill"><i class="fa fa-times"></i></button>';

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-center">' + srno + '</td>' +
                                '<td class="text-center">' + value.customername + '</td>' +
                                '<td class="text-center">' + value.customermobile + '</td>' +
                                '<td class="text-center">' + value.niceDate + '</td>' +
                                '<td class="text-center">' + value.mode + '</td>' +
                                '<td class="text-center">' + value.amount + '</td>' +
                                '<td class="text-center">' + value.details + '</td>' +
                                '<td class="text-center">' + value.lastupdate + '</td>' +
                                '<td class="text-center">' + button1 + '</td>' +
                                '</tr>';
                            $('#example1 tbody').append(html);
                        });

                        $('#customerid').append(new Option("Select Customer", ""));
                        $.each(returnedData['custlist'], function(key, value) {
                            $('#customerid').append(new Option(value.name, value.id));
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
                            // console.log(response);                      
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

            //add received bill
            $('#addrbill').submit(function(e) {
                $('#add').prop('disabled', true);
                $('#addalertclass').removeClass();
                $('#addmsg').empty();

                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: $('#addrbill').serialize(),
                    success: function(response) {
                        console.log(response);
                        var returnedData = JSON.parse(response);
                        // console.log(returnedData);
                        $('#add').prop('disabled', false);
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

        })
    </script>
</body>

</html>