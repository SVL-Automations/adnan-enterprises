<?php
//include("sessioncheck.php");
session_start();
ob_start();

include("../../db.php");

if (isset($_POST['id'])) {

    $id = $_POST['ids'];
    $date = $_POST['date'];
    $data = new \stdClass();

    $res = mysqli_query($connection, "SELECT p.*, v.name as vendorname, v.mobile as vendormobile from purchase as p
                                        LEFT join vendor as v ON p.vendorid = v.id
                                        where p.id = '$id'");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->data = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
        $data->status = "0";
        $data->data = "Bill Not Found.";
    }
    echo json_encode($data);
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $project ?> : Bill </title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>


<!------ Include the above in your HEAD tag ---------->
<style>
    #invoice {
        padding: 30px;
    }
    #address{
        font-size: 22px;
    }

    .invoice {
        position: relative;
        background-color: #FFF;
        min-height: 680px;
        padding: 15px
    }

    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #3989c6
    }

    .invoice .company-details {
        text-align: right;
        font-size: 1.4em;
    }

    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0;
        font-size : 4.0rem 
    }

    .invoice .contacts {
        margin-bottom: 20px
    }

    .invoice .invoice-to {
        text-align: left;
        font-size: 1.5em;
    }

    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .invoice-details {
        text-align: right;
        font-size: 20px;

    }

    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #000000
    }

    .invoice main {
        padding-bottom: 50px
    }

    .invoice main .thanks {
        margin-top: -10px;
        font-size: 2em;
        margin-bottom: 50px
    }

    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #3989c6
    }

    .invoice main .notices .notice {
        font-size: 1.2em
    }

    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
    }

    .invoice table td,
    .invoice table th {
        padding: 15px;
        background: #eee;
        border-bottom: 1px solid #fff
    }

    .invoice table th {
        white-space: nowrap;
        font-weight: 500;
        font-size: 20px
    }

    .invoice table td h3 {
        margin: 0;
        font-weight: 400;
        color: #000000;
        font-size: 1.6em
    }

    .invoice table .qty,
    .invoice table .total,
    .invoice table .unit {
        text-align: right;
        font-size: 1.6em
    }

    .invoice table .no {
        color: #000000;
        font-size: 1.6em;
        
    }

    .invoice table .unit {
        /* background: #ddd */
    }

    .invoice table .total {
        /* background: #3989c6;
        color: #fff */
    }

    .invoice table tbody tr:last-child td {
        border: none
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 1.2em;
        border-top: 1px solid #aaa
    }

    .invoice table tfoot tr:first-child td {
        border-top: none
    }

    .invoice table tfoot tr:last-child td {
        color: #000000;
        font-size: 1.4em;
        border-top: 1px solid #000000
    }

    .invoice table tfoot tr td:first-child {
        border: none
    }

    .invoice footer {
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0;
        font-size: 20px;
    }

    @media print {
        .invoice {
            font-size: 11px !important;
            overflow: hidden !important
        }

        .invoice footer {
            position: absolute;
            bottom: 10px;
            page-break-after: always;           
            
        }

        .invoice>div:last-child {
            page-break-before: always
        }
    }
</style>

<body>
    <div id="invoice">

        <div class="toolbar hidden-print">
            <!-- <div class="text-right">
                <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
            </div> -->
            <hr>
        </div>
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170"/>
                            </a>
                        </div>
                        <div class="col company-details">
                            <h1 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    Mulla Wakhar
                                </a>
                            </h1>
                            <div class="address">Near Reliance Mall, </div>
                            <div>Laxmipuri, Kolhapur, 416002</div>
                            <div>Mobile : 98609-43955</div>
                            <div>yasins.mulla9@gmail.com</div>
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">INVOICE TO:</div>
                            <h2 class="to" id="to"></h2>
                            <div class="address" id="address"></div>
                            <div  id="email"></div>
                            <div  id="mobile"></div>
                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">INVOICE : </h3>
                            <div class="date" id="idate">Invoice Date : </div>
                            <div class="date" id="date">Due Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no.  </th>
                                <th class="text-left">Product Description</th>
                                <th class="text-right">Quantity(KG)</th>
                                <th class="text-right">Rate(Rs)</th>
                                <th class="text-right">Total(Rs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="no" width="5%">01</td>
                                <td class="text-left" width="50%">
                                    <h3 id="productname">

                                    </h3>
                                </td>
                                <td class="qty" id='qty' width="15%"></td>
                                <td class="unit" id="unit" width="15%"><i class="fa fa-inr"></i></td>
                                <td class="total" id='total' width="15%"><i class="fa fa-inr"></i></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2"  rowspan="4" style="text-align: left !important; font-size:20px; border-style: dotted; border-width: 0.8px;">
                                <div>Bank Details:</div>
                                    <div>Account Holder: Yasin Mulla</div>
                                    <div> Name: Bank of Maharastra</div>
                                    <div>A/c No: 60050078389 </div>
                                    <div>Ifsc code: MAHB0001017</div>
                                </td>
                                <td colspan="2">Sub Total</td>
                                <td id="subtotal"><i class="fa fa-inr"></i></td>
                            </tr>
                            <tr>
                                <!-- <td colspan="2" >
                                    
                                </td> -->
                                <td colspan="2">Shipping</td>
                                <td id="shipping"><i class="fa fa-inr"></i></td>
                            </tr>
                            <tr>
                                <!-- <td colspan="2"></td> -->
                                <td colspan="2">Labour</td>
                                <td id="labour"><i class="fa fa-inr"></i></td>
                            </tr>
                            <tr>
                                <!-- <td colspan="2"></td> -->
                                <td colspan="2">Grand Total</td>
                                <td id="grandtotal"><i class="fa fa-inr"></i></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="thanks">Thank you for your business!</div>
                    <div class="notices">
                        <div>NOTICE:</div>
                        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
                        <div class="notice">A cheque bounce charges should be paid by custmer only.</div>
                    </div>
                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>
    <!-- jQuery 3 -->
    <!-- <script src="../../bower_components/jquery/dist/jquery.min.js"></script> -->
    <!-- Bootstrap 3.3.7 -->
    <!-- <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->
    <!-- SlimScroll -->
    <!-- <script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script> -->
    <!-- FastClick -->
    <!-- <script src="../../bower_components/fastclick/lib/fastclick.js"></script> -->

    <script>
        $(document).ready(function() {
            $('#printInvoice').click(function() {
                Popup($('.invoice')[0].outerHTML);

                function Popup(data) {
                    window.print();
                    return true;
                }
            });

            //display data table
            function tabledata() {
                var url = window.location.search.substring(1);
                var id = /id=(\d+)/.exec(url)[1];


                $.ajax({
                    url: 'rptbill.php',
                    type: 'POST',
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        // console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {
                            document.title = returnedData['data'][0]['billname'] +'_'+ returnedData['data'][0]['id'];
                            $("#to").append(returnedData['data'][0]['billname']);
                            $("#address").append(returnedData['data'][0]['address']);
                            $("#email").append(returnedData['data'][0]['cemail']);
                            $("#mobile").append(returnedData['data'][0]['cmobile']);
                            $("#invoice-id").append(returnedData['data'][0]['id']);
                            var todayTime = new Date(returnedData['data'][0]['date']);
                            $("#idate").append(todayTime.getDate() + "/" + (todayTime.getMonth() + 1) + "/" + todayTime.getFullYear());
                            $("#date").append(todayTime.getDate() + "/" + (todayTime.getMonth() + 1) + "/" + todayTime.getFullYear());
                            $("#productname").append(returnedData['data'][0]['name']);
                            $("#unit").append(parseFloat(returnedData['data'][0]['rate']).toFixed(2));
                            $("#qty").append(parseFloat(returnedData['data'][0]['quantity']).toFixed(2));
                            $("#total").append(parseFloat(returnedData['data'][0]['rate'] * returnedData['data'][0]['quantity']).toFixed(2));
                            $("#subtotal").append(parseFloat(returnedData['data'][0]['rate'] * returnedData['data'][0]['quantity']).toFixed(2));
                            $("#shipping").append(parseFloat(returnedData['data'][0]['shipping']).toFixed(2));
                            $("#labour").append(parseFloat(returnedData['data'][0]['labour']).toFixed(2));
                            $("#grandtotal").append(parseFloat(parseFloat((returnedData['data'][0]['rate'] * returnedData['data'][0]['quantity'])) + parseFloat(returnedData['data'][0]['shipping']) + parseFloat(returnedData['data'][0]['labour'])).toFixed(2));


                        }



                    }
                });
            }

            tabledata();
        })
    </script>
</body>

</html>