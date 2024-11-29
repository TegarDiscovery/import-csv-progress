<?php
header('Content-type: text/html; charset=utf-8');
session_start();

if (isset($_SESSION['csv_file_name'])) {
   $connect = new PDO("mysql:host=localhost;dbname=testing", "root", "");
   $file_path = 'file/' . $_SESSION['csv_file_name'];

   if (($file_data = fopen($file_path, 'r')) !== false) {
      fgetcsv($file_data); // Skip header row

      while ($row = fgetcsv($file_data)) {
         $data = [
            ':first_name' => htmlspecialchars($row[0]),
            ':last_name' => htmlspecialchars($row[1])
         ];

         $query = "INSERT INTO tbl_sample (first_name, last_name) VALUES (:first_name, :last_name)";
         $statement = $connect->prepare($query);
         $statement->execute($data);

         sleep(1);
      }

      fclose($file_data);
      unset($_SESSION['csv_file_name']);
   }
}
