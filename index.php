<?php
require_once "core/init.php";

$user=new User();
if($user->isLoggedIn()){
?>
<p>Hello <a href="profile.php?user=<?php echo $user->data()->username; ?>"><?php echo $user->data()->username; ?></a></p>
<ul>
<li><a href="logout.php">Log out</a></li>
<li><a href="update.php">Update details</a></li>
<li><a href="changepassword.php">Change password</a></li>
</ul>
<?php
/*
if($user->hasPermission('admin')){
    echo 'You are an administrator';
}
*/


}else{
    echo '<p>you need to <a href="login.php">Log in</a> or <a href="register.php">Register</a></p>';
}

?>

