<!-- Sidebar -->
<?php
   
   include_once "./config/dbconnect.php";

   ?>
<div class="sidebar" id="mySidebar">
<div class="side-header">
    <img src="./assets/images/user.png" width="120" height="120" alt="BookNook"> 
    <h5 style="margin-top:10px;">Hello, <?php
    echo $_SESSION['adminname']; 
    ?></h5>
</div>

<hr style="border:1px solid; background-color:red; border-color:red;">
    <a href="./index.php" ><i class="fa fa-home"></i> Dashboard</a>
    <a href="users.php"   onclick="location.href='users.php'" ><i class="fa fa-users"></i> Users</a>


    <a href="books.php"onclick="location.href='books.php'" ><i class="fa fa-book"></i> Books</a>


    <a href="genres.php"   onclick="location.href='genres.php'" ><i class="fa fa-list"></i> Genres</a>


    <a href="authors.php"   onclick="location.href='authors.php'" ><i class="fa fa-male"></i>  Authors</a>


    <a href="orders.php"   onclick="location.href='orders.php'" ><i class="fa fa-list"></i>  Orders</a>


    <a href="messages.php"   onclick="location.href='messages.php'" ><i class="fa-solid fa-envelope"></i>Messages</a>

    <a href="settings.php"   onclick="location.href='settings.php'" ><i class="fa solid fa-gear"></i> Settings</a>
</div>
 



