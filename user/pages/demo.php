<?php
    include("sessioncheck.php");
    date_default_timezone_set('Asia/Kolkata');
        
    if(isset($_POST['date']))
    {        
        //header("location:raindetails.php");        
        print_r($_POST);
        
        $_SESSION['date'] = $_POST['date'];
        echo $_SESSION['date'];
    }   
       
if (isset($_POST['tabledata1'])) {
    
    $data = array();   
    $data["date"] = $_SESSION['date'];
    echo json_encode($data);
    exit();
}

   
    

?>

 <!-- jQuery 3 -->
 <script src="../../bower_components/jquery/dist/jquery.min.js"></script>

 <script>
    $(document).ready(function() {
       
        
        document.title = 'blah';
       

        $.ajax({
            url: 'demo.php',
            type: 'POST',
            data: {
                'tabledata1': 'tabledata1'
            },
            success: function(response) {
                //console.log(response); 
                var returnedData = JSON.parse(response);
                //console.log(returnedData);            
               
            }
            });          

    })
  </script>