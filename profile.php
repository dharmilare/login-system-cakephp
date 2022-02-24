<?php
require_once 'core/init.php';
if(!$username = Input::get('user')){
    Redirect::to('index.php');
}else{
    $user=new User($username);
    if(!$user->exists()){
        Redirect::to(404);
    }else{
        $data=$user->data();
    }
?>
<h3><?php echo $user->data()->username; ?></h3>
<p>Full name: <?php echo $user->data()->name; ?></p>
<?php
}
