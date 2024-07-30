<?php
session_start();
include "./adminHeader.php";
include "./sidebar.php";
include_once "config/dbconnect.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// check if the user is already logged in
if(!isset($_SESSION['adminname']))
{
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Authors</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body {
      background: url("assets/images/logo1.png") no-repeat center;
      background-color: #475a99;
    }
    table {
      align-self: center;
      text-align: center;
      border: 2px solid #fff;
      margin-top: 30px;
      margin-left: 80;
      width: calc(100% - 200px); /* Adjust width to fit container */
      border-collapse: separate; /* Ensure borders are separated */
      border-spacing: 0; /* Remove spacing between cells */
    }
    .table th, .table td {
      font-size: 1.2rem;
      color: #fff;
      vertical-align: middle;
      border-bottom: 1px solid #ddd; /* Add bottom border for rows */
      padding: 12px; /* Add padding for better readability */
    }
    .table thead th {
      background-color: #4a5568;
    }
    .table tbody tr:nth-child(even) {
      background-color: rgba(74, 85, 104, 0.5);
    }
    .table tbody tr:nth-child(odd) {
      background-color: rgba(74, 85, 104, 0.7);
    }
    .action-buttons {
      display: flex;
      justify-content: center;
      gap: 10px;
    }
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s, transform 0.3s;
    }
    .btn-edit {
      background-color: #007bff;
      color: #fff;
    }
    .btn-edit:hover {
      background-color: #0056b3;
      transform: scale(1.05);
    }
    .btn-delete {
      background-color: #dc3545;
      color: #fff;
    }
    .btn-delete:hover {
      background-color: #c82333;
      transform: scale(1.05);
    }

    .add-author-container {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.btn-add-author {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
  background-color: #28a745;
  color: #fff;
  transition: background-color 0.3s, transform 0.3s;
}

.btn-add-author:hover {
  background-color: #218838;
  transform: scale(1.05);
}

  </style>
</head>
<body>
  <div id="main">
    <button class="openbtn" onclick="openNav();" style="width:90px; border-radius:10px;">
      <i class="fa fa-male" style="font-size:60px;"></i>
    </button>
  </div>
  <div class="container mx-auto my-8">
    <div class="overflow-x-auto bg-transparent shadow-md rounded-lg p-6">
      <table class="min-w-full bg-transparent table">
        <thead class="bg-gray-800 text-white">
          <tr>
            <th class="py-2 px-4">Author ID</th>
            <th class="py-2 px-4">Author Name</th>
            <th class="py-2 px-4">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b">
            <td class="py-2 px-4">1</td>
            <td class="py-2 px-4">J.K. Rowling</td>
            <td class="py-2 px-4">
              <div class="action-buttons">
                <button class="btn btn-edit">Edit</button>
                <button class="btn btn-delete">Delete</button>
              </div>
            </td>
          </tr>
          <tr class="border-b bg-gray-100">
            <td class="py-2 px-4">2</td>
            <td class="py-2 px-4">George R.R. Martin</td>
            <td class="py-2 px-4">
              <div class="action-buttons">
                <button class="btn btn-edit">Edit</button>
                <button class="btn btn-delete">Delete</button>
              </div>
            </td>
          </tr>
          <tr class="border-b">
            <td class="py-2 px-4">3</td>
            <td class="py-2 px-4">J.R.R. Tolkien</td>
            <td class="py-2 px-4">
              <div class="action-buttons">
                <button class="btn btn-edit">Edit</button>
                <button class="btn btn-delete">Delete</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="add-author-container">
  <button class="btn btn-add-author">Add More Authors</button>
</div>

  <!-- <script type="text/javascript" src="./assets/js/ajaxWork.js"></script> -->
  <script type="text/javascript" src="assets/js/script.js"></script>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
