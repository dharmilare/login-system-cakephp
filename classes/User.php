<?php
class User{
    private $_db,
            $_data,
            $_isLoggedIn,
            $_cookieName,
            $_sessionName;
        // construction of the property of the class
    public function __construct($user=''){
        $this->_db= DB::getInstance(); //the class property db = connecting to the database 
        $this->_sessionName=config::get('session/session_name'); //getting the session name from init.php to this property
        $this->_cookieName=config::get('remember/cookie_name'); //getting the cookie name from init.php to this property

        //if session exists and find user=true, then the property logged in is true
        if(!$user){
            if(Session::exists($this->_sessionName)){
                $user=Session::get($this->_sessionName);
                if($this->find($user)){
                    $this->_isLoggedIn = true;
                }else{
                    //logout
                }
            }
        }else{
            $this->find($user);
        }
    }
    //method to update the the user details
    public function update($fields= array(), $id=null){
        if(!$id && $this->isLoggedIn()){
            $id=$this->data()->id;
        }
        if(!$this->_db->update('users', $id, $fields)){ //if it does update the db throw this error
            throw new Exception('There was a problem updating.');
        }
    }

    //method to insert/register new user
    public function create($fields=array()){
        if(!$this->_db->insert('users', $fields)){
            throw new Exception('There was a problem creating new account');
        }
    }


    //find user
    public function find($user = null){
        if($user){
            $field = (is_numeric($user)) ? 'id' : 'username'; //find through username or id
            $data = $this->_db->get('users',array($field, '=', $user));
            if($data->count()){
                $this->_data = $data->first();
                return true;
            }
            
        }
        return false;
    }


    //method to login a user
    public function login($username = null , $password = null, $remember=null){
        if(!$username && !$password && $this->exists()){
            Session::put($this->_sessionName, $this->data()->id);
        }else{
            $user = $this->find($username);//find the username

            if($user){
                if($this->data()->password === Hash::make($password)/* ,$this->data()->salt */){//comparing the password in the db and inputted password
                    Session::put($this->_sessionName, $this->data()->id);
                    if($remember){ //when logging in and you click remember
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));
                        if(!$hashCheck->count()){
                            $this->_db->insert('users_session', array( //insert the cookie hash in the db
                                'user_id'=>$this->data()->id,
                                'hash'=>$hash
                            ));
                        }else{
                            $hash= $hashCheck->first()->hash;
                        }
                        Cookie::put($this->_cookieName, $hash, config::get('remember/cookie_expiry'));//insert cookie when you click remember me
                    }
                    return true;
                }
            }
        }
        return false;
    }
    /*
    public function hasPermission($key){
        $group = $this->_db->get('groups', array('id', '=', $this->data()->group));
        if($group->count()){
            $permissions= json_decode($group->first()->permission, true);
            if($permissions[$key]== true){
                return true;
            }
        }
        return false;
    }
    */




    public function exists(){ //to check if the data actually exists
        return (!empty($this->_data)) ? true : false;
    }



    public function logout(){ // to log a user out
        $this->_db->delete('users_session', array('user_id', '=', $this->data()->id));// delete from the users_seesion data in the db when a user logout
        Session::delete($this->_sessionName);//delete current session when a user log out
        Cookie::delete($this->_cookieName);//delete the cookie stored when a user log out
    }


    //method for calling data stored
    public function data(){
        return $this->_data;
    }

    //loggedIn method
    public function isLoggedIn(){
        return $this->_isLoggedIn;
    }
}