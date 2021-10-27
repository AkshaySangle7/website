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
    <title>order summary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="css/style.css">

</head>
<body style="font-family: 'Nunito', sans-serif;">


<a  class="logo" style="display: block;text-align: center;"><i class="fas fa-shopping-basket"></i>Nature's Basket</a>
<br><br><br>
<h1 style="text-align: center;text-transform: uppercase;text-decoration-line: underline;font-size: 25px;">Order Summary</h1>
<br>
<?php
if(!isset($_SESSION["rand"])) $_SESSION["rand"] = rand(0,1000);
echo "<h1 style='text-align: center;text-transform: uppercase;text-decoration-line: underline;font-size: x-large;'>Order id: " . $_SESSION["rand"] . "</h1>";
?>
<br>
<h2 style="text-align: center;text-transform: uppercase;text-decoration-line: underline;font-size: large;">Shipping to: <?php echo $user_data['user_name']; ?></h2>
<br>
<div style="padding-inline: 20px;">
<h2 style="text-decoration-line: underline;font-size: 20px;"> Shipping Address:<h3 style="font-size: 20px;"><?php echo $user_data['address'].",".$user_data['pincode']; ?></h3></h2>
</div>
<br>

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
<div style="text-align:center;font-size: 15px;">
	<h3>You will receive your order in 7 days</h3>
</div>
<br><br><br>
<div>
<div style="text-align:center;">
<button id="btn" onclick="print()" style="cursor:pointer;padding: 6px;color: white;background-color: #27ae60;border-radius: 5px;border-color: yellowgreen;"> Print receipt </button>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script>
	$(document).on('click','#btn',function(){
    let pdf = new jsPDF();
    let section=$('body');
    let page= function() {
		pdf.contentWindow.print();
		/*pdf.output('dataurlnewwindow');
		/*pdf.contentWindow.print();
        /*pdf.save('pagename.pdf');*/
       
    };
    pdf.addHTML(section,page);
    })
</script>

<br><br><br><br><br><br>

</div>
</body>
</html>


