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
    <title>payment gateway</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
body {
  font-family: Arial;
  font-size: 17px;
  padding: 8px;
}

* {
  box-sizing: border-box;
}

.row {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  -ms-flex-wrap: wrap; /* IE10 */
  flex-wrap: wrap;
  margin: 0 -16px;
}

.col-25 {
  -ms-flex: 25%; /* IE10 */
  flex: 25%;
}

.col-50 {
  -ms-flex: 50%; /* IE10 */
  flex: 50%;
}

.col-75 {
  -ms-flex: 75%; /* IE10 */
  flex: 75%;
}

.col-25,
.col-50,
.col-75 {
  padding: 0 16px;
}

.container {
  background-color: #f2f2f2;
  padding: 5px 20px 15px 20px;
  border: 1px solid lightgrey;
  border-radius: 3px;
}

input[type=text] {
  width: 100%;
  margin-bottom: 20px;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 3px;
}

label {
  margin-bottom: 10px;
  display: block;
}

.icon-container {
  margin-bottom: 20px;
  padding: 7px 0;
  font-size: 24px;
}

.btn {
  background-color: #4CAF50;
  color: white;
  padding: 12px;
  margin: 10px 0;
  border: none;
  width: 100%;
  border-radius: 3px;
  cursor: pointer;
  font-size: 17px;
}

.btn:hover {
  background-color: #45a049;
}

a {
  color: #2196F3;
}

hr {
  border: 1px solid lightgrey;
}

span.price {
  float: right;
  color: grey;
}

/* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
@media (max-width: 800px) {
  .row {
    flex-direction: column-reverse;
  }
  .col-25 {
    margin-bottom: 20px;
  } 
}
</style>
</head>
<body>
    

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<div class="row" style="padding: 85px 50px;">
  <div class="col-50">
    <div class="container" >
      <form>
      <div class="col-25">
<h3 style="text-align: center;margin:20px 10px;font-family: lato;">Online Payment Checkout Form</h3>

<label for="fname"><i class="fa fa-user"></i> Full Name</label>
    <input type="textbox" name="name" id="name" placeholder="Enter your name" style="height: 35px;width: -webkit-fill-available;"/><br/><br/>
<label for="fname"><i class="fa fa-money"></i> Amount(In ₹)</label>
    <input type="textbox" name="amt" id="amt" value="<?php if(!empty($total_price)){ echo number_format($total_price,2);} else{ echo "0";}?>" readonly style="height: 35px;width: -webkit-fill-available;"/><br/><br/>
    <input type="button" class="btn" name="btn" id="btn" value="Pay Now" onclick="pay_now()"/>
    </div>      
     </form>
     </div>
     </div>
     </div>

<script>
    function pay_now(){
        var name=jQuery('#name').val();
        var amt=jQuery('#amt').val();
        
         jQuery.ajax({
               type:'post',
               url:'payment_process.php',
               data:"amt="+amt+"&name="+name,
               success:function(result){
                   var options = {
                        "key": "rzp_test_AELSJ0IGmOAlFO", 
                        "amount": amt*100, 
                        "currency": "INR",
                        "name": "Nature's Basket",
                        "description": "Test Transaction",
                        "image": "https://www.dealio.co.in/wp-content/uploads/2020/02/natures-basket.jpg",
                        "handler": function (response){
                           jQuery.ajax({
                               type:'post',
                               url:'payment_process.php',
                               data:"payment_id="+response.razorpay_payment_id,
                               success:function(result){
                                   window.location.href="thank_you.php";
                               }
                           });
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
               }
           });
        
        
    }
</script>

<div style="text-align: center;">
<a href="pay_on_delivery.php" style="text-decoration: none;border: solid;border-radius: 5px;padding: 5px;">pay on delivery?</a>
</div>
</body>
</html>