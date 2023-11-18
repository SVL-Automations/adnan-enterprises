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

    $res = mysqli_query($connection, "SELECT p.*, DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, v.name AS vendorname, v.mobile AS vendormobile,v.address,v.email FROM purchase AS p
                                        LEFT JOIN vendor AS v ON p.vendorid = v.id
                                        WHERE p.vendorid = '$id' AND p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(amount),0) as totalSend FROM `payment_send` WHERE status = 1 AND vendorid = '$id' ");
        $data->totalSend = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(total),0) as totalPurchase FROM `purchase` WHERE status = 1 AND vendorid = '$id' AND date < '$sdate'");
        $data->totalPurchase = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(pending,0) as pending FROM `vendor` WHERE id = '$id' ");
        $data->pending = mysqli_fetch_row($res);


    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
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
    <title><?= $project ?> : Purchase Bill </title>

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
                            <div class="text-gray-light">Bill FROM:</div>
                            <h2 class="to" id="to"></h2>
                            <div id="mobile"></div>
                            <div class="address" id="address"></div>
                            <div id="email"></div>

                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Bill Details : </h3>
                            <div class="date" id="sdate">Start Date : </div>
                            <div class="date" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Caret</th>
                                <th class="text-center">5KG Box</th>
                                <th class="text-center">Paper</th>
                                <th class="text-center">Tape</th>
                                <th class="text-center">Tawine</th>
                                <th class="text-center">BR Box</th>
                                <!-- <th class="text-center">Plane Box</th> -->
                                <th class="text-center">White Rim</th>
                                <th class="text-center">Pink Rim</th>
                            </tr>
                        </thead>
                        <tbody id="salesdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <!-- <td colspan="2" rowspan="4" style="text-align: left !important; font-size:20px; border-style: dotted; border-width: 0.8px;">
                                    <div>Bank Details:</div>
                                    <div>Account Holder: Alimurtuja Nijam Umarani</div>
                                    <div> Name: ICICI Bank, Billur</div>
                                    <div>A/c No: 637805004166 </div>
                                    <div>Ifsc code: ICIC0006378</div>
                                </td> -->
                                <td colspan="4">Sub Total</td>
                                <td id="subtotal"><i class="fa fa-inr"></i></td>
                            </tr>
                            <tr>
                                <!-- <td colspan="2" >
                                    
                                </td> -->
                                <td colspan="4">Pending </td>
                                <td id="pending"><i class="fa fa-inr"></i></td>
                            </tr>
                            <tr>
                                <!-- <td colspan="2"></td> -->
                                <td colspan="4">Grand Total</td>
                                <td id="grandtotal"><i class="fa fa-inr"></i></td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- <div class="thanks">Thank you for your business!</div>
                    <div class="notices">
                        <div>NOTICE:</div>
                        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
                        <div class="notice">A cheque bounce charges should be paid by custmer only.</div>
                    </div> -->
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

                        } else {
                            document.title = returnedData['list'][0]['vendorname'] + '_' + vars["sdate"] + " - " + vars["edate"];
                            $("#to").append(returnedData['list'][0]['vendorname']);
                            $("#address").append(returnedData['list'][0]['address']);
                            $("#email").append(returnedData['list'][0]['email']);
                            $("#mobile").append("Mobile : " + returnedData['list'][0]['vendormobile']);

                            $("#sdate").append(returnedData["sdate"]);
                            $("#edate").append(returnedData["edate"]);

                            var srno = 0;
                            var caretTotal = 0;
                            var box5kgTotal = 0;
                            var paperTotal = 0;
                            var tapeTotal = 0;
                            var tawimTotal = 0;
                            var brboxTotal = 0;
                            // var planetTotal = 0;
                            var whiterimTotal = 0;
                            var pinkrimTotal = 0;
                            var discount = 0;
                            var total = 0;

                            var caretQuantity = 0;
                            var box5kgQuantity = 0;
                            var paperQuantity = 0;
                            var tapeQuantity = 0;
                            var tawimQuantity = 0;
                            var brboxQuantity = 0;
                            // var planetQuantity = 0;
                            var whiterimQuantity = 0;
                            var pinkrimQuantity = 0;


                            $.each(returnedData['list'], function(key, value) {
                                srno++;

                                caretQuantity = parseFloat(caretQuantity) + parseFloat(value.caret_quantity);
                                box5kgQuantity = parseFloat(box5kgQuantity) + parseFloat(value.box5kg_quantity);
                                paperQuantity = parseFloat(paperQuantity) + parseFloat(value.paper_quantity);
                                tapeQuantity = parseFloat(tapeQuantity) + parseFloat(value.tape_quantity);
                                tawimQuantity = parseFloat(tawimQuantity) + parseFloat(value.tawim_quantity);
                                brboxQuantity = parseFloat(brboxQuantity) + parseFloat(value.brbox_quantity);
                                // planetQuantity = parseFloat(planetQuantity) + parseFloat(value.planetbox_quantity);
                                whiterimQuantity = parseFloat(whiterimQuantity) + parseFloat(value.whiterim_quantity);
                                pinkrimQuantity = parseFloat(pinkrimQuantity) + parseFloat(value.pinkrim_quantity);

                                caretTotal = parseFloat(caretTotal) + (parseFloat(value.caret_rate) * parseFloat(value.caret_quantity));
                                box5kgTotal = parseFloat(box5kgTotal) + (parseFloat(value.box5kg_rate) * parseFloat(value.box5kg_quantity));
                                paperTotal = parseFloat(paperTotal) + (parseFloat(value.paper_rate) * parseFloat(value.paper_quantity));
                                tapeTotal = parseFloat(tapeTotal) + (parseFloat(value.tape_rate) * parseFloat(value.tape_quantity));
                                tawimTotal = parseFloat(tawimTotal) + (parseFloat(value.tawim_rate) * parseFloat(value.tawim_quantity));
                                brboxTotal = parseFloat(brboxTotal) + (parseFloat(value.brbox_rate) * parseFloat(value.brbox_quantity));
                                // planetTotal = parseFloat(planetTotal) + (parseFloat(value.planetbox_rate) * parseFloat(value.planetbox_quantity));                                
                                whiterimTotal = parseFloat(whiterimTotal) + (parseFloat(value.whiterim_rate) * parseFloat(value.whiterim_quantity));                                
                                pinkrimTotal = parseFloat(pinkrimTotal) + (parseFloat(value.pinkrim_rate) * parseFloat(value.pinkrim_quantity));                                
                                total = parseFloat(total) + parseFloat(value.total);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.caret_quantity + '</td>' +
                                    '<td class="text-center">' + value.box5kg_quantity + '</td>' +
                                    '<td class="text-center">' + value.paper_quantity + '</td>' +
                                    '<td class="text-center">' + value.tape_quantity + '</td>' +
                                    '<td class="text-center">' + value.tawim_quantity + '</td>' +
                                    '<td class="text-center">' + value.brbox_quantity + '</td>' +
                                    // '<td class="text-center">' + value.planetbox_quantity + '</td>' +
                                    '<td class="text-center">' + value.whiterim_quantity + '</td>' +
                                    '<td class="text-center">' + value.pinkrim_quantity + '</td>' +
                                    '</tr>';
                                $('#salesdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Quantity</b>  </td>' +
                                '<td class="text-center">' + caretQuantity + '</td>' +
                                '<td class="text-center">' + box5kgQuantity + '</td>' +
                                '<td class="text-center">' + paperQuantity + '</td>' +
                                '<td class="text-center">' + tapeQuantity + '</td>' +
                                '<td class="text-center">' + tawimQuantity + '</td>' +
                                '<td class="text-center">' + brboxQuantity + '</td>' +
                                // '<td class="text-center">' + planetQuantity + '</td>' +
                                '<td class="text-center">' + whiterimQuantity + '</td>' +
                                '<td class="text-center">' + pinkrimQuantity + '</td>' +
                                '</tr>';
                            $('#salesdetails').append(html);

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Price</b>  </td>' +
                                '<td class="text-center">' + caretTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + box5kgTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + paperTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + tapeTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + tawimTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + brboxTotal.toLocaleString('en-IN') + '/-</td>' +
                                // '<td class="text-center">' + planetTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + whiterimTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + pinkrimTotal.toLocaleString('en-IN') + '/-</td>' +
                                '</tr>';
                            $('#salesdetails').append(html);

                            var total1 = parseFloat(caretTotal) + parseFloat(box5kgTotal) + parseFloat(paperTotal) + parseFloat(tapeTotal) + parseFloat(tawimTotal) + parseFloat(brboxTotal) + parseFloat(whiterimTotal)+ parseFloat(pinkrimTotal);
                            var pending = parseFloat(returnedData['totalPurchase'][0]) + parseFloat(returnedData['pending'][0]) - parseFloat(returnedData['totalSend'][0]);
                            $("#subtotal").append(parseFloat(total1).toLocaleString('en-IN') + "/-");
                            $("#pending").append(parseFloat(pending).toLocaleString('en-IN') + "/-");
                            $("#grandtotal").append(parseFloat(parseFloat(total1) + parseFloat(pending)).toLocaleString('en-IN') + "/- ");
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