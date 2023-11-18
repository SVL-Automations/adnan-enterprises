<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];

if (isset($_POST['tabledata'])) {
    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");
    $result = mysqli_query($connection, "SELECT v.*,
                                            (SELECT IFNULL(SUM(p.total),0) FROM purchase as p WHERE p.status = 1 and p.vendorid = v.id) as purchaseTotal,
                                            (SELECT IFNULL(SUM(s.amount),0) FROM payment_send as s WHERE s.status=1 and s.vendorid = v.id ) as sendTotal
                                            FROM `vendor` as v
                                            GROUP BY v.id 
                                        ");
    $data->list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($data);
    exit();
}


//Add vendor
if (isset($_POST['Add'])) {

    $msg = new \stdClass();

    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $details = mysqli_real_escape_string($connection, $_POST['details']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);
    $pending = mysqli_real_escape_string($connection, $_POST['pending']);

    $res = mysqli_query($connection, "INSERT INTO `vendor`(`name`, `mobile`, `email`, `details`, `address`, `pending`, `status`)
                                    VALUES('$name','$mobile','$email','$details','$address','$pending','1')
                                    ");
    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Vendor Added Successfully";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = "Please Try Again";
        $msg->type = "alert alert-danger alert-dismissible ";
    }
    echo json_encode($msg);
    exit();
}

//Edit vendor
if (isset($_POST['Edit'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");

    $editname = mysqli_real_escape_string($connection, $_POST['editname']);
    $editaddress = mysqli_real_escape_string($connection, $_POST['editaddress']);
    $editdetails = mysqli_real_escape_string($connection, $_POST['editdetails']);
    $editemail = mysqli_real_escape_string($connection, $_POST['editemail']);
    $editmobile = mysqli_real_escape_string($connection, $_POST['editmobile']);
    $id = mysqli_real_escape_string($connection, trim(strip_tags($_POST['id'])));


    $updaterain = mysqli_query($connection, "UPDATE `vendor` SET 
                                            `name`= '$editname', `address`='$editaddress', 
                                            `email`='$editemail', `mobile`='$editmobile', `details`='$editdetails'
                                            WHERE id = '$id'
                                        ");

    if ($updaterain > 0) {
        $msg->value = 1;
        $msg->data = "Vendor Update Successfully.";
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
    <title><?= $project ?> : Vendor Details </title>
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
    <!-- <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/buttons.dataTables.min.css"> -->

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
                    <li><a href="#"> Vendor </a></li>
                    <li class="active"> Add / Update </li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Vendor Details </h3>
                                <a class="btn btn-social-icon btn-success pull-right" title="Add Vendor" data-toggle="modal" data-target="#modaladdvendor"><i class="fa fa-plus"></i></a>
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
                                            <th class='text-center'>Name </th>
                                            <th class='text-center'>Address </th>
                                            <th class='text-center'>Email </th>
                                            <th class='text-center'>Mobile </th>
                                            <th class='text-center'>Details </th>
                                            <th class='text-center'>Pending </th>
                                            <th class='text-center'>Status </th>
                                            <th class='text-center'>Update</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class='text-center'>SrNo </th>
                                            <th class='text-center'>Name </th>
                                            <th class='text-center'>Address </th>
                                            <th class='text-center'>Email </th>
                                            <th class='text-center'>Mobile </th>
                                            <th class='text-center'>Details </th>
                                            <th class='text-center'>Pending </th>
                                            <th class='text-center'>Status </th>
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
        <!-- Add vendor modal -->
        <form id="addvendor" action="" method="post">
            <div class="modal fade" id="modaladdvendor" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Add Vendor</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="addalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                <p id="addmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Vendor Name</label>
                                <input type="text" class="form-control" placeholder="Vendor Name" name="name" id="name" required pattern="[a-zA-Z0-9\s]+">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile</label>
                                <input type="number" class="form-control" placeholder="Mobile Number" id="mobile" name="mobile" required min="2222222222">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email Address</label>
                                <input type="email" class="form-control" placeholder="Email Address" id="email" name="email">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Address</label>
                                <textarea class="form-control" rows="3" placeholder="Address" name="address" ></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Details</label>
                                <textarea class="form-control" rows="3" placeholder="Details" name="details"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Pending amount</label>
                                <input type="number" class="form-control" placeholder="Pending amount" name="pending" id="pending" required pattern="[0-9]+" value="0">
                            </div>


                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Add" value="Add">
                            <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Add Vendor</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add vendor modal -->

        <!-- Edit vendor modal -->
        <form id="editvendor" action="" method="post">
            <div class="modal fade" id="modaleditvendor" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-red">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Vendor Edit</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="editalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#editalertclass').hide()">×</button>
                                <p id="editmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Vendor Name</label>
                                <input type="text" class="form-control" placeholder="Vendor Name" name="editname" id="editname" required pattern="[a-zA-Z0-9\s]+">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile</label>
                                <input type="number" class="form-control" placeholder="Mobile Number" id="editmobile" name="editmobile" required min="2222222222">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email Address</label>
                                <input type="email" class="form-control" placeholder="Email Address" id="editemail" name="editemail">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Address</label>
                                <textarea class="form-control" rows="3" placeholder="Address" name="editaddress" id='editaddress' ></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Details</label>
                                <textarea class="form-control" rows="3" placeholder="Details" name="editdetails" id='editdetails'></textarea>
                            </div>


                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="id" id="editid">
                            <input type="hidden" name="Edit" value="Edit">
                            <button type="submit" name="Edit" value="Edit" id='Edit' class="btn btn-success">Edit Vendor</button>
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
    <script src="../../bower_components/datatables.net-bs/js/dataTables.buttons.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/jszip.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/pdfmake.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/vfs_fonts.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/buttons.html5.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/buttons.print.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.sidebar-menu').tree()

            $('[data-toggle="tooltip"]').tooltip();

            //display data table
            function tabledata() {

                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'tabledata': 'tabledata'
                    },
                    success: function(response) {
                        //  console.log(response); 
                        var returnedData = JSON.parse(response);
                        //  console.log(returnedData);
                        var srno = 0;
                        $.each(returnedData['list'], function(key, value) {
                            srno++;
                            button1 = '';

                            button1 = '<button type="submit" name="Edit" id="Edit" ' +
                                'data-editid="' + value.id + '" data-name="' + value.name +
                                '" data-mobile="' + value.mobile + '" data-email="' + value.email +
                                '" data-address="' + value.address + '" data-details="' + value.details +
                                '" data-pending="' + value.pending +
                                '" class="btn btn-xs btn-warning edit-button" style= "margin:5px" title=" Edit Vendor " data-toggle="modal" data-target="#modaleditvendor"><i class="fa fa-edit"></i></button>';

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-center">' + srno + '</td>' +
                                '<td class="text-center">' + value.name + '</td>' +
                                '<td class="text-center">' + value.address + '</td>' +
                                '<td class="text-center">' + value.email + '</td>' +
                                '<td class="text-center">' + value.mobile + '</td>' +
                                '<td class="text-center">' + value.details + '</td>' +
                                '<td class="text-center">' + parseFloat(parseFloat(value.purchaseTotal) + parseFloat(value.pending) - parseFloat(value.sendTotal)).toLocaleString('en-IN') + "/-" + '</td>' +
                                '<td class="text-center">' + value.status + '</td>' +
                                '<td class="text-center">' + button1 + '</td>' +
                                '</tr>';
                            $('#example1 tbody').append(html);
                        });


                        $('#example1').DataTable({
                            dom: 'Bfrtip',                            
                            buttons: [{
                                    extend: 'copy',
                                    className: ' btn btn-success',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },                                
                                {
                                    extend: 'csv',
                                    className: ' btn bg-maroon',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'excel',
                                    className: ' btn bg-purple',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    className: ' btn bg-navy',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'print',
                                    className: ' btn bg-olive',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'colvis',
                                    columns: ' :not(.noVis)',
                                    className: ' btn btn-warning '
                                }
                            ],
                            stateSave: true,
                            destroy: true,
                        });
                    }
                });
            }

            tabledata();

            $(document).on("click", ".edit-button", function(e) {

                $('#editalertclass').removeClass();
                $('#editmsg').empty();
                $(".modal-body #editname").attr("value", $(this).data('name'));
                $(".modal-body #editemail").attr("value", $(this).data('email'));
                $(".modal-body #editmobile").attr("value", $(this).data('mobile'));
                $(".modal-body #editdetails").val($(this).data('details'));
                $(".modal-body #editaddress").val($(this).data('address'));
                $("#editid").val($(this).data('editid'));
            });

            //add vendor
            $('#addvendor').submit(function(e) {
                $('#add').prop('disabled', true);
                $('#addalertclass').removeClass();
                $('#addmsg').empty();

                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: $('#addvendor').serialize(),
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        // console.log(returnedData);
                        $('#add').prop('disabled', false);
                        if (returnedData['value'] == 1) {
                            $('#addvendor')[0].reset();
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

            //edit vendor 
            $('#editvendor').submit(function(e) {
                $('#editalertclass').removeClass();
                $('#editmsg').empty();
                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: $('#editvendor').serialize(),
                    success: function(response) {
                        // console.log(response);                      
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