<?php 

namespace src;
use src\libraries\Utils as Utils;

class User {
    
    
    public function createUser($user_creation_inputs){
        if(empty($user_creation_inputs['user_name'])){
            return Utils::customError('User name is mandatory');
        }
        if(empty($user_creation_inputs['password'])){
            return Utils::customError('Password is mandatory');
        }
        
        if(  $this->checkIfUserExists($user_creation_inputs['user_name']) ){
            return Utils::customError('User already exists. Please choose another user_name');
        }
        
        if($this->addUser($user_creation_inputs)){
            return Utils::sendResponse('User Created Succesfully. Please Login to the application');
        };
    }
    
    public function checkIfUserExists($user_name){
        $user_data = $this->getAllUsersDetails();
        
        if(!empty($user_data[$user_name])){
            return true;
        }
        return false;
    }


    protected function getAllUsersDetails(){
        return $this->getDecryptedFileData();
    }
    
    protected function getDecryptedFileData(){
        $data = [];
        $file_path = !empty($_ENV['user_file_path']) ? $_ENV['user_file_path']  : '' ;
        if(!empty($file_path) && file_exists($file_path)){
            $data = file_get_contents($file_path);
            $data = !empty($data) ? unserialize($data) : [] ;
        }
        return $data;
    }
    
    protected function addUser($user_creation_inputs){
        $file_path = !empty($_ENV['user_file_path']) ? $_ENV['user_file_path']  : '' ;
        $get_all_user_details = $this->getAllUsersDetails();
        $new_user_data = [];
        $new_user_data[$user_creation_inputs['user_name']] = [];
        $new_user_data[$user_creation_inputs['user_name']]['password'] = $user_creation_inputs['password'];
        $new_user_data = array_merge($new_user_data , $get_all_user_details);
        $user_data = serialize($new_user_data);
        file_put_contents($file_path, $user_data);
        return true;
    }
    
    public function loginUser($user_login_inputs){
        if(empty($user_login_inputs['user_name'])){
            return Utils::customError('User name is mandatory');
        }
        if(empty($user_login_inputs['password'])){
            return Utils::customError('Password is mandatory');
        }
        
        if(! $this->isUserCredentialsValid($user_login_inputs)){
            return Utils::customError('User name or password is wrong');
        }
        $this->setSessionData($user_login_inputs);
        return Utils::sendResponse('User Logged In Succesfully');   
    }
    
    protected function isUserCredentialsValid($user_login_inputs){
        $user_data = $this->getAllUsersDetails();
        $user_name = $user_login_inputs['user_name'];
        $password = $user_login_inputs['password'];
        if(!empty($user_data[$user_name]) && $user_data[$user_name]['password'] ==  $password ){
            return true;
        }
        return false;
        
    }
    
    protected function setSessionData($user_login_inputs){
        $_SESSION['user_name'] =  $user_login_inputs['user_name'];
    }
}