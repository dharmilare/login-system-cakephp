<?php
class Validate{
    private $_passed=false,
            $_errors=array(),
            $_db=null;

    public function __construct(){ //construct the property db
        $this->_db = DB::getInstance();
    }

    //method to check if the validation conditions are met
    public function check($source, $items=array()){
        foreach($items as $item=>$rules){
            foreach($rules as $rule=>$rule_value){
                $value=trim($source[$item]);
                //required conditions
                if($rule === 'required' && empty($value)){
                    $this->addError("{$item} is required");
                }elseif(!empty($value)){
                    switch($rule){
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            }
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError("{$item} must be a maximum of {$rule_value} characters.");
                            }
                        break;
                        case'matches':
                            if($value != $source[$rule_value]){
                                $this->addError("{$rule_value} must match {$item}");
                            }
                        break;
                        case 'unique':
                            $check=$this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()){
                                $this->addError("{$item} already exists.");
                            }
                        break;
                    }
                }
            }
        }
        //if there is no error then validaton passed is true
        if(empty($this->_errors)){
            $this->_passed=true;
        }
        return $this;
    }
    //method within the function for calling error
    private function addError($error){
        $this->_errors[]=$error;
    }
    //method for calling errors if the validation are not passed
    public function errors(){
        return $this->_errors;
    }
    //method when all validation are passed
    public function passed(){
        return $this->_passed;
    }
}