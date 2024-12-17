<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");
///code								
?> 
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Meta Tags -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Real Estate PHP">
<meta name="keywords" content="">
<meta name="author" content="Unicoder">
<link rel="shortcut icon" href="images/favicon.ico">
 
<!--	Fonts
	========================================================-->
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

<!--	Css Link
	========================================================-->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/layerslider.css">
<link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

<!--	Title
	=========================================================-->
<title>Real Estate PHP</title>
<style>
    .favorite-btn{
        font-size:16px;
        color:#28a745;
    }
    .favorite-btn:hover{
        color: #ff163f;
    }
</style>
</head>
<body>


<div id="page-wrapper">
    <div class="row"> 
        <!--	Header start  -->
		<?php include("include/header.php");?>
        <!--	Header end  -->
         
        
        <!--	Property Grid
		===============================================================-->
        <div class="full-row">
            <div class="container">
                <div class="row">
				
					<div class="col-lg-8">
                        <div class="row">
						
                        <?php 
$query = mysqli_query($con, "SELECT property.*, user.uname, user.utype, user.uimage FROM `property`, `user` WHERE property.uid=user.uid ORDER BY date DESC LIMIT 9");
while ($row = mysqli_fetch_array($query)) {
    $is_favorite = false;
    if (isset($_SESSION['uemail']) && isset($_SESSION['uid'])) {
        $is_favorite_query = mysqli_query($con, "SELECT * FROM favorites WHERE pid = '{$row['0']}' AND uid = '{$_SESSION['uid']}'");
        $is_favorite = mysqli_num_rows($is_favorite_query) > 0;
    }
?>
<div class="col-md-6 col-lg-6"> 
    <div class="featured-thumb hover-zoomer mb-4">
        <div class="overlay-black overflow-hidden position-relative"> 
            <img src="admin/property/<?php echo $row['18'];?>" alt="pimage">
            <div class="featured bg-success text-white">New</div>
            <div class="sale bg-success text-white text-capitalize">For <?php echo $row['5'];?></div>
            <div class="price text-primary"><b>$<?php echo $row['13'];?> </b><span class="text-white"><?php echo $row['12'];?> Sqft</span></div>
        </div>
        <div class="featured-thumb-data shadow-one">
            <div class="p-3">
                <?php if(isset($_SESSION['uemail'])) { ?>
                    <!-- Display favorite button based on whether property is already in favorites -->
                    <?php if($is_favorite) { ?>
                        <span class="text-success">Added to Favourites</span>
                    <?php } else { ?>
                        <a href="add_to_favorites.php?pid=<?php echo $row['0'];?>" class="favorite-btn"><i class="fas fa-heart"></i> Add to Favourite</a>
                    <?php } ?>
                <?php } ?>
                <h5 class="text-secondary hover-text-success mb-2 text-capitalize"><a href="propertydetail.php?pid=<?php echo $row['0'];?>"><?php echo $row['1'];?></a></h5>
                <span class="location text-capitalize"><i class="fas fa-map-marker-alt text-success"></i> <?php echo $row['14'];?></span> 
            </div>
            <div class="bg-gray quantity px-4 pt-4">
                <ul>
                    <li><span><?php echo $row['12'];?></span> Sqft</li>
                    <li><span><?php echo $row['6'];?></span> Beds</li>
                    <li><span><?php echo $row['7'];?></span> Baths</li>
                    <li><span><?php echo $row['9'];?></span> Kitchen</li>
                    <li><span><?php echo $row['8'];?></span> Balcony</li>
                </ul>
            </div>
            <div class="p-4 d-inline-block w-100">
                <div class="float-left text-capitalize"><i class="fas fa-user text-success mr-1"></i>By : <?php echo $row['uname'];?></div>
                <div class="float-right"><i class="far fa-calendar-alt text-success mr-1"></i> <?php echo date('d-m-Y', strtotime($row['date']));?></div> 
            </div>
        </div>
    </div>
</div>
<?php } ?>


 

                        </div>
                    </div>
					
                    <div class="col-lg-4">
                        <h4 class="double-down-line-left text-secondary position-relative pb-4 mb-4 mt-5">Featured Property</h4>
                        <ul class="property_list_widget">
							
                            <?php 
                            $query=mysqli_query($con,"SELECT * FROM `property` WHERE isFeatured = 1 ORDER BY date DESC LIMIT 3");
                                    while($row=mysqli_fetch_array($query))
                                    {
                            ?>
                            <li> <img src="admin/property/<?php echo $row['18'];?>" alt="pimage">
                                <h6 class="text-secondary hover-text-success text-capitalize"><a href="propertydetail.php?pid=<?php echo $row['0'];?>"><?php echo $row['1'];?></a></h6>
                                <span class="font-14"><i class="fas fa-map-marker-alt icon-success icon-small"></i> <?php echo $row['14'];?></span>
                                
                            </li>
                            <?php } ?>

                        </ul>
                        
                        <div class="sidebar-widget mt-5">
                            <h4 class="double-down-line-left text-secondary position-relative pb-4 mb-4">Recently Added Property</h4>
                            <ul class="property_list_widget"> 
							
								<?php 
								$query=mysqli_query($con,"SELECT * FROM `property` ORDER BY date DESC LIMIT 6");
										while($row=mysqli_fetch_array($query))
										{
								?>
                                <li> <img src="admin/property/<?php echo $row['18'];?>" alt="pimage">
                                    <h6 class="text-secondary hover-text-success text-capitalize"><a href="propertydetail.php?pid=<?php echo $row['0'];?>"><?php echo $row['1'];?></a></h6>
                                    <span class="font-14"><i class="fas fa-map-marker-alt icon-success icon-small"></i> <?php echo $row['14'];?></span>
                                    
                                </li>
                                <?php } ?>

                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        
        <!--	Footer   start-->
		<?php include("include/footer.php");?>
		<!--	Footer   start-->
        
        <!-- Scroll to top --> 
        <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
        <!-- End Scroll To top --> 
    </div>
</div>
<!-- Wrapper End --> 

<!--	Js Link
============================================================--> 
<script src="js/jquery.min.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/greensock.js"></script> 
<script src="js/layerslider.transitions.js"></script> 
<script src="js/layerslider.kreaturamedia.jquery.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/owl.carousel.min.js"></script> 
<script src="js/tmpl.js"></script> 
<script src="js/jquery.dependClass-0.1.js"></script> 
<script src="js/draggable-0.1.js"></script> 
<script src="js/jquery.slider.js"></script> 
<script src="js/wow.js"></script> 

<script src="js/custom.js"></script>
</body>

</html>