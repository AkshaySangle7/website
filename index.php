<?php 
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);



require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM product_tb WHERE code='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["code"],$_SESSION["cart_item"])) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["code"] == $k)
								$_SESSION["cart_item"][$k]["quantity"] = $_POST["quantity"];
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
}
}

    $session_items = 0;
if(!empty($_SESSION["cart_item"])){
	$session_items = count($_SESSION["cart_item"]);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nature's Basket</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<!-- header section starts  -->

<header>

    <div class="header-1">

        <a href="#" class="logo"><i class="fas fa-shopping-basket"></i>Nature's Basket</a>

        <!--<form action="" class="search-box-container">
            <input type="search" id="search-box" placeholder="search here...">
            <label for="search-box" class="fas fa-search" value="submit"></label>
        </form>-->
		<h2><a href="logout.php" style="border: solid;border-color: chocolate;color: red;padding: 2px;border-radius: 5px;">Logout</a></h2>
    </div>

    <div class="header-2">

        <div id="menu-bar" class="fas fa-bars"></div>

        <nav class="navbar">
            <a href="#home">home</a>
            <a href="#category">category</a>
            <a href="#product">product</a>
            <a href="#deal">deal</a>
            <a href="#contactus">contact us</a>
        </nav>

        <div class="icons">
            <a href="cart.php" class="fas fa-shopping-cart"></a>
            <a href="#" class="fas fa-user-circle"></a><h2>Hello, <?php echo $user_data['user_name']; ?></h2>
        </div>

    </div>

</header>

<!-- header section ends -->

<!-- home section starts  -->

<section class="home" id="home">

    <div class="image">
        <img src="images/home-img.png" alt="">
    </div>

    <div class="content">
        <span>fresh and organic</span>
        <h3>your daily need products</h3>
        <a href="#product" class="btn">get started</a>
    </div>

</section>

<!-- home section ends -->

<!-- banner section starts  -->

<section class="banner-container">

    <div class="banner">
        <img src="images/banner-1.jpg" alt="">
        <div class="content">
            <h3>special offer</h3>
            <p>upto 45% off</p>
            <a href="#product" class="btn">check out</a>
        </div>
    </div>

    <div class="banner">
        <img src="images/banner-2.jpg" alt="">
        <div class="content">
            <h3>limited offer</h3>
            <p>upto 50% off</p>
            <a href="#product" class="btn">check out</a>
        </div>
    </div>

</section>

<!-- banner section ends -->

<!-- category section starts  -->

<section class="category" id="category">

    <h1 class="heading">shop by <span>category</span></h1>

    <div class="box-container">

        <div class="box">
            <h3>vegitables</h3>
            <p>upto 50% off</p>
            <img src="images/category-1.png" alt="">
            <a href="#" class="btn">shop now</a>
        </div>
        <div class="box">
            <h3>juice</h3>
            <p>upto 44% off</p>
            <img src="images/category-2.png" alt="">
            <a href="#" class="btn">shop now</a>
        </div>
        
        <div class="box">
            <h3>fruits</h3>
            <p>upto 12% off</p>
            <img src="images/category-4.png" alt="">
            <a href="#" class="btn">shop now</a>
        </div>

    </div>

</section>

<!-- category section ends -->

<!-- product section starts  -->

<section class="product" id="product">

    <h1 class="heading">latest <span>products</span></h1>


    <div class="box-container">
    <?php
	$product_array = $db_handle->runQuery("SELECT * FROM product_tb ORDER BY id ASC");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
        <div class="box">
        <form method="post" action="index.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">

            <span class="discount">-<?php echo $product_array[$key]["discount"]; ?>%</span>
            <div class="icons">
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-share"></a>
                <a href="#" class="fas fa-eye"></a>
            </div>
            <img src="<?php echo $product_array[$key]["image"]; ?>" alt="">
            <h3><?php echo $product_array[$key]["name"]; ?></h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <div class="price"> <?php echo "₹".$product_array[$key]["price"]; ?> <span> ₹<?php echo $product_array[$key]["real_price"]; ?> </span> </div>
            <div class="quantity">
                <span>quantity : </span>
                <input type="number" name="quantity" min="1" max="1000" value="1">
                <span> /kg </span>
            </div>
            <div style="font-size: large;">
                availible : <?php echo $product_array[$key]["availible"]; ?>
            </div>
            <script>
                 function AddItemAlert() {
                 alert ("Item Added!");
                                      }
            </script>
            <input onClick="AddItemAlert()" type="submit" value="Add to cart" class="btn" style="text-align: center;width: -webkit-fill-available;">
        </form>
        </div>

       
    <?php
        }
    }   
    ?>
</section>

<!-- product section ends -->

<!-- deal section starts  -->

<section class="deal" id="deal">

    <div class="content">

        <h3 class="title">deal of the day</h3>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam possimus voluptates commodi laudantium! Doloribus sint voluptatibus quaerat sequi suscipit nulla?</p>

        <div class="count-down">
            <div class="box">
                <h3 id="day">00</h3>
                <span>day</span>
            </div>
            <div class="box">
                <h3 id="hour">00</h3>
                <span>hour</span>
            </div>
            <div class="box">
                <h3 id="minute">00</h3>
                <span>minute</span>
            </div>
            <div class="box">
                <h3 id="second">00</h3>
                <span>second</span>
            </div>
        </div>

        <a href="#" class="btn">check the deal</a>

    </div>

</section>

<!-- deal section ends -->

<!-- contactus section starts  -->

<section class="contactus" id="contactus">

    <h1 class="heading"> <span>contact</span> us </h1>
    
   
    
    <form action="insert_contactUs.php" method="post" style="text-align: center;padding:2rem;border:.1rem solid rgba(0,0,0,.3);">

        <div class="inputBox" style="display: flex;justify-content: space-between;flex-wrap: wrap;">
            <input type="text" name="name" placeholder="name" style="padding:1rem;font-size: 1.7rem;background:#f7f7f7;text-transform: none;margin:1rem 0;border:.1rem solid rgba(0,0,0,.3);width: 49%;">
            <input type="email" name="email" placeholder="email" style="padding:1rem;font-size: 1.7rem;background:#f7f7f7;text-transform: none;margin:1rem 0;border:.1rem solid rgba(0,0,0,.3);width: 49%;">
        </div>

        <div class="inputBox" style="display: flex;justify-content: space-between;flex-wrap: wrap;">
            <input type="number" name="Mobile_number" placeholder="Mobile_number" style="padding:1rem;font-size: 1.7rem;background:#f7f7f7;text-transform: none;margin:1rem 0;border:.1rem solid rgba(0,0,0,.3);width: 49%;">
            <input type="text" name="subject" placeholder="subject" style="padding:1rem;font-size: 1.7rem;background:#f7f7f7;text-transform: none;margin:1rem 0;border:.1rem solid rgba(0,0,0,.3);width: 49%;">
        </div>

        <textarea placeholder="message" name="message" id="" cols="30" rows="10" style="padding:1rem;font-size: 1.7rem;background:#f7f7f7;text-transform: none;margin:1rem 0;border:.1rem solid rgba(0,0,0,.3);width: 49%;height: 20rem;resize: none;width: 100%;"></textarea>

        <input type="submit" value="send message" class="btn">

    </form>
   
</section>

<!-- contact section ends -->

<!-- newsletter section starts  -->

<section class="newsletter">

    <h3>subscribe us for latest updates</h3>

    <form action="">
        <input class="box" type="email" placeholder="enter your email">
        <input type="submit" value="subscribe" class="btn">
    </form>

</section>

<!-- newsletter section ends -->

<!-- footer section starts  -->

<section class="footer">

    <div class="box-container">

        <div class="box">
            <a href="#" class="logo"><i class="fas fa-shopping-basket"></i>Nature's Basket</a>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ullam culpa sit enim nesciunt rerum laborum illum quam error ut alias!</p>
            <div class="share">
                <a href="#" class="btn fab fa-facebook-f"></a>
                <a href="#" class="btn fab fa-twitter"></a>
                <a href="#" class="btn fab fa-instagram"></a>
                <a href="#" class="btn fab fa-linkedin"></a>
            </div>
        </div>
        
        <div class="box">
            <h3>our location</h3>
            <div class="links">
                <a href="#">india</a>
                <a href="#">USA</a>
                <a href="#">france</a>
                <a href="#">japan</a>
                <a href="#">russia</a>
            </div>
        </div>

        <div class="box">
            <h3>quick links</h3>
            <div class="links">
                <a href="#">home</a>
                <a href="#">category</a>
                <a href="#">product</a>
                <a href="#">deal</a>
                <a href="#">contact</a>
            </div>
        </div>

        <div class="box">
            <h3>download app</h3>
            <div class="links">
                <a href="#">google play</a>
                <a href="#">window xp</a>
                <a href="#">app store</a>
            </div>
        </div>

    </div>

    <h1 class="credit"> created by <span> Akshay Sangle </span> | all rights reserved! </h1>

</section>

<!-- footer section ends -->



<!-- custom js file link  -->
<script src="js/script.js"></script>
    
</body>
</html>