

<?php 

   /* if (isset($_GET['id'])) {
       $query = query("SELECT * FROM users WHERE user_id = " . escape_string($_GET['id']) . " ");
       confirm($query);
       while ($row = fetch_array($query)) {
        $username   = escape_string($row['username']);
        $password   = escape_string($row['password']);
        $email      = escape_string($row['email']);
        $user_photo = escape_string($row['user_photo']);
  
       }
    
    update_product();   

    }*/

edit_user();

?>
  <h1 class="page-header">
      Edit User
      <small>Page</small>
  </h1>


<div class="col-md-6 user_image_box">   
  <span id="user_admin" class='fa fa-user fa-4x'></span>
</div>


<form action="" method="post" enctype="multipart/form-data">
  <div class="col-md-6">
     <div class="form-group">    
        <input type="file" name="file">       
     </div>

     <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" class="form-control">  
     </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" name="email" class="form-control"> 
     </div>

<!-- 
      <div class="form-group">
        <label for="first name">First Name</label>
        <input type="text" name="first_name" class="form-control">  
     </div> -->
<!-- 
      <div class="form-group">
        <label for="last name">Last Name</label>
        <input type="text" name="last_name" class="form-control"> 
     </div> -->

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control"> 
     </div>

      <div class="form-group">
        <a id="user-id" class="btn btn-danger" href="">Delete</a>
        <input type="submit" name="edit_user" class="btn btn-primary pull-right" value="Edit User" >  
     </div>

  </div>
</form>