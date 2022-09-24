<?php
include ("../includes/config.php");

$adId = $_POST['adId'];

$rate = $_POST['rate'];

$sponsorId = $_POST['sponsorId'];

$dateTime = date("Y-m-d H:i:s");

//updating ad play count and last played in database
$query = mysqli_query($con, "UPDATE sponsor_ads SET plays = plays + 1, last_played = '$dateTime', cost = plays * '$rate' WHERE ad_id = '$adId'");
$query = mysqli_query($con, "UPDATE sponsors SET remaining_balance = remaining_balance - '$rate' WHERE sponsor_id = '$sponsorId'");

//if sponsor balance is depleted
$query = mysqli_query($con, "SELECT remaining_balance FROM sponsors WHERE sponsor_id = '$sponsorId'");
$array = mysqli_fetch_assoc($query);
$result = $array['remaining_balance'];
if($result <= 0) {
  $query = mysqli_query($con, "UPDATE sponsor_ads SET statuss = 'Inactive' WHERE sponsor_id = '$sponsorId'");
}
?>