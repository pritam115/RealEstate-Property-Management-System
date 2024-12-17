<?php
include("config.php");
session_start();

if (!isset($_SESSION['uemail'])) {
    header("Location: login.php");
    exit();
}

// Handle deletion of favorite property
if (isset($_GET['delete'])) {
    $fav_id = $_GET['delete'];

    $sql = "DELETE FROM favorites WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $fav_id);

    if ($stmt->execute()) {
        $msg = "<p class='alert alert-success'>Favorite removed successfully</p>";
    } else {
        $msg = "<p class='alert alert-warning'>Unable to remove favorite</p>";
    }

    $stmt->close();
    
    // Redirect back to the favorites page without reloading it
    header("Location: add_to_favorites.php?msg=" . urlencode($msg));
    exit();
}

// Handle adding to favorites
if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];
    $uid = $_SESSION['uid'];

    // Check if the property is already in the user's favorites
    $check_query = "SELECT * FROM favorites WHERE pid = ? AND uid = ?";
    $stmt_check = $con->prepare($check_query);
    $stmt_check->bind_param('ii', $pid, $uid);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $msg = "<p class='alert alert-warning'>Property already in favorites</p>";
    } else {
        $insert_query = "INSERT INTO favorites (pid, uid) VALUES (?, ?)";
        $stmt_insert = $con->prepare($insert_query);
        $stmt_insert->bind_param('ii', $pid, $uid);

        if ($stmt_insert->execute()) {
            $msg = "<p class='alert alert-success'>Property added to favorites</p>";
        } else {
            $msg = "<p class='alert alert-warning'>Unable to add property to favorites</p>";
        }

        $stmt_insert->close();
    }

    $stmt_check->close();
    
    // Redirect back to the previous page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
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
<link rel="stylesheet" type="text/css" href="css/color.css">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/login.css">

<!--	Title
	=========================================================-->
<title>Real Estate PHP</title>
</head>
<body>



<div id="page-wrapper">
    <div class="row"> 
        <!--	Header start  -->
		<?php include("include/header.php");?>
        <!--	Header end  -->
        
        <!--	Banner   --->
        <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>User Listed Property</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-left float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">User Listed Property</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
         <!--	Banner   --->
		 
		 
<!-- Submit property -->
<div class="full-row bg-gray">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-12">
                <h2 class="text-secondary double-down-line text-center">Favorite Properties</h2>
                <?php 
                    if(isset($_GET['msg']))  
                    echo $_GET['msg'];    
                ?>
            </div>
        </div>
        <table class="items-list col-lg-12 table-hover" style="border-collapse:inherit;">
            <thead>
                <tr class="bg-dark">
                    <th class="text-white font-weight-bolder">Properties</th>
                    <th class="text-white font-weight-bolder">BHK</th>
                    <th class="text-white font-weight-bolder">Type</th>
                    <th class="text-white font-weight-bolder">Added Date</th>
                    <th class="text-white font-weight-bolder">Status</th>
                    <th class="text-white font-weight-bolder">Delete</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $uid = $_SESSION['uid'];
                $query = mysqli_query($con, "SELECT favorites.id AS fav_id, property.*, user.uname 
                                            FROM favorites 
                                            JOIN property ON favorites.pid = property.pid 
                                            JOIN user ON property.uid = user.uid 
                                            WHERE favorites.uid = '$uid'");
                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td>
                            <img src="admin/property/<?php echo $row['pimage']; ?>" alt="pimage">
                            <div class="property-info d-table">
                                <h5 class="text-secondary text-capitalize">
                                    <a href="propertydetail.php?pid=<?php echo $row['pid']; ?>"><?php echo $row['title']; ?></a>
                                </h5>
                                <span class="font-14 text-capitalize">
                                    <i class="fas fa-map-marker-alt text-success font-13"></i>&nbsp; 
                                    <?php echo $row['location']; ?>
                                </span>
                                <div class="price mt-3">
                                    <span class="text-success">$&nbsp;<?php echo $row['price']; ?></span>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $row['bhk']; ?></td>
                        <td class="text-capitalize">For <?php echo $row['type']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td class="text-capitalize"><?php echo $row['status']; ?></td>
                        <td><a class="btn btn-danger" href="add_to_favorites.php?delete=<?php echo $row['fav_id']; ?>">Remove</a></td>
                    </tr>
                <?php 
                } 
                ?>


            </tbody>
        </table>
    </div>
</div>
<!-- Submit property -->

        
        
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