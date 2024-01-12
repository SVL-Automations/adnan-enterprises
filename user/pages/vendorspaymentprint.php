<?php
//include("sessioncheck.php");
session_start();
ob_start();

include("../../db.php");

if (isset($_POST['vendorid'])) {
    $id = $_POST['vendorid'];
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");   

    $res = mysqli_query($connection, "SELECT p.*, DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, v.name AS vendorname, v.mobile AS vendormobile,v.address,v.email FROM payment_send AS p
                                        LEFT JOIN vendor AS v ON p.vendorid = v.id
                                        WHERE p.vendorid = '$id' AND p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);

    } else {
        $data->status = "0";
        $data->data = "Data Not Found.";
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
    <title><?= $project ?> : Vendor Payment Print </title>

    <!-- Font Awesome -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>


</head>


<!------ Include the above in your HEAD tag ---------->
<style>
    #invoice {
        padding: 30px;
    }

    #address {
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
        font-size: 3.0rem
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
            <div class="text-right">
                <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
            </div>
            <hr>
        </div>
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    Adnan Enterprises
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 90968-42658</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Payment To vendor:</div>
                            <h2 class="to" id="to"></h2>
                            <div id="mobile"></div>
                            <div class="address" id="address"></div>
                            <div id="email"></div>

                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id"> Details : </h3>
                            <div class="date" id="sdate">Start Date : </div>
                            <div class="date" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Mode</th>
                                <th class="text-center">Details</th>
                                <th class="text-center">Amount</th>                                
                            </tr>
                        </thead>
                        <tbody id="salesdetails">
                            <tr>

                            </tr>
                        </tbody>                        
                    </table>                    
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
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'vendorid': vars["vendorid"],
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        
                        if (returnedData['status'] == 0) {
                            var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center" colspan=5>No Data Found</td>' +                                                                        
                                    '</tr>';
                                $('#salesdetails').append(html);

                        } else {
                            document.title = 'Payment print '+returnedData['list'][0]['vendorname'] + '_' + vars["sdate"] + " - " + vars["edate"];
                            $("#to").append(returnedData['list'][0]['vendorname']);
                            $("#address").append(returnedData['list'][0]['address']);
                            $("#email").append(returnedData['list'][0]['email']);
                            $("#mobile").append("Mobile : " + returnedData['list'][0]['vendormobile']);

                            $("#sdate").append(returnedData["sdate"]);
                            $("#edate").append(returnedData["edate"]);

                            var srno = 0;
                            var total = 0;
                            
                            $.each(returnedData['list'], function(key, value) {
                                srno++;

                                total = parseFloat(total) + parseFloat(value.amount);
                                

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.mode + '</td>' +
                                    '<td class="text-center">' + value.details + '</td>' +
                                    '<td class="text-center">' + parseFloat(value.amount).toLocaleString('en-IN') + '/-</td>' +                                    
                                    '</tr>';
                                $('#salesdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="4"> <b>Total </b>  </td>' +
                                '<td class="text-center">' + parseFloat(total).toLocaleString('en-IN') + '/-</td>' +                                
                                '</tr>';
                            $('#salesdetails').append(html);                          

                        }
                    }
                });
            }

            tabledata();

            const wordify = (num) => {
                const single = ["Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
                const double = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
                const tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
                const formatTenth = (digit, prev) => {
                    return 0 == digit ? "" : " " + (1 == digit ? double[prev] : tens[digit])
                };
                const formatOther = (digit, next, denom) => {
                    return (0 != digit && 1 != next ? " " + single[digit] : "") + (0 != next || digit > 0 ? " " + denom : "")
                };
                let res = "";
                let index = 0;
                let digit = 0;
                let next = 0;
                let words = [];
                if (num += "", isNaN(parseInt(num))) {
                    res = "";
                } else if (parseInt(num) > 0 && num.length <= 10) {
                    for (index = num.length - 1; index >= 0; index--) switch (digit = num[index] - 0, next = index > 0 ? num[index - 1] - 0 : 0, num.length - index - 1) {
                        case 0:
                            words.push(formatOther(digit, next, ""));
                            break;
                        case 1:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 2:
                            words.push(0 != digit ? " " + single[digit] + " Hundred" + (0 != num[index + 1] && 0 != num[index + 2] ? " and" : "") : "");
                            break;
                        case 3:
                            words.push(formatOther(digit, next, "Thousand"));
                            break;
                        case 4:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 5:
                            words.push(formatOther(digit, next, "Lakh"));
                            break;
                        case 6:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 7:
                            words.push(formatOther(digit, next, "Crore"));
                            break;
                        case 8:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 9:
                            words.push(0 != digit ? " " + single[digit] + " Hundred" + (0 != num[index + 1] || 0 != num[index + 2] ? " and" : " Crore") : "")
                    };
                    res = words.reverse().join("")
                } else res = "";
                return res
            };
        })
    </script>
</body>

</html>