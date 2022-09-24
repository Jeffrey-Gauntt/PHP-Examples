<?php

class newAddress {

  private $con;
  private $errorarray;

  public function __construct($con) {
    $this->con = $con;
    $this->errorarray = array();
  }
  //error message displayed should an error exist
  public function geterror($error) {
    if (!in_array($error, $this->errorarray)) {
      $error = "";
    }
    return "<p class='errorMessage'>$error</p>";
  }


  //add address_____________________________________________
  public function addAddress($username, $coin, $address, $userId) {
    $this->validateCoin($coin);
    $this->validateAddress($address);

    //direct to function to insert into database
    if (empty($this->errorarray)) {      
      return $this->insertNewAddress($username, $coin, $address, $userId);
    }
    else {
      return false;
    }
  }
  
  //insert address form into database
  private function insertNewAddress($username, $coin, $address, $userId) {
    $dateTime = date("Y-m-d H:i:s");
    $result = mysqli_query($this->con, "INSERT INTO addresses VALUES ('', '$userId', '$username', '$coin', '$address', '$dateTime')");

    return $result;//end of process
  }

    //coin
    private function validateCoin($coin) {
      return;
      //TO DO: if any validation needs to be done with coin
    }

  //validate address
  private function validateAddress($address) {

    //addresses only use letters and numbers
    if (preg_match('/[^A-Za-z0-9]@/', $address)) {
      array_push($this->errorarray, constants::$addressNotAlphanumeric);
      return;
    }
    //password correct length
    if (strlen($address) > 100 || strlen($address) < 1) {
      array_push($this->errorarray, constants::$addressLength);
      return;
    }
  }



}



?>