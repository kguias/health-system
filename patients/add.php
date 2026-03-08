<?php
include "config.php";

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$bloodType = $_POST['bloodType'];
$allergies = $_POST['allergies'];

$code = "P" . rand(1000,9999);

$sql = "INSERT INTO patients 
(patient_code, first_name, last_name, dob, gender, phone, address, blood_type, allergies)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $code, $firstName, $lastName, $dob, $gender, $phone, $address, $bloodType, $allergies);
$stmt->execute();

echo json_encode(["status"=>"success"]);
?>