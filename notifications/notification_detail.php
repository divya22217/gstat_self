<?php  include('header.php');
$msgId =$_REQUEST['msgId'];
?>

<div class="loading">Loading&#8230;</div>
<div class="container padding120">
	
	
	
	<div class="demo" style="margin-top: 200px;" >
		
		<div class="panel-group" id="accordion" role="tablist"
			aria-multiselectable="true">
			<div class="panel panel-default" id="cont">
				<a class="collapsed" role="button" data-toggle="collapse"
					data-parent="#accordion" href="#collapseFour" aria-expanded="false"
					aria-controls="collapseThree">
					<div class="panel-heading" role="tab" id="headingThree"
						style="background-color: #4a565d;">
						<h4 class="panel-title" style="color: white; text-align: center">
						NOTIFICATION DETAIL </h4>
					</div>
				</a>
				<div id="collapseFour1" role="tabpanel"
					aria-labelledby="headingThree" style="display: block;">
                        <div class="panel-body">
                            <div class="row">
                                    <div class="col-md-12">
                                       
                                            <div class="listview">
                                                <?php $remarks= getNotifiDetail($db,$msgId); ?>
                                                        <li style="list-style: none!important; font-size:15px;">
                                                            <b><?php echo $remarks['message']; ?>
                                                        </li>
                                            </div>
                                        
                                      
                                    </div>
                            </div>
                        </div>    
                </div>
            </div>
        <div>
    </div>		<!-- container -->
</div>
</div>
</body>
   

</html>

<?php 
function getNotifiDetail($db,$msgId)
{
 
    $sql = "SELECT n.msg_id, n.type, n.message, n.data, n.is_read, n.created_at FROM notifications n
    WHERE n.msg_id = ? ";

    try {
        $stmt =$db->prepare($sql);
        $stmt->bindParam(1, $msgId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching notifications: " . $e->getMessage();
        return [];
    }
}
?>


