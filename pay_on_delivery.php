<?php
session_start();

include("connection.php");
include("functions.php");

$user_data = check_login($con);

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
?>

<?php
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pay on delivery</title>
    
</head>
<body>
    <div style="text-align:center;">
    <h1>Pay On Delivery</h1>
    </div>
    
<div style="text-align:center;">
<label for="fname"><i class="fa fa-money"></i> Amount(In ₹)</label>
<input type="textbox" name="amt" id="amt" value="<?php if(!empty($total_price)){ echo number_format($total_price,2);} else{ echo "0";}?>" readonly style="height: 20px;"/><br/><br/>

</div>

<div>

<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
?>	
<table style="font-size: 1em;width: -webkit-fill-available;padding-inline: 20px;" class="tbl-cart" cellpadding="10" cellspacing="1">
<tbody>
<tr style="background-color: #27ae60;height: 30px;font-size: 15px;">
<th style="text-align:left;">Product Name</th>
<th style="text-align:left;">Code</th>
<th style="text-align:right;" width="5%">Quantity</th>
<th style="text-align:right;" width="10%">Unit Price</th>
<th style="text-align:right;" width="10%">Price</th>
</tr>	
<form action="">	
<?php	
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		$product_info = $db_handle->runQuery("SELECT * FROM product_tb WHERE code = '" . $item["code"] . "'");
		?>
				<tr style="display: table-row;vertical-align: inherit;border-color: inherit;background-color: antiquewhite;font-size: 12px;">
				<td><img src="<?php echo $product_info[0]["image"]; ?>" style="width: 35px;height: 35px;border-radius: 50%;border: #E0E0E0 1px solid;padding: 1px;vertical-align: middle;margin-right: 15px;" /><?php echo $item["name"]; ?></td>
				<td><?php echo $item["code"]; ?></td>
				<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
				<td  style="text-align:right;"><?php echo "₹ ".$item["price"]; ?></td>
				<td  style="text-align:right;"><?php echo "₹ ". number_format($item_price,2); ?></td>
				</tr>
				<?php
				$total_quantity += $item["quantity"];
				$total_price += ($item["price"]*$item["quantity"]);
		}
		?>

<tr style="display: table-row;vertical-align: inherit;border-color: inherit;background-color: lightgrey;font-size: 15px;">
<td colspan="2" align="right">Total:</td>
<td align="right"><?php echo $total_quantity; ?></td>
<td align="right" colspan="2"><strong><?php echo "₹ ".number_format($total_price, 2); ?></strong></td>

</tr>
</tbody>
</table>		
  <?php
} else {
?>
<div class="no-records" style="text-align: center;clear: both;margin: 38px 0px;font-size: xx-large;">Your Cart is Empty</div>
<?php 
}
?>
<br><br>
<br>
</div>


</div>

<div style="text-align:center;">
 <h2>Do you want to place your order?</h2>
 <div>
 <a href="thank_you.php" onClick="yes()" style="display: inline-block;margin-top: 1rem;background: green;color: #fff;padding: .8rem 3rem;font-size: 1.7rem;text-align: center;cursor: pointer;text-decoration: none;">yes</a>   <a href="cart.php" style="display: inline-block;margin-top: 1rem;background: red;color: #fff;padding: .8rem 3rem;font-size: 1.7rem;text-align: center;cursor: pointer;text-decoration: none;">no</a>
 </div>
 
 <script>
	function yes(){ 
		alert("order placed successfully!");
	 <?php

		$amt = number_format($total_price,2);
	
        $order_id = $_SESSION["rand"];
        $customer_name = $user_data['user_name'];
		$added_on=date('Y-m-d h:i:s');

		$sql = "INSERT INTO payment (order_id, name, amount, payment_status,added_on) VALUES ('$order_id','$customer_name','$amt','pay on delivery','$added_on')";
		mysqli_query($con, $sql);
			
		
		foreach ($_SESSION["cart_item"] as $item){
			$order_id = $_SESSION["rand"];
			$product_name = $item["name"];
			$quantity = $item["quantity"];
			$unit_price = $item["price"];
			$item_price = $item["quantity"]*$item["price"];
			$sql2 = "INSERT INTO orders (order_id,customer_name,product_name,quantity,unit_price,price,total_bill,payment_status) VALUES ('$order_id','$customer_name','$product_name','$quantity','$unit_price','$item_price','$amt','pay on delivery')";
			mysqli_query($con, $sql2);
		
		}
	
	?>
	
	}
	
</script>

    </div>
</body>
</html>