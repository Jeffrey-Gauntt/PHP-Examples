<?php
include ("../includes/config.php");

$adId = $_POST['adId'];

$rate = $_POST['rate'];

$sponsorId = $_POST['sponsorId'];

$dateTime = date("Y-m-d H:i:s");

//updating ad play count and last played in database
$query = mysqli_query($con, "UPDATE ad SET plays = plays + 1, last_played = '$dateTime', amount = plays * '$rate' WHERE id = '$adId'");
$query = mysqli_query($con, "UPDATE adverts SET remaining = remaining - '$rate' WHERE sponsor_id = '$sponsorId'");

//if sponsor balance is depleted
$query = mysqli_query($con, "SELECT remaining FROM adverts WHERE id = '$advertId'");
$array = mysqli_fetch_assoc($query);
$result = $array['remaining'];
if($result <= 0) {
  $query = mysqli_query($con, "UPDATE ad SET status = '2' WHERE id = '$advertId'");
}
?>
