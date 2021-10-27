<?php
session_start();
include("connection.php");
include("functions.php");


$user_data = check_login($con);


if(isset($_POST['amt']) && isset($_POST['name'])){
    $amt=$_POST['amt'];
	$final_amt = number_format($amt,2);
    $name=$_POST['name'];
    $payment_status="pending";
    $added_on=date('Y-m-d h:i:s');
    $order_id = $_SESSION["rand"];

    mysqli_query($con,"insert into payment(name,amount,payment_status,added_on,order_id) values('$name','$final_amt','$payment_status','$added_on','$order_id')");
    
    $_SESSION['OID']=mysqli_insert_id($con);
}
    

if(isset($_POST['payment_id']) && isset($_SESSION['OID'])){
    $payment_id=$_POST['payment_id'];
    mysqli_query($con,"update payment set payment_status='complete',payment_id='$payment_id' where id='".$_SESSION['OID']."'");

}
?>





<?php

require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) {
	switch($_GET["action"]) {
		case "remove":
			if(!empty($_SESSION["cart_item"])) {
				foreach($_SESSION["cart_item"] as $k => $v) {
						if($_GET["code"] == $k)
							unset($_SESSION["cart_item"][$k]);				
						if(empty($_SESSION["cart_item"]))
							unset($_SESSION["cart_item"]);
				}
			}
		break;
		case "empty":
			unset($_SESSION["cart_item"]);
		break;	
	}
}

if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;

    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		$product_info = $db_handle->runQuery("SELECT * FROM product_tb WHERE code = '" . $item["code"] . "'");
	
		$total_quantity += $item["quantity"];
		$total_price += ($item["price"]*$item["quantity"]);
 }
}
?>
<?php


foreach ($_SESSION["cart_item"] as $item){
    $order_id = $_SESSION["rand"];
    $customer_name = $user_data['user_name'];
    $final_amt2 = number_format($total_price,2);
    $product_name = $item["name"];
    $quantity = $item["quantity"];
    $unit_price = $item["price"];
    $item_price = $item["quantity"]*$item["price"];
    $sql2 = "INSERT INTO orders (order_id,customer_name,product_name,quantity,unit_price,price,total_bill,payment_status) VALUES ('$order_id','$customer_name','$product_name','$quantity','$unit_price','$item_price','$final_amt2','online')";
    mysqli_query($con, $sql2);
}



?>