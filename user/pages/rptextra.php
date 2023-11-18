<?php
include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');
$talukaid = $_SESSION['talukaid'];
$talukaname = $_SESSION['taluka'];
$userid = $_SESSION['userid'];

if (isset($_POST['date'])) {
    $_SESSION['date'] = $_POST['date'];
}

if (isset($_POST['rpttoday'])) 
{
    $date = $_SESSION['date'];    
    $days_ago = date_create(date('Y-m-d', strtotime('-1 days', strtotime($date))));
    $days2_ago = date_create(date('Y-m-d', strtotime('-1 days', strtotime($date))));


    $data =  new \stdClass();
    $data->date = $_SESSION['date'];
    $data->printdate = date_format(date_create($_SESSION['date']), "d/m/Y");
    $data->officename = $officename1;
    $data->talukaname = $talukaname;
    $data->days_ago = date_format($days_ago, "d/m/Y");
    $data->days2_ago = date_format($days2_ago, "d/m/Y");

    $year= date_create($_SESSION['date'])->format("Y");  
    
    $result = mysqli_query($connection, "SET NAMES utf8");
    $pending = mysqli_query($connection, "select (select count(*) from letter1 where date='$date' and talukaid='$talukaid') as count1,
                                                (select count(*) from letter2 where date='$date' and talukaid='$talukaid') as count2,
                                                (select count(*) from letter3 where date='$date' and talukaid='$talukaid') as count3,
                                                (select count(*) from letter4 where date='$date' and talukaid='$talukaid') as count4,
                                                (select count(*) from letter5 where date='$date' and talukaid='$talukaid') as count5
                                            ");
    $pending = mysqli_fetch_assoc($pending);

    if ($pending['count1'] != 1 || $pending['count2'] != 1 || $pending['count3'] != 1 || $pending['count4'] != 1 || $pending['count5'] != 1  ) 
    {
        $data->value = 0;
        $data->msg = "Parpatra  Data is Not uploaded.";
        $data->type = "alert alert-danger alert-dismissible ";
    } 
    else 
    {
        //--------------- extra 1 --------------------------//
        $result = mysqli_query($connection, "SET NAMES utf8");
        $total=mysqli_query($connection,"select * from extraletter1 where  date = '$date' and talukaid='$talukaid'");
        $data->letter1_extra =  mysqli_fetch_all($total, MYSQLI_ASSOC);  

        //--------------- extra 2 --------------------------//
        $result = mysqli_query($connection, "SET NAMES utf8");
        $total=mysqli_query($connection,"select * from extraletter2 where  date = '$date' and talukaid='$talukaid'");
        $data->letter2_extra =  mysqli_fetch_all($total, MYSQLI_ASSOC);  

        //--------------- extra 3 --------------------------//
        $result = mysqli_query($connection, "SET NAMES utf8");
        $total=mysqli_query($connection,"select * from extraletter3 where  date = '$date' and talukaid='$talukaid'");
        $data->letter3_extra =  mysqli_fetch_all($total, MYSQLI_ASSOC);  

        //--------------- extra 4 --------------------------//
        $result = mysqli_query($connection, "SET NAMES utf8");
        $total=mysqli_query($connection,"select * from extraletter4 where  date = '$date' and talukaid='$talukaid'");
        $data->letter4_extra =  mysqli_fetch_all($total, MYSQLI_ASSOC);  

        //--------------- extra 5 --------------------------//
        $result = mysqli_query($connection, "SET NAMES utf8");
        $total=mysqli_query($connection,"select * from extraletter5 where  date = '$date' and talukaid='$talukaid'");
        $data->letter5_extra =  mysqli_fetch_all($total, MYSQLI_ASSOC);   
        
        //--------------- extra 6 --------------------------//
        $result = mysqli_query($connection, "SET NAMES utf8");
        $total=mysqli_query($connection,"select * from extraletter6 where  date = '$date' and talukaid='$talukaid'");
        $data->letter6_extra =  mysqli_fetch_all($total, MYSQLI_ASSOC);   
        

    }

    echo json_encode($data);
    exit();
}

?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="../../reportcss/normalize.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="../../reportcss/paper.css">
    <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <script>
        function myFunction() {
            window.print();
        }
    </script>
</head>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
</style>

<body class="A4 rpt">
    <div class="alert " id="alertclass" style="display: none">
        <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
        <p id="msg"></p>
    </div>
   
    <!-- Extra Information  -->
    <section class="sheet padding-25mm">
         <!-- Extra Information  1-->
        <table  border="1" width: 100% id="extra1">           
            <thead>
                <tr>                
                    <td colspan="4" align="center" height="30"><b class="officename"> </b></td>        
                </tr>
                <tr>                    
                    <td colspan="4" align="center" height="30"><b>प्रपत्र 1[मृत व्यक्तींचा तपशिल] कालावधी <span class="days_ago"></span></b></td>    
                </tr>
                <tr>
                    <td width="50" align="center" height="30">अ.क्र. </td>                   
                    <td width="150" align="center">गावांचे नाव</td>
                    <td width="150" align="center">नुकसानीची रक्कम</td>
                    <td  align="center">माहिती </td>                   
                </tr>
            </thead>
            <tbody>

            </tbody>  
        </table>
        
        <br>
        <b  style="float:right;padding-right: 00px;">तहसिलदार <span class="talukaname"> <span> </b>
         <!-- Extra Information  2-->
        <table  border="1" width: 100% id="extra2">           
            <thead>
                <tr>                
                    <td colspan="4" align="center" height="30"><b class="officename"> </b></td>        
                </tr>
                <tr>                    
                    <td colspan="4" align="center" height="30"><b>प्रपत्र 2[मृत जनावरांचा तपशिल] कालावधी  <span class="days_ago"></span></b></td>    
                </tr>
                <tr>
                    <td width="50" align="center" height="30">अ.क्र. </td>                   
                    <td width="150" align="center">गावांचे नाव</td>
                    <td width="150" align="center">नुकसानीची रक्कम</td>
                    <td  align="center">माहिती </td>                   
                </tr>
            </thead>
            <tbody>

            </tbody>  
        </table>
        
        <br>
        <b  style="float:right;padding-right: 00px;">तहसिलदार <span class="talukaname"> <span> </b>
            <!-- Extra Information  3-->
        <table  border="1" width: 100% id="extra3">           
            <thead>
                <tr>                
                    <td colspan="4" align="center" height="30"><b class="officename"> </b></td>        
                </tr>
                <tr>                    
                    <td colspan="4" align="center" height="30"><b>प्रपत्र 3[पडझड झालेली घरे/गोठे यांचा तपशिल] कालावधी  <span class="days_ago"></span></b></td>    
                </tr>
                <tr>
                    <td width="50" align="center" height="30">अ.क्र. </td>                   
                    <td width="150" align="center">गावांचे नाव</td>
                    <td width="150" align="center">नुकसानीची रक्कम</td>
                    <td  align="center">माहिती </td>                   
                </tr>
            </thead>
            <tbody>

            </tbody>  
        </table>
        
        <br>
        <b  style="float:right;padding-right: 00px;">तहसिलदार <span class="talukaname"> <span> </b>
        <!-- Extra Information  4-->
        <table  border="1" width: 100% id="extra4">           
            <thead>
                <tr>                
                    <td colspan="4" align="center" height="30"><b class="officename"> </b></td>        
                </tr>
                <tr>                    
                    <td colspan="4" align="center" height="30"><b>प्रपत्र 4[आपत्ती निहाय मृत व्यक्तींची संख्या] कालावधी<span class="days_ago"></span></b></td>    
                </tr>
                <tr>
                    <td width="50" align="center" height="30">अ.क्र. </td>                   
                    <td width="150" align="center">गावांचे नाव</td>
                    <td width="150" align="center">नुकसानीची रक्कम</td>
                    <td  align="center">माहिती </td>                   
                </tr>
            </thead>
            <tbody>

            </tbody>  
        </table>
        
        <br>
        <b  style="float:right;padding-right: 00px;">तहसिलदार <span class="talukaname"> <span> </b>
        <!-- Extra Information  5-->
        <table  border="1" width: 100% id="extra5">           
            <thead>
                <tr>                
                    <td colspan="4" align="center" height="30"><b class="officename"> </b></td>        
                </tr>
                <tr>                    
                    <td colspan="4" align="center" height="30"><b>प्रपत्र 5[सार्वजनिक/खाजगी मालमत्तेच्या नुकसानीबाबतचा तपशिल] कालावधी <span class="days_ago"></span></b></td>    
                </tr>
                <tr>
                    <td width="50" align="center" height="30">अ.क्र. </td>                   
                    <td width="150" align="center">गावांचे नाव</td>
                    <td width="150" align="center">नुकसानीची रक्कम</td>
                    <td  align="center">माहिती </td>                   
                </tr>
            </thead>
            <tbody>

            </tbody>  
        </table>
        
        <br>
        <b  style="float:right;padding-right: 00px;">तहसिलदार <span class="talukaname"> <span> </b>
        <!-- Extra Information  6-->
        <table  border="1" width: 100% id="extra5">           
            <thead>
                <tr>                
                    <td colspan="4" align="center" height="30"><b class="officename"> </b></td>        
                </tr>
                <tr>                    
                    <td colspan="4" align="center" height="30"><b>प्रपत्र 6[ अतिवृष्टीमुळे तात्पुरत्या स्वरुपात निराधार झालेल्या व्यक्तीबाबत माहिती] कालावधी <span class="days_ago"></span></b></td>    
                </tr>
                <tr>
                    <td width="50" align="center" height="30">अ.क्र. </td>                   
                    <td width="150" align="center">गावांचे नाव</td>
                    <td width="150" align="center">नुकसानीची रक्कम</td>
                    <td  align="center">माहिती </td>                   
                </tr>
            </thead>
            <tbody>

            </tbody>  
        </table>
        
        <br>
        <b  style="float:right;padding-right: 00px;">तहसिलदार <span class="talukaname"> <span> </b>
    </section>


</body>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        $('#alertclass').removeClass();
        $('#msg').empty();


        $.ajax({
            url: 'rptextra.php',
            type: 'POST',
            data: {
                'rpttoday': 'rpttoday'
            },
            success: function(response) {
                var srno = 0;

                var returnedData = JSON.parse(response);
                //console.log(returnedData);
                document.title = 'प्रपत्र अतिरिक्त माहिती ' + returnedData.printdate;
                $(".officename").append("जिल्हा : "+ returnedData['officename'] + ", तालुका : " + returnedData['talukaname']);
                $(".days_ago").append(returnedData['printdate']);
                $(".talukaname").append(returnedData['talukaname']);
                $(".todaydate").append(returnedData['printdate']);
                
                
                if(returnedData['value']==0)                   
                {
                    $('#alertclass').addClass(returnedData['type']);
                    $('#msg').append(returnedData['msg']);
                    $("#alertclass").show(); 
                } 
                else
                { 
                        //----------------------------Extra 1--------------------------// 
                    srno = 0;    
                    if(returnedData['letter1_extra'].length == 0)
                    {
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20" colspan="4"> निरंक </td>'+                                       
                                    '</tr>';
                        $('#extra1 tbody').append(html);
                    }                                      
                    $.each(returnedData['letter1_extra'], function(key, value) 
                    {
                        srno++;                        
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20">' + srno + ' </td>'+
                                        '<td width="150" align="center">' + value.village + ' </td>' +
                                        '<td width="140" align="center">' +  parseFloat(value.amount).toFixed(2) +  '</td>'+
                                       
                                        '<td align="left"  width="800">'+ value.details +' </td> ' +
                                       
                                    '</tr>';
                        $('#extra1 tbody').append(html);

                    });  

                    //----------------------------Extra 2--------------------------// 
                    srno = 0;    
                    if(returnedData['letter2_extra'].length == 0)
                    {
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20" colspan="4"> निरंक </td>'+                                       
                                    '</tr>';
                        $('#extra2 tbody').append(html);
                    }                                      
                    $.each(returnedData['letter2_extra'], function(key, value) 
                    {
                        srno++;                        
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20">' + srno + ' </td>'+
                                        '<td width="150" align="center">' + value.village + ' </td>' +
                                        '<td width="140" align="center">' +  parseFloat(value.amount).toFixed(2) +  '</td>'+
                                       
                                        '<td align="left"  width="800">'+ value.details +' </td> ' +
                                       
                                    '</tr>';
                        $('#extra2 tbody').append(html);

                    });

                    //----------------------------Extra 3--------------------------// 
                    srno = 0;    
                    if(returnedData['letter3_extra'].length == 0)
                    {
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20" colspan="4"> निरंक </td>'+                                       
                                    '</tr>';
                        $('#extra3 tbody').append(html);
                    }                                      
                    $.each(returnedData['letter3_extra'], function(key, value) 
                    {
                        srno++;                        
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20">' + srno + ' </td>'+
                                        '<td width="150" align="center">' + value.village + ' </td>' +
                                        '<td width="150" align="center">' +  parseFloat(value.amount).toFixed(2) +  '</td>'+
                                       
                                        '<td align="left"  width="800" >'+ value.details +' </td> ' +
                                       
                                    '</tr>';
                        $('#extra3 tbody').append(html);

                    }); 

                    //----------------------------Extra 4--------------------------// 
                    srno = 0;    
                    if(returnedData['letter4_extra'].length == 0)
                    {
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20" colspan="4"> निरंक </td>'+                                       
                                    '</tr>';
                        $('#extra4 tbody').append(html);                        
                    }                                      
                    $.each(returnedData['letter4_extra'], function(key, value) 
                    {
                        srno++;                        
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20">' + srno + ' </td>'+
                                        '<td width="150" align="center">' + value.village + ' </td>' +
                                        '<td width="140" align="center">' +  parseFloat(value.amount).toFixed(2) +  '</td>'+
                                       
                                        '<td align="left"  width="800">'+ value.details +' </td> ' +
                                       
                                    '</tr>';
                        $('#extra4 tbody').append(html);                       

                    });             

                    //----------------------------Extra 5--------------------------// 
                    srno = 0;    
                    if(returnedData['letter5_extra'].length == 0)
                    {
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20" colspan="4"> निरंक </td>'+                                       
                                    '</tr>';                                    
                        $('#extra5 tbody').append(html);
                    }  
                                                    
                    $.each(returnedData['letter5_extra'], function(key, value) 
                    {
                        srno++;                        
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20">' + srno + ' </td>'+
                                        '<td width="150" align="center">' + value.village + ' </td>' +
                                        '<td width="140" align="center">' +  parseFloat(value.amount).toFixed(2) +  '</td>'+
                                       
                                        '<td align="left"  width="800">'+ value.details +' </td> ' +
                                       
                                    '</tr>';
                        $('#extra5 tbody').append(html);

                    });               

                    //----------------------------Extra 6--------------------------// 
                    srno = 0;    
                    if(returnedData['letter6_extra'].length == 0)
                    {
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20" colspan="4"> निरंक </td>'+                                       
                                    '</tr>';                                    
                        $('#extra6 tbody').append(html);
                    }  
                                                    
                    $.each(returnedData['letter6_extra'], function(key, value) 
                    {
                        srno++;                        
                        var html = '<tr>'+
                                        '<td width="30" align="center" height="20">' + srno + ' </td>'+
                                        '<td width="150" align="center">' + value.village + ' </td>' +
                                        '<td width="140" align="center">' +  parseFloat(value.amount).toFixed(2) +  '</td>'+
                                       
                                        '<td align="left"  width="800">'+ value.details +' </td> ' +
                                       
                                    '</tr>';
                        $('#extra6 tbody').append(html);

                    });                                                                


                        
                  
                }     

            }
        });

    })
</script>

</html>