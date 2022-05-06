<?php
session_start();
if(isset($_SESSION['user'])){
    header('location:profile.php');
    exit();
}
if(isset($_POST['submit'])){
include 'conn-db.php';
   $name=filter_var($_POST['name'],FILTER_SANITIZE_STRING);
   $password=filter_var($_POST['password'],FILTER_SANITIZE_STRING);
   $email=filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);

   $errors=[];
   // validate name
   if(empty($name)){
       $errors[]="must enter the name";
   }elseif(strlen($name)>100){
       $errors[]=" name must be less than 100 letter  ";
   }

   // validate email
   if(empty($email)){
    $errors[]="you must enter the email";
   }elseif(filter_var($email,FILTER_VALIDATE_EMAIL)==false){
    $errors[]="invalid email";
   }

   $stm="SELECT email FROM users WHERE email ='$email'";
   $q=$conn->prepare($stm);
   $q->execute();
   $data=$q->fetch();

   if($data){
     $errors[]="this email already exists";
   }


   // validate password
   if(empty($password)){
        $errors[]="must enter the password  ";
   }elseif(strlen($password)<6){
    $errors[]="password must be 6 or more than";
}



   // insert or errros 
   if(empty($errors)){
      // echo "insert db";
      $password=password_hash($password,PASSWORD_DEFAULT);
      $stm="INSERT INTO users (name,email,password) VALUES ('$name','$email','$password')";
      $conn->prepare($stm)->execute();
      $_POST['name']='';
      $_POST['email']='';

      $_SESSION['user']=[
        "name"=>$name,
        "email"=>$email,
      ];
      header('location:profile.php');
   }
}

?>



<form action="register.php" method="POST">
    <?php 
        if(isset($errors)){
            if(!empty($errors)){
                foreach($errors as $msg){
                    echo $msg . "<br>";
                }
            }
        }
    ?>
    <input type="text" value="<?php if(isset($_POST['name'])){echo $_POST['name'];} ?>" name="name" placeholder="name"><br><br>
    <input type="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>" name="email" placeholder="email"><br><br>
    <input type="password" name="password" placeholder="password"><br><br>
    <input type="submit" name="submit" value="Register">
    <br><br>
    <a href="login.php">login</a><br><br><br>
</form>