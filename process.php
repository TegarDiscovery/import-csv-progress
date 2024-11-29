<?php
$connect = new PDO("mysql:host=localhost;dbname=testing", "root", "");

$query = "SELECT COUNT(*) as total FROM tbl_sample";
$statement = $connect->prepare($query);
$statement->execute();

$row = $statement->fetch(PDO::FETCH_ASSOC);
echo $row['total'];
