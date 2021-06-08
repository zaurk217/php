<?php

// ----- HELPER FUNCTIONS -----

function redirect($location){
    header("Location: $location");
}


function query($sql){
global $connection;
    return mysqli_query($connection, $sql);
}


function confirm($result){
global $connection;
if (!$result) {
        die("QUERY FAILED" . mysqli_error($connection));
    }   
}


function escape_string($string){
global $connection;
return mysqli_real_escape_string($connection, $string);
}


function fetch_array($result){
    return mysqli_fetch_array($result);
}


function set_message($msg){
    if (!empty($msg)) {
        $_SESSION['message'] = $msg;
    } else {
        $msg = "";
    }
}


function display_message(){
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}


function last_id(){
   global $connection; 
   return mysqli_insert_id($connection);
}


/***************************FRONT END FUNCTIONS*************************************/

// ----- GET PRODUCTS -----

function get_products(){
    $query = query("SELECT * FROM products");
    confirm($query);
    while ($row = fetch_array($query)) {
    $product_image = display_image($row['product_image']);    
        
    $product = <<<DELIMETER
         <div class="col-sm-4 col-lg-4 col-md-4">
             <div class="thumbnail">
                 <a href="item.php?id={$row['product_id']}"><img style="height: 150px" src="../resources/{$product_image}" alt=""></a>
                 <div class="caption">
                     <h4 class="pull-right">{$row['product_price']}</h4>
                     <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                     </h4>
                     <p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
                     <a class="btn btn-primary" target="_blank" href="../resources/cart.php?add={$row['product_id']}">Add To Cart</a>
                 </div>
                            
             </div>
         </div>

    DELIMETER;

    echo $product;

    }   
}


function get_products_in_cat_page(){
    $query = query("SELECT * FROM products WHERE product_category_id = " . escape_string($_GET['id']) . "");
    confirm($query);
    while ($row = fetch_array($query)) {
    $product_image = display_image($row['product_image']);    
    $product = <<<DELIMETER
         <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../resources/{$product_image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>
DELIMETER;

    echo $product;

    }   
}


function get_products_in_shop_page(){
    $query = query("SELECT * FROM products");
    confirm($query);
    while ($row = fetch_array($query)) {
    $product_image = display_image($row['product_image']);    
    $product = <<<DELIMETER
         <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../resources/{$product_image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>
DELIMETER;

    echo $product;

    }   
}


function get_categories(){
    $query = query("SELECT * FROM categories");    
    confirm($query);
    while ($row = fetch_array($query)) {
            
    $category_links = <<<DELIMETER
         
    <a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>
DELIMETER;

    echo $category_links;       
    }
}


function login_user(){
    if (isset($_POST['submit'])) {
        $username = escape_string($_POST['username']);
        $password = escape_string($_POST['password']);
        $query = query("SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}'");
        confirm($query);
        if(mysqli_num_rows($query) == 0){
        set_message("Your Username or Password is incorrect!");
        redirect("login.php");    
        } else {
        $_SESSION['username'] = $username;
        set_message("Welcome to Admin {$username}!");
        redirect("admin");
        }
    }
}


function send_message(){
    if (isset($_POST['submit'])) {
        $to        = "zauri722@gmail.com";
        $from_name = $_POST['name'];
        $email     = $_POST['email'];
        $subject   = $_POST['subject'];
        $message   = $_POST['message'];

        $headers = "From: {$from_name} {$email}";

        $result  = mail($to, $subject, $message, $headers);

        if (!$result) {
            set_message("Sorry, your message couldn't sent");
            redirect("contact.php");
        } else {
            set_message("Your message has been sent");
        }
    }
}

/***************************BACK END FUNCTIONS*************************************/

function display_orders(){
    $query = query("SELECT * FROM orders");
    confirm($query);

while ($row = fetch_array($query)) {
$orders = <<<DELIMETER
    <tr>
        <td>{$row['order_id']}</td>
        <td>{$row['order_amount']}</td>
        <td>{$row['order_transaction']}</td>
        <td>{$row['order_status']}</td>
        <td>{$row['order_currency']}</td>
        <td><a class="btn btn-danger" href="../../resources/templates/back/delete_orders.php?id={$row['order_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
    </tr>    
DELIMETER;

echo $orders;   
    }
}

/************************ ADMIN PRODUCTS **********************************************/

function get_products_in_admin(){
$query = query("SELECT * FROM products");
    confirm($query);
    while ($row = fetch_array($query)) {
    $category = show_product_category_title($row['product_category_id']);
    $product_image = display_image($row['product_image']); 

    $product = <<<DELIMETER
         <tr>
            <td>{$row['product_id']}</td>
            <td>{$row['product_title']}<br>
            <a href="index.php?edit_product&id={$row['product_id']}"><img width='100' src="../../resources/{$product_image}" alt=""></a>
            </td>
            <td>{$category}</td>
            <td>{$row['product_price']}</td>
            <th>{$row['product_quantity']}</th>
            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_product.php?id={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
DELIMETER;

    echo $product;
    }   
}


function show_product_category_title($product_category_id){
$category_query = query("SELECT * FROM categories WHERE cat_id = '{$product_category_id}' ");
confirm($category_query);
while ($category_row = fetch_array($category_query)) {
    return $category_row['cat_title']; 
  }
}


function display_image($picture){
    return "uploads" . DS . $picture;
}

/************************ ADDING PRODUCTS IN ADMIN PRODUCTS**********************************************/


function add_product(){
    if (isset($_POST['publish'])) {      
        $product_title       = escape_string($_POST['product_title']);
        $product_category_id = escape_string($_POST['product_category_id']);
        $product_price       = escape_string($_POST['product_price']);
        $product_description = escape_string($_POST['product_description']);
        $product_short_desc  = escape_string($_POST['short_desc']);
        $product_quantity    = escape_string($_POST['product_quantity']);
        $product_image       = escape_string($_FILES['file']['name']);
        $image_temp_location = escape_string($_FILES['file']['tmp_name']);

    //move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);
    copy($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);

    $query = query("INSERT INTO products(product_title, product_category_id, product_price, product_quantity, product_description, short_desc, product_image) VALUES('{$product_title}','{$product_category_id}','{$product_price}','{$product_quantity}','{$product_description}','{$product_short_desc}','{$product_image}')");
    $last_id = last_id();
    confirm($query);
    set_message("New Product With Id {$last_id} Added Successfully");
    redirect("index.php?products");
    }
}


/************************ UPDATING PRODUCTS IN ADMIN PRODUCTS**********************************************/

function update_product(){
    if (isset($_POST['update'])) {      
        $product_title       = escape_string($_POST['product_title']);
        $product_category_id = escape_string($_POST['product_category_id']);
        $product_price       = escape_string($_POST['product_price']);
        $product_description = escape_string($_POST['product_description']);
        $product_short_desc  = escape_string($_POST['short_desc']);
        $product_quantity    = escape_string($_POST['product_quantity']);
        $product_image       = escape_string($_FILES['file']['name']);
        $image_temp_location = escape_string($_FILES['file']['tmp_name']);
    if (empty($product_image)) {
        $get_pic = query("SELECT product_image FROM products WHERE product_id =" . escape_string($_GET['id']) . "");
        confirm($get_pic);
        while ($pic = fetch_array($get_pic)) {
            $product_image = $pic['product_image'];
        }
    }

        //$image_name = date("YmdHisu");
    //move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);
    copy($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);

    $query = "UPDATE products SET ";
    $query .= "product_title       = '{$product_title}', ";
    $query .= "product_category_id = '{$product_category_id}', ";
    $query .= "product_price       = '{$product_price}', ";
    $query .= "product_quantity    = '{$product_quantity}', ";
    $query .= "product_description = '{$product_description}', ";
    $query .= "short_desc          = '{$product_short_desc}', ";
    $query .= "product_image       = '{$product_image}' ";
    $query .= "WHERE product_id=" . escape_string($_GET['id']);
    $send_update_query = query($query);
    confirm($send_update_query);
    set_message("Product Has Been Updated");
    redirect("index.php?products");
    }
}


function show_categories_add_product_page(){
    $query = query("SELECT * FROM categories");    
    confirm($query);
    while ($row = fetch_array($query)) {
            
    $category_options = <<<DELIMETER
         
    <option value="{$row['cat_id']}">{$row['cat_title']}</option>
DELIMETER;

    echo $category_options;         
    }
}


function show_categories_in_admin(){
    $query = "SELECT * FROM categories";
    $category_query = query($query);
    confirm($category_query);
    while ($row = fetch_array($category_query)) {
       $cat_id    = $row['cat_id'];
       $cat_title = $row['cat_title'];

       $category = <<<DELIMETER

       <tr>
           <td>{$cat_id}</td> 
           <td>{$cat_title}</td>
           <td><a class="btn btn-danger" href="../../resources/templates/back/delete_category.php?id={$row['cat_id']}"><span class="glyphicon glyphicon-remove"></span></a></td> 
       </tr>
DELIMETER;

    echo $category;
  }
}


function add_category(){
    if (isset($_POST['add_category'])) {
        $cat_title = escape_string($_POST['cat_title']);
        if (empty($cat_title) || $cat_title == " ") {
            echo "<h3 class='bg-danger'>Category Title Cannot Be Empty!</h3>";
        } else {
        $insert_cat = query("INSERT INTO categories(cat_title) VALUES('{$cat_title}')");
        confirm($insert_cat);
        set_message("New Category" . " " . $cat_title . " Added Successfully");
        }
    }
}


/*************************************** ADMIN USERS ***************************************/

function display_users_in_admin(){
    $query = "SELECT * FROM users";
    $users_query = query($query);
    confirm($users_query);
    while ($row = fetch_array($users_query)) {
       $user_id    = $row['user_id'];
       $username   = $row['username'];
       $password   = $row['password'];
       $email      = $row['email'];

       $user = <<<DELIMETER

       <tr>
           <td>{$user_id}</td> 
           <td>{$username}</td>
           <td>{$email}</td>
           <td><a class="btn btn-danger" href="../../resources/templates/back/delete_user.php?id={$row['user_id']}"><span class="glyphicon glyphicon-remove"></span></a>
               <a class="btn btn-warning" href="index.php?edit_user&id={$row['user_id']}"><span class="glyphicon glyphicon-pencil"></span></a></td> 
       </tr>
DELIMETER;

    echo $user;
  }
}


function add_user(){
    if (isset($_POST['add_user'])) {
    $username   = escape_string($_POST['username']);
    $password   = escape_string($_POST['password']);
    $email      = escape_string($_POST['email']);
    $user_photo = escape_string($_FILES['file']['name']);
    $photo_temp = escape_string($_FILES['file']['tmp_name']);
    move_uploaded_file($photo_temp, UPLOAD_DIRECTORY . DS . $user_photo);

    $query = query("INSERT INTO users(username,password,email,user_photo) VALUES('{$username}','{$password}','{$email}','{$user_photo}' )");
    confirm($query);
    set_message("USER CREATED");
    redirect("index.php?users");

    }
}


function edit_user(){
    if (isset($_POST['edit_user'])) {
    $username   = escape_string($_POST['username']);
    $password   = escape_string($_POST['password']);
    $email      = escape_string($_POST['email']);
    $user_photo = escape_string($_FILES['file']['name']);
    $photo_temp = escape_string($_FILES['file']['tmp_name']);
    move_uploaded_file($photo_temp, UPLOAD_DIRECTORY . DS . $user_photo);

    $query = query("UPDATE users SET username = '{$username}', password = '{$password}', email = '{$email}', user_photo = '{$user_photo}' WHERE user_id=" . escape_string($_GET['id']));
    confirm($query);
    set_message("USER UPDATED");
    redirect("index.php?users");

    }
}


function get_reports(){
$query = query("SELECT * FROM reports");
    confirm($query);
    while ($row = fetch_array($query)) {

    $reports = <<<DELIMETER
         <tr>
            <td>{$row['report_id']}</td>
            <td>{$row['product_id']}</td>
            <td>{$row['product_title']}</td>
            <td>{$row['order_id']}</td>
            <td>{$row['product_price']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_report.php?id={$row['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
DELIMETER;

    echo $reports;
    }   
}


/************************* SLIDE FUNCTIONS *************************/

function add_slides(){
    if (isset($_POST['add_slide'])) {
        $slide_title     = escape_string($_POST['slide_title']);
        $slide_image     = escape_string($_FILES['file']['name']);
        $slide_image_loc = escape_string($_FILES['file']['tmp_name']);

        if (empty($slide_title) || empty($slide_image)) {
            echo "<p class='bg-danger'>This Field Canno't Be Empty!!!</p>";       
        } else {
            //move_uploaded_file($slide_image_loc, UPLOAD_DIRECTORY . DS . $slide_image);
            copy($slide_image_loc, UPLOAD_DIRECTORY . DS . $slide_image);

            $query = query("INSERT INTO slides(slide_title, slide_image) VALUES('{$slide_title}', '{$slide_image}')");
            confirm($query);
            set_message("Slide Added");
            redirect("index.php?slides");
        }
    }
}


function get_current_slide_in_admin(){
    $query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
    confirm($query);
    while ($row = fetch_array($query)) {
    $slide_image = display_image($row['slide_image']);    
    $slide_active_admin = <<<DELIMETER
      
        <img class="img-responsive" src="../../resources/{$slide_image}" alt="">
DELIMETER;    

echo $slide_active_admin;        
    }
}


function get_active_slide(){
    $query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
    confirm($query);
    while ($row = fetch_array($query)) {
    $slide_image = display_image($row['slide_image']);    
    $slide_active = <<<DELIMETER
    
    <div class="item active">
        <img class="slide-image" src="../resources/{$slide_image}" alt="">
    </div>
DELIMETER;    

echo $slide_active;        
    }
}


function get_slides(){
    $query = query("SELECT * FROM slides");
    confirm($query);
    while ($row = fetch_array($query)) {
    $slide_image = display_image($row['slide_image']);    
    $slides = <<<DELIMETER
    
    <div class="item">
        <img class="slide-image" src="../resources/{$slide_image}" alt="">
    </div>
DELIMETER;    

echo $slides;        
    }
}


function get_slide_thubnails(){
    $query = query("SELECT * FROM slides ORDER BY slide_id ASC ");
    confirm($query);
    while ($row = fetch_array($query)) {
    $slide_image = display_image($row['slide_image']);    
    $slide_thumb_admin = <<<DELIMETER
    
        <div class="col-xs-6 col-md-3 image_container">
            <a href="index.php?delete_slide_id={$row['slide_id']}">
                <img class="img-responsive slide_image" src="../../resources/{$slide_image}" alt="">
            </a>
            <div class="caption">
                <p>{$row['slide_title']}</p>
            </div>

        </div>
DELIMETER;    

echo $slide_thumb_admin;        
    }
}



?>