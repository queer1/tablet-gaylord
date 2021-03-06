<?PHP
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error_msg = ""; 
$success_msg = "";	
$menuHeading = "";
$orderMenu = "";
$basket ="";
$basketMsg="";
$grandtotal="";	

if(!isset($_SESSION['email']))
{
	$error_msg = "user not found";

    header("Location: index.php?err=$error_msg");
    exit();
}

$name = $_SESSION['name'];

if (isset($_GET['cat'])){
	$cat=$_GET['cat'];
	
	if ($cat == "1"){
	 $menuTitle = "SHURUAT - APPETISERS";	
	 $selectedMenu = $cat;
	 	
	} else if ($cat == "2"){
	 $menuTitle = "TANDOORI DISHES (DRY DISHES)";	
	 $selectedMenu = $cat;
	 
 	} else if ($cat == "3"){
	 $menuTitle = "GAYLORD EXCLUSIVE NEW DISHES";	
	  $selectedMenu = $cat;
	 	 
	}else if ($cat == "4"){
	 $menuTitle = "OUR CHEF’S SPECIALITIES";	
	 $selectedMenu = $cat;
	 
	}else if ($cat == "5"){
	 $menuTitle = "GAYLORD CHICKEN SPECIALITIES";	
	 $selectedMenu = $cat;
	 
	}else if ($cat == "6"){
	 $selectedMenu = $cat;
	 $menuTitle = "GAYLORD LAMB SPECIALITIES";		
	 
	}else if ($cat == "7"){
	 $selectedMenu = $cat;
	 $menuTitle = "GAYLORD SEAFOOD AND FISH SPECIALITIES";		
	 
	}else if ($cat == "8"){
	 $selectedMenu = $cat;
	 $menuTitle = "AKHANI AUR-BIRIYANI - RICE DISHES";		
	 
	}else if ($cat == "9"){
	echo $selectedMenu = $cat;
	 $menuTitle = "GAYLORD VEGETARIAN SIDE DISHES";		
	 
	}else if ($cat == "10"){
	 $selectedMenu = $cat;
	 $menuTitle = "CHAWAL - RICE SIDE DISHES";		

	}else if ($cat == "11"){
	 $selectedMenu = $cat;
	 $menuTitle = "NAN/ROTI - FLATBREAD";		

	}else if ($cat == "12"){
	 $selectedMenu = $cat;
	 $menuTitle = "SUNDRIES & OTHER EXTRAS";		
	
	}else if ($cat == "13"){
	$selectedMenu = $cat;
	 $menuTitle = "DRINKS";		
	
	}else if ($cat == "14"){
	$selectedMenu = $cat;
	$menuTitle = "MY ORDER";		

	}else{
	$cat = "1";
	$selectedMenu = $cat;
	$menuTitle = "SHURUAT - APPETISERS";		

}
}
   

	include_once ("db_connect.php");
	if($cat == 14){
	
// Basket script goes here	
//########################################################################################################################
//#######Display baskey content from database#############################################################################
//########################################################################################################################
	
	$crt_sess = session_id();
	if ($crt_sess != ""){
	
	include_once("db_connect.php");
	
	$sql_product_list = "SELECT * FROM cart_tbl INNER JOIN product_tbl ON product_tbl.p_id = cart_tbl.p_id
												INNER JOIN client_tbl ON client_tbl.c_id = cart_tbl.c_id
						WHERE cart_tbl.crt_sess='$crt_sess' ORDER BY product_tbl.pc_id ASC";
				
	$get_product_db = mysqli_query($db_connection, $sql_product_list) or die (mysqli_error($db_connection));
			
				
				while ($row = mysqli_fetch_assoc($get_product_db)){
				$sub = $row['crt_qt']* $row['crt_price'];
				
				$basket .= '
				<div id="UserCart">
				
				<div id="dishName">'.$row['crt_name'].'</div>
				
				
				<div id="quant">'.$row['crt_qt'].' &nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp; &pound;' .number_format ($row['crt_price'], 2). '</div>
				
				<div id="cost">
				<a href="neworder.php?remove='.$row['p_id'].'&cat=14"><img src="../Images/minus.png" alt="Minus"></a> 
				<a href="neworder.php?add='.$row['p_id'].'&cat=14"><img src="../Images/plus.png" alt="Plus"></a> 
				<a href="neworder.php?delete='.$row['p_id'].'&cat=14"><img src="../Images/delete.png" alt="Delete"></a>
				</div>
				
				<hr>
				</div>
				';	
				$total += $sub;
				}
			
	
	if ($total==0){	
		$basketMsg = '
				<div id="cartMsg">
					<p>'.$row['u_name'].' Your cart is empty.</p>
					<p>Click the add link beside the dish to include it into the basket</p>
					<p>When you happy with the selected dish click check out to proces the basket</p>
			 	</div>';
	}
	else{
		
	$crt_sess = $_SESSION['crt_sess'];
	$activation = $_SESSION['activation'];
	$activateorderid = $_SESSION['activateorderid'];
	$c_id = $_SESSION['c_id'];
		
		$grandtotal= '<div id="usertotal">'.$row['u_name'].' Your Bill Total Is: &pound; '.number_format($total, 2).'</div>';
		$complete_btn = '<div class="Order_complete">
						<span>Once you are happy with your order, click the complete button to process your order. A waiter will come and confirm your order.</span>

  						<div class="buttons">
						<form action="confirmation.php" method="post" target="_self" enctype="multipart/form-data">
						
						<input type="submit" name="complete" value="Next Step" class="button" />
						<input name="complete" type="hidden" value="imcomplete">
						<input name="crt_sess" type="hidden" value="'.$crt_sess.'">
						<input name="activation" type="hidden" value="'.$activation.'">
						<input name="activateorderid" type="hidden" value="'.$activateorderid.'">
						<input name="c_id" type="hidden" value="'.$c_id.'">

						</div>
						</form>
					</div>';
	}
	
	// table basket

	 $activation = $_SESSION['activation'];
	 $activateorderid = $_SESSION['activateorderid'];
	
	$sql_table_product_list = "SELECT * FROM cart_tbl INNER JOIN product_tbl ON product_tbl.p_id = cart_tbl.p_id
													INNER JOIN client_tbl ON client_tbl.c_id = cart_tbl.c_id
					WHERE cart_tbl.o_activation='$activation' AND cart_tbl.o_id = '$activateorderid' ORDER BY cart_tbl.crt_sess ASC";
				
	$get_table_product_db = mysqli_query($db_connection, $sql_table_product_list) or die (mysqli_error($db_connection));
			
				$total ="";
				while ($t_row = mysqli_fetch_assoc($get_table_product_db)){
				$sub = $t_row['crt_qt']* $t_row['crt_price'];
				
				$table_basket .= '
				<div id="UserCart">
				
				<div id="dishName">'.$t_row['crt_name'].'</div>
				
				
				<div id="quant">'.$t_row['crt_qt'].' &nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp; &pound;' .number_format ($t_row['crt_price'], 2). '</div>
				
				<div id="cost">
				'.$t_row['c_fname'].'
				</div>
				
				<hr>
				</div>
				';	
				$total += $sub;
				}
			
	
	if ($total==0){	
		$table_basketMsg = '
				<div id="cartMsg">
					<p>Table cart is empty.</p>
			 	</div>';
	}
	else{
		
		$table_grandtotal= '<div id="usertotal">Total Table Bill: &pound; '.number_format($total, 2).'</div>';
		
	} 

}	
	
##########################################################################################################################	
#######End of basket script###############################################################################################
##########################################################################################################################
	
	}else{

	$get_category = "SELECT * FROM product_tbl INNER JOIN productcat_tbl ON productcat_tbl.pc_id = product_tbl.pc_id WHERE productcat_tbl.pc_id = '$selectedMenu' AND product_tbl.p_active= '1' ORDER BY product_tbl.p_id ASC";
	
	
	$get_category_db = mysqli_query($db_connection, $get_category) or die (mysqli_error($db_connection));
	$get_menu_db = mysqli_query($db_connection, $get_category) or die (mysqli_error($db_connection));
		
		
		$row_check = mysqli_num_rows($get_category_db);
		
	if ($row_check >= 1){
		
	if ($get_category_row = mysqli_fetch_assoc($get_category_db))
		{
		
		$heading = $get_category_row ['pc_name'];
		$description = $get_category_row ['pc_desc'];
		
		$menuHeading .= '
		
		<div id="menuCat">
		<div class="menuHeading">'. $heading .'</div>
		<div class="menuDescription">'. $description .'</div>
		</div>
		
		';
		}
		mysqli_free_result($get_category_db);

		while ($get_row = mysqli_fetch_assoc($get_menu_db)){

		if ($get_row['p_ldesc'] == ""){
			$descRow = "";
		} else{
			$descRow = '
			<div class="prodlDesc">'.$get_row['p_ldesc'].'</div>
			';
		}
		
		if ($get_row['p_spice'] == "0"){
			$spice = "";
		} else{
			$spice = $get_row['p_spice'];
		}
		
		if ($get_row['p_nut'] == "0"){
			$nut = "";
		} else{
			$nut = $get_row['p_nut'];
		}
		
		$orderMenu .= '
		
			<div id="menuHolder">
			<div class="prodName">'.$get_row['p_name'].'</div>
			<div class="prodsDesc">'.$get_row['p_sdesc'].' '.$spice.' '.$nut.'</div>
			<div class="prodPrice">£'.number_format($get_row['p_inprice'], 2).'</div>
			<div class="prodAdd">
			
			<a href="neworder.php?add='.$get_row['p_id'].'&pn='.$get_row['p_name'].'&cat='.$get_row['pc_id'].'&pr='.$get_row['p_inprice'].'&par='.$par.'"><font style="color:#C00; font-weight:bolder; font-size:14px;" >Add</font></a>
			
			</div>
			'.$descRow.'
			</div>
		
		';
		}		
	mysqli_free_result($get_menu_db);

	}else{
		
	$page = "http://lunarwebstudio.com/Demos/GaylordTablet/user/neworder.php";
	header('Location:'.$page .'?cat=14');
		
	}
		
}	


##########################################################################################################################
// Add items onto the basket
//########################################################################################################################
//#######ADD items from basket#########################################################################################
//########################################################################################################################
if (isset($_GET['add'])){
	$p_id = $_GET['add'];
	$pc_id = $_GET['cat'];
	$price = $_GET['pr'];
	$crt_name = $_GET['pn'];

	$page = "http://lunarwebstudio.com/Demos/GaylordTablet/user/neworder.php";
	
	if ($pc_id != ""){
	$location  = ''.$page.'?cat='.$pc_id.'';
	} else {
	$location  = $page ."?cat=1";	
	}
	include_once("db_connect.php");

	$_SESSION['crt_sess'] = session_id();
	$crt_sess = $_SESSION['crt_sess'];
	$activation = $_SESSION['activation'];
	$activateorderid = $_SESSION['activateorderid'];
	$c_id = $_SESSION['c_id'];
	
	$sql_product_check = "SELECT p_id FROM cart_tbl WHERE p_id = '$p_id' AND crt_sess = '$crt_sess'";

	$get_product_check_db = mysqli_query($db_connection, $sql_product_check) or die (mysqli_error($db_connection));

	
	$product_check = mysqli_num_rows($get_product_check_db); 
	//check to see if the product already within the cart
	if ($product_check == 0){ 
	$product_insert = "INSERT INTO cart_tbl (p_id, pc_id, o_id, c_id, o_activation, crt_name, crt_qt, crt_price, crt_sum, crt_sess, crt_date)
					VALUES ('$p_id','$pc_id','$activateorderid','$c_id','$activation','$crt_name', '1','$price','$price', '$crt_sess', NOW())";
									
	$get_product_insert_db = mysqli_query($db_connection, $product_insert) or die (mysqli_error($db_connection));
							
	} else { 
	$product_update = "UPDATE cart_tbl SET crt_qt = crt_qt + 1 WHERE crt_sess= '$crt_sess' AND p_id = $p_id";
	
	$get_product_update_db = mysqli_query($db_connection, $product_update) or die (mysqli_error($db_connection));

	
	$sql_price ="SELECT * FROM cart_tbl WHERE p_id = $p_id AND crt_sess = '$crt_sess'";
	
	$get_sql_price_db = mysqli_query($db_connection, $sql_price) or die (mysqli_error($db_connection));

	
	
	while ($get_row = mysqli_fetch_assoc($get_sql_price_db)){
	$newSub = $get_row['crt_qt'] * $get_row['crt_price'];
	}
	$update_subtotal = "UPDATE cart_tbl SET crt_sum = '$newSub' WHERE crt_sess = '$crt_sess' AND p_id = $p_id";
	
	$get_update_subtotal_db = mysqli_query($db_connection, $update_subtotal) or die (mysqli_error($db_connection));

	}
	header('Location:'.$location);
}

##########################################################################################################################
// Remove item from the cart
//########################################################################################################################
//#######Remove items from the basket#####################################################################################
//########################################################################################################################
if (isset($_GET['remove'])){
	$p_id = $_GET['remove'];
	
	$pc_id = $_GET['cat'];
	
	$page = "http://lunarwebstudio.com/Demos/GaylordTablet/user/neworder.php";

	if ($pc_id != ""){
	$location  = ''.$page.'?cat='.$pc_id.'';
	} else {
	$location  = $page ."cat=1";	
	}
		
	$_SESSION['crt_sess'] = session_id();
	$crt_sess = $_SESSION['crt_sess'];
	
	include_once("db_connect.php");

	
	$sql_product_call = "SELECT crt_qt FROM cart_tbl WHERE p_id = $p_id AND crt_sess = '$crt_sess'";
	$get_sql_product_call_db = mysqli_query($db_connection, $sql_product_call) or die (mysqli_error($db_connection));


	$row = mysqli_fetch_assoc($get_sql_product_call_db);
	$quantity_check = $row['crt_qt'];
	
	if ($quantity_check == 1){ 
		$product_Delete = "DELETE FROM cart_tbl WHERE crt_sess = '$crt_sess' AND p_id = '$p_id'";
		$get_product_Delete_db = mysqli_query($db_connection, $product_Delete) or die (mysqli_error($db_connection));

	} else { 
	--$quantity_check;

	$product_remove = "UPDATE cart_tbl SET crt_qt = '$quantity_check' WHERE crt_sess = '$crt_sess' AND p_id = '$p_id'";
	$get_product_remove_db = mysqli_query($db_connection, $product_remove) or die (mysqli_error($db_connection));

	$sql_price = "SELECT * FROM cart_tbl WHERE p_id = '$p_id' AND crt_sess = '$crt_sess'";
	$get_sql_price_db = mysqli_query($db_connection, $sql_price) or die (mysqli_error($db_connection));
	
	while ($get_row = mysqli_fetch_assoc($get_sql_price_db)){
	$newSub = $get_row['crt_sum'] - $get_row['crt_price'];
	}
	$update_subtotal = "UPDATE cart_tbl SET crt_sum = '$newSub' WHERE crt_sess = '$crt_sess' AND p_id = '$p_id'";		
	$get_update_subtotal_db = mysqli_query($db_connection, $update_subtotal) or die (mysqli_error($db_connection));
	
							
		}
	header('Location:'.$page);
}

// Delete Item
if (isset($_GET['delete'])){
	$p_id = $_GET['delete'];
	
	$pc_id = $_GET['cat'];
	
	$page = "http://lunarwebstudio.com/Demos/GaylordTablet/user/neworder.php";

	if ($pc_id != ""){
	$location  = ''.$page.'?cat='.$pc_id.'';
	} else {
	$location  = $page ."?cat=1";	
	}
		
	$_SESSION['crt_sess'] = session_id();
	$crt_sess = $_SESSION['crt_sess'];
	
	$sql_product_check = "SELECT p_id FROM cart_tbl WHERE p_id = '$p_id' AND crt_sess = '$crt_sess'";
	$get_sql_product_check_db = mysqli_query($db_connection, $sql_product_check) or die (mysqli_error($db_connection));

	
	$product_check = mysqli_num_rows($get_sql_product_check_db); 
	//check to see if the product already within the cart
	if ($product_check != ""){ 
	$product_Delete = "DELETE FROM cart_tbl WHERE crt_sess = '$crt_sess' AND p_id = '$p_id'";
	$get_product_Delete_db = mysqli_query($db_connection, $product_Delete) or die (mysqli_error($db_connection));

	} else { 
	header('Location:'.$page .'?cat=14');
	}
	header('Location:'.$page);
}
?>
<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gaylord</title>
<link href="CSS/Main.css" rel="stylesheet" type="text/css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script>
		$(function() {
			var pull 		= $('#pull');
				menu 		= $('.ordermenu .ordermenu_list');
				menuHeight	= menu.height();

			$(pull).on('click', function(e) {
				e.preventDefault();
				menu.slideToggle();
			});

			$(window).resize(function(){
        		var w = $(window).width();
        		if(w > 320 && menu.is(':hidden')) {
        			menu.removeAttr('style');
        		}
    		});
		});
	</script>

</head>
<body>

<div class="gridContainer clearfix">

  <div id="Header"><?php include_once("header1.php");?></div>  
  	<div id="heading"><h2>Welcome <?php echo $name;?></h2></div>
    
    <div class="title"><h3>View Menu</h3></div>
  <div id="takeawaymenuOption">
    <nav class="ordermenu">
        <ul class="ordermenu_list"> 
        
          <a class="op" href="?cat=1" >SHURUAT - APPETISERS</a>
            <a class="op" href="?cat=2" >TANDOORI DISHES (DRY DISHES)</a>
            <a class="op" href="?cat=3" >GAYLORD EXCLUSIVE NEW DISHES</a>
            <a class="op" href="?cat=4" >OUR CHEF’S SPECIALITIES</a>
            <a class="op" href="?cat=5" >GAYLORD CHICKEN SPECIALITIES</a>
            <a class="op" href="?cat=6" >GAYLORD LAMB SPECIALITIES</a>
            <a class="op" href="?cat=7" >GAYLORD SEAFOOD AND FISH SPECIALITIES</a>
            <a class="op" href="?cat=8" >AKHANI AUR-BIRIYANI - RICE DISHES</a>
            <a class="op" href="?cat=9" >GAYLORD VEGETARIAN SIDE DISHES</a>
            <a class="op" href="?cat=10" >CHAWAL - RICE SIDE DISHES</a>
            <a class="op" href="?cat=11" >NAN/ROTI - FLATBREAD</a>
            <a class="op" href="?cat=12" >SUNDRIES & OTHER EXTRAS</a>
            <a class="op" href="?cat=13" >DRINKS</a>
            <a class="op" href="?cat=14" >MY ORDER</a>
        </ul>
        <a href="#" id="pull">You are currently viewing: <?php echo $menuTitle;?></a>  
    </nav>    
    </div>
            <?php echo $error_msg; echo $success_msg; ?>
            
<?php echo $menuHeading;?>
<?php echo $orderMenu;?>
<?php echo $basket;?>
<?php echo $basketMsg;?>
<?php echo $grandtotal;?>
<?php echo $complete_btn;?>
<?php echo $table_basket;?>
<?php echo $table_basketMsg;?>
<?php echo $table_grandtotal;?>

<div id="footer"><?php include_once("footer1.php");?></div>
</div>
</body>
</html>