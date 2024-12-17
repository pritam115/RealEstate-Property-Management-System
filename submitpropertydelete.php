<?php
include("config.php");
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['uemail'])) {
    header("Location: login.php");
    exit();
}

// Get the property ID and the user ID
$pid = $_GET['id'];
$uid = $_GET['uid'];

// Begin a transaction
$con->begin_transaction();

try {
    // Check if the user is an agent and owns the property
    $sql_check = "SELECT * FROM user WHERE uid = ? AND utype = 'agent'";
    $stmt_check = $con->prepare($sql_check);
    $stmt_check->bind_param('i', $uid);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Prepare the SQL query to delete the favorites related to the property
        $sql_favorites = "DELETE FROM favorites WHERE pid = ?";
        $stmt_favorites = $con->prepare($sql_favorites);
        $stmt_favorites->bind_param('i', $pid);
        $stmt_favorites->execute();
        $stmt_favorites->close();

        // Prepare the SQL query to delete the property
        $sql_property = "DELETE FROM property WHERE pid = ? AND uid = ?";
        $stmt_property = $con->prepare($sql_property);
        $stmt_property->bind_param('ii', $pid, $uid);
        $stmt_property->execute();
        $stmt_property->close();

        // Commit the transaction
        $con->commit();
        $msg = "<p class='alert alert-success'>Property Deleted</p>";
    } else {
        $msg = "<p class='alert alert-warning'>You are not authorized to delete this property</p>";
    }

} catch (Exception $e) {
    // Rollback the transaction if any error occurs
    $con->rollback();
    $msg = "<p class='alert alert-warning'>Property Not Deleted</p>";
}

$con->close();

// Redirect with the message
header("Location: feature.php?msg=" . urlencode($msg));
exit();
?>
