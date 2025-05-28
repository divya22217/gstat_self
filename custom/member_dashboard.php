<script src="./plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css"/>
<script src="./plugins/jQueryUI/jquery-ui.js"></script>
<script src="./plugins/jQueryUI/date.js"></script>
<script src="./src/calendar.js"></script>

<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL); 
$current_date = date('Y-m-d');
$user_court = $_SESSION['user_court'];
$query = "select max(listing_date) as l_date from $schemas.case_allocation where court_no = ?";
$cause_list = $db->prepare($query);
$cause_list->bindParam(1, $user_court, PDO::PARAM_STR);
//$cause_list->bindParam(2, $current_date, PDO::PARAM_STR);
$cause_list->execute();
$today_date = $cause_list->fetchColumn();


if(!empty($today_date))
	$today_date = date('d/m/Y',strtotime($today_date));
else
	$today_date = date('d/m/Y');


 ?>

 

<script>	

	function show_causelist(list_date){
		get_causelist('<?php echo $user_court; ?>',list_date);

	}

	//$( document ).ready(function() {
		//alert("sdfsdf");
		get_causelist('<?php echo $user_court; ?>','<?php echo $today_date; ?>');
		function get_causelist(user_court,today_date){
	    $.ajax({
                type: "POST",
                url: "./mis/generate_cause_list1.php",
                data: {court_no:user_court,next_list_date:today_date,cause_list_type:1,from:'1'},
                dataType: 'html',
                success: function(data11) {
                    $('#show_content').html(data11);
                    
                    // $('.loader').fadeOut(200);
                    //   $("#cases_data_list_details").html(data);
                },
                error: function(request, error) {
                    //   $('.loader').fadeOut(200);
                    // console.log("Something error.");
                }
            });
		}
	//});
</script>