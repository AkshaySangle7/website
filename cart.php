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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart</title>
     <!-- font awesome cdn link  -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

     <!-- custom css file link  -->
     <link rel="stylesheet" href="css/style.css">
     
</head>
<body>
    <header>

        <div class="header-1">
    
            <a href="index.php" class="logo"><i class="fas fa-shopping-basket"></i>Nature's Basket</a>
    
        </div>
    
        <div class="header-2">
    
            <div id="menu-bar" class="fas fa-bars"></div>
    
            <nav class="navbar">
                <a href="index.php">home</a>
                <a href="index.php">category</a>
                <a href="index.php">product</a>
                <a href="index.php">deal</a>
                <a href="index.php">contact Us</a>
            </nav>
    
            <div class="icons">
                <a href="cart.php" class="fas fa-shopping-cart"></a> 
                <a href="#" class="fas fa-user-circle"></a><h2>Hello, <?php echo $user_data['user_name']; ?></h2>  
            </div>
    
        </div>    
    </header>
    <!-- header section ends -->
       
    <h1 style="text-align: center; font-size: 50px; color: #27ae60; padding: 50px;">cart</h1>

    
    <div id="shopping-cart" style="margin: 40px;">

<!--<a id="btnEmpty" href="index.php?action=empty">Empty Cart</a>-->
<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
?>	
<table style="font-size: 1em;" class="tbl-cart" cellpadding="10" cellspacing="1">
<tbody>
<tr style="background-color: #27ae60;height: 30px;font-size: 15px;">
<th style="text-align:left;">Product Name</th>
<th style="text-align:left;">Code</th>
<th style="text-align:right;" width="5%">Quantity</th>
<th style="text-align:right;" width="10%">Unit Price</th>
<th style="text-align:right;" width="10%">Price</th>
<th style="text-align:center;" width="5%">Remove</th>
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
				<td style="text-align:center;"><a href="cart.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="images/icon-delete.png" alt="Remove Item" /></a></td>
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
<td></td>
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
</div>

<div class="cart_footer_link">
<a href="cart.php?action=empty" class="btn" style="background-color: red;">Clear Cart</a>
<a href="index.php" title="Cart" class="btn">Continue Shopping</a>
</div>

<input onClick="javascript:window.open('payment_gateway.php', '_blank');" name="proceed" type="submit" value="proceed to buy" class="btn" style="width: -webkit-fill-available;margin-top: 50px;margin-bottom: 200px">

</div>

  <script>
function toggleAction(id) {
	if(document.getElementById("remove"+id).style.display == 'none') {
		document.getElementById("remove"+id).style.display = 'block';
	} else {
		document.getElementById("remove"+id).style.display = 'none';
	}
}
</script>

   <!-- custom js file link  
<script src="js/script.js"></script> -->


</body>
</html>