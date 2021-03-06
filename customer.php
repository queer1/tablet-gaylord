<?PHP
session_start();

ini_set('error_reporting', version_compare(PHP_VERSION,5,'>=') && version_compare(PHP_VERSION,6,'<') ?E_ALL^E_STRICT:E_ALL);
	
$error_msg = ""; 
$success_msg = "";	
$keyword ="";
$username = $_SESSION['username'];

if ($_GET['par'] = md5(2)){

	$error_msg = $_GET['err'];

} else{
	$error_msg = "";
	$user_msg = "Use your given loging credencials to log into the admin controller";	
}
	
if(!isset($_SESSION['username'])){
	
	$error_msg = "";
    header("Location: index.php?err=$error_msg");
    exit();
	
}else if(isset($_POST['edit'])){
	
	$cid = stripslashes($_POST['cid']);
	$fname = stripslashes($_POST['fname']);
	$lname = stripslashes($_POST['lname']);
	$address01 = stripslashes($_POST['address01']);	
	$town = stripslashes($_POST['town']);
	$postcode = stripslashes($_POST['postcode']);
	$email = stripslashes($_POST['email']);
	$mobile = stripslashes($_POST['mobile']);	
	
	
	$cid = strip_tags($cid);
	$fname = strip_tags($fname);	
	$lname = strip_tags($lname);	
	$address01 = strip_tags($address01);	
	$town = strip_tags($town);
	$postcode = strip_tags($postcode);	
	$email = strip_tags($email);	
	$mobile = strip_tags($mobile);	
	
	include_once ("db_connect.php");
		
		$update_client = "UPDATE client_tbl SET c_fname='$fname', c_lname='$lname', c_address1='$address01', c_town='$town', c_postcode='$postcode', c_email='$email', c_mobile='$mobile', c_active ='1'
							WHERE c_id='$cid'";
		
		$update_client_db = mysqli_query($db_connection, $update_client) or die (mysqli_error($db_connection));
				
		if($update_client_db){
		
		$success_msg = "Updated";
			
		} else{
		
		$error_msg = "Not Updated ";	
		
		}	
		
} else if(isset($_POST['add'])){
	
	$fname = stripslashes($_POST['fname']);
	$lname = stripslashes($_POST['lname']);
	$address01 = stripslashes($_POST['address01']);	
	$town = stripslashes($_POST['town']);
	$postcode = stripslashes($_POST['postcode']);
	$email = stripslashes($_POST['email']);
	$mobile = stripslashes($_POST['mobile']);	
	
	
	$cid = strip_tags($cid);
	$fname = strip_tags($fname);	
	$lname = strip_tags($lname);	
	$address01 = strip_tags($address01);	
	$town = strip_tags($town);
	$postcode = strip_tags($postcode);	
	$email = strip_tags($email);	
	$mobile = strip_tags($mobile);
	
	$password = md5($fname .".".$lname); 
	
	include_once ("db_connect.php");
		
		
		
		$sql_client_check = ("SELECT c_id FROM client_tbl WHERE c_email='$email' LIMIT 1");
		
		$sql_client_check_db = mysqli_query($db_connection, $sql_client_check) or die (mysqli_error($db_connection));
		
		mysqli_free_result($sql_client_check_db);
		
	if ($sql_client_check_db){
		
		$error_msg = "Account already exists";
		
	}else{
		
		$add_customer = ("INSERT INTO client_tbl (c_fname, c_lname, c_address1, c_town, c_postcode, c_email, c_mobile, c_joindate, c_active, c_identifier) VALUES ('$fname', '$lname', '$address01', '$town', '$postcode', '$email', '$mobile', now(), '1', '$password')");
		
		$add_customer_db = mysqli_query($db_connection, $add_customer) or die (mysqli_error($db_connection));
		
		mysqli_free_result($add_customer_db);

		if($add_customer_db){
		
		$c_id = mysqli_insert_id($db_connection);
		$filtercondition_qry ='WHERE c_id = "'.$c_id.'"';

		$success_msg = "Insert Successful";
			
		} else{
		
		$error_msg = "Insert Failed";	
		
		}
	}
} else if(isset($_POST['delete'])){
	
	$cid = stripslashes($_POST['cid']);	
	$cid = strip_tags($cid);		

include_once ("db_connect.php");
		
		$delete_clientid = "DELETE FROM client_tbl WHERE c_id='$cid'";
		
		$delete_clientid_db = mysqli_query($db_connection, $delete_clientid) or die (mysqli_error($db_connection));
		
		if($delete_clientid_db){
		
		$success_msg = "Client deleted";
			
		} else{
		
		$error_msg = "Deleation Failed";	
		
		}
	
} 

if ($_POST['keyword']){

	$keyword = $_POST['keyword'];

}

if($_POST['filtercondition'] == 1){
		
	$filtercondition_qry ='WHERE c_id = "'.$keyword.'"';
	$filtercondition = "ID (Selected)";
	
	}else if($_POST['filtercondition'] == 2){
		
	$filtercondition_qry ='WHERE c_fname = "'.$keyword.'"';
	$filtercondition = "First Name (Selected)";

	}else if($_POST['filtercondition'] == 3){
	
	$filtercondition_qry ='WHERE c_lname ="'.$keyword.'"';
	$filtercondition = "Last Name (Selected)";
	
	}else if($_POST['filtercondition'] == 4){
	
	$filtercondition_qry ='WHERE c_email ="'.$keyword.'"';
	$filtercondition = "Email (Selected)";
	
	}else if($_POST['filtercondition'] == 5){
	
	$filtercondition_qry ='WHERE c_mobile ="'.$keyword.'"';
	$filtercondition = "Mobile (Selected)";
	
	}else if($_POST['filtercondition'] == 6){
	
	$filtercondition_qry ='WHERE c_postcode ="'.$keyword.'"';
	$filtercondition = "Postcode (Selected)";

	}else if($_POST['filtercondition'] == 7){
	
	$filtercondition_qry ='WHERE c_postcode = "'.$keyword.' OR c_mobile = '.$keyword.' OR c_email = '.$keyword.' OR c_lname = '.$keyword.' OR c_fname = '.$keyword.' OR c_id = '.$keyword.'"';
	$filtercondition = "Search All (Selected)";
	
	}else{
	$filtercondition_qry ="";
	$filtercondition = "Show ALL (Selected)";
	}

		
		include_once ("db_connect.php");

		$display_client = 'SELECT * FROM client_tbl '.$filtercondition_qry.'';
	
		$display_client_db = mysqli_query($db_connection, $display_client) or die (mysqli_error($db_connection));
		
		$display_check = mysqli_num_rows($display_client_db);
		
		$filtercondition .=" " .$display_check ." clients found";
				
			if ($display_check > 0){ //gather information from database
		
				while($client = mysqli_fetch_array($display_client_db)){
				
					$c_id = $client["c_id"];
					$c_fname = $client["c_fname"];
					$c_lname = $client["c_lname"];
					$c_address1 = $client["c_address1"];
					$c_town = $client["c_town"];
					$c_postcode = $client["c_postcode"];
					$c_email = $client["c_email"];
					$c_mobile = $client["c_mobile"];

		
			$customerDisplay .='
    <div id="customerFrm">
    	
        <form action="customer.php" method="post" target="_self">
        <div class="fields customer_form_fields">
    		<label class="label_form_cst">Customer ID</label>
            <input name="cid" class="customer_frm_input" type="text" value="'.$c_id.'">

            <label class="label_form_cst">First Name</label>
            <input name="fname" class="customer_frm_input" type="text" value="'.$c_fname.'">

   		   	<label class="label_form_cst">Last name</label>
            <input name="lname" class="customer_frm_input" type="text" value="'.$c_lname.'">


   		  	<label class="label_form_cst">Address</label>
            <input name="address01" class="customer_frm_input" type="text" value="'.$c_address1.'">

   		   	<label class="label_form_cst">Town</label>
            <input name="town" class="customer_frm_input" type="text" value="'.$c_town.'">

   		   	<label class="label_form_cst">Postcode</label>
            <input name="postcode" class="customer_frm_input" type="text" value="'.$c_postcode.'">

   		   	<label class="label_form_cst">Mobile</label>
            <input name="mobile" class="customer_frm_input" type="tel" value="'.$c_mobile.'">

   		    <label class="label_form_cst">Email</label>
            <input name="email" class="customer_frm_input" type="email" value="'.$c_email.'">
   		</div>
        
        <div class="buttons">
   			<input type="submit" name="add" value="Add" class="button4_add" />        
			<input type="submit" name="edit" value="Edit" class="button4_edit" />
            <input type="submit" name="delete" value="Delete" class="button4_delete" />
   			

   		</div>
         </form>
   
    </div>
			';


			}
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<?php include_once('head.php'); ?>
<body>
<div class="gridContainer clearfix">

  <div id="Header"><?php include_once("header.php");?>     
    <div id="heading">
      <h3>Welcome <?php echo $username;?> <a href="maincontroller.php"> <img src="Images/home.png"> </a> </h3>
   	</div>
  </div>  
    
    <div id="main_content">
 
      <div class="title">
        <h2>Customer Details</h2>
      </div>
      
		<div id="searchfilter">
		    
		        <form action="customer.php" method="post" target="_self">
		        
		        <div class="fields">
		        	<input name="keyword" class="search_box" type="search" placeholder="Search: ID, Name, Address or Email">
		    	
		            <div class="styled-select">
		            <select class="filter_selected" name="filtercondition"> 
		                <option value="<?php echo $filtercondition;?>" selected><?php echo $filtercondition;?></option>
		                <option value="7">Show ALL (Default)</option>
		                <option value="1">ID</option>
		                <option value="2">First Name</option>
		                <option value="3">Last Name</option>
		                <option value="4">Email</option>
		                <option value="5">Mobile</option>
		                <option value="6">Postcode</option>
		                <option value="7">Search All</option>

		            </select>  
		            </div>
		            <div class="continue_button">
		            <input class="filter_continue" name="filter" type="submit" value="Apply Filter">   
					</div>          
		        
		            </form>
		            
		            <?php echo $error_msg; ?> <?php echo $success_msg; ?>
		  </div>
		        
		<?php echo $customerDisplay;?>


    </div>



    
    
  <div id="footer"><?php include_once("footer.php");?></div>
</div>
</body>
</html>