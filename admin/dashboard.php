<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);


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
  <title>Admin Dashboard</title>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
       <link rel="stylesheet" href="assets/css/style.css"></link>
       <style>
        body{
            background: #475a99;
        }
        #main-content{
            height:70vh;
            background:url("assets/images/logo.png") no-repeat center;
        }
       </style>
  </head>
</head>
<body >
         <?php
            include "./adminHeader.php";
            include "./sidebar.php";
            include_once "config/dbconnect.php";
        ?>
        <div id="main">
            <button class="openbtn" onclick="openNav();" style = "width:85px; border-radius:10px;"><i class="fa fa-home" style="font-size:60px;"></i></button>
        </div>
<div class="content">
    <div id="main-content" class="container allContent-section py-4">
        <div class="row">
           
            <div class="col-sm-3">
                <div class="card">
                    <i class="fa fa-book mb-2" style="font-size: 70px; color:white;"></i>
                    <h4 style="color:white;">Books</h4>
                    <h5 style="color:white;">
                    <?php
                       
                       $sql="SELECT * from products";
                       $result=$conn-> query($sql);
                       $count=0;
                       if ($result-> num_rows > 0){
                           while ($row=$result-> fetch_assoc()) {
                   
                               $count=$count+1;
                           }
                       }
                       echo $count;
                   ?>
                   </h5>
                </div>
            </div>
            <div class="col-sm-3">
            <div class="card">
                    <i class="fa fa-list mb-2" style="font-size: 70px; color:white;"></i>
                    <h4 style="color:white;">Genres</h4>
                    <h5 style="color:white;">
                    <?php
                       
                       $sql="SELECT * from genres";
                       $result=$conn-> query($sql);
                       $count=0;
                       if ($result-> num_rows > 0){
                           while ($row=$result-> fetch_assoc()) {
                   
                               $count=$count+1;
                           }
                       }
                       echo $count;
                   ?>
                   </h5>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <i class="fa fa-male mb-2" style="font-size: 70px; color:white;"></i>
                    <h4 style="color:white;">Authors</h4>
                    <h5 style="color:white;">
                    <?php
                       
                       $sql="SELECT * from authors";
                       $result=$conn-> query($sql);
                       $count=0;
                       if ($result-> num_rows > 0){
                           while ($row=$result-> fetch_assoc()) {
                   
                               $count=$count+1;
                           }
                       }
                       echo $count;
                   ?>
                   </h5>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <i class="fa fa-users mb-2" style="font-size: 70px; color:white;"></i>
                    <h4 style="color:white;">Users</h4>
                    <h5 style="color:white;">
                    <?php
                       
                       $sql="SELECT * from user_details";
                       $result=$conn-> query($sql);
                       $count=0;
                       if ($result-> num_rows > 0){
                           while ($row=$result-> fetch_assoc()) {
                   
                               $count=$count+1;
                           }
                       }
                       echo $count;
                   ?>
                   </h5>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <i class="fa fa-list mb-2" style="font-size: 70px; color:white;"></i>
                    <h4 style="color:white;">Orders</h4>
                    <h5 style="color:white;">
                    <?php
                       
                       $sql="SELECT * from orders";
                       $result=$conn-> query($sql);
                       $count=0;
                       if ($result-> num_rows > 0){
                           while ($row=$result-> fetch_assoc()) {
                   
                               $count=$count+1;
                           }
                       }
                       echo $count;
                   ?>
                   </h5>
                </div>
            </div>
        </div>
        
    </div>
</div>


    <!-- <script type="text/javascript" src="./assets/js/ajaxWork.js"></script>     -->
    <script type="text/javascript" src="assets/js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
 
</html>