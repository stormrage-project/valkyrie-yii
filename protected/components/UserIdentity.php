<?php

class UserIdentity extends CUserIdentity
{
    protected $_id;
 
    public function authenticate(){
        $user = User::model()->find('LOWER(email)=? AND status=?', array(strtolower($this->username), User::STATUS_ACTIVE));

        if($user===null)
        {
            if(Yii::app()->params['allowExternalLogin'])
            {
                switch(Yii::app()->params['externalLoginMethod'])
                {
                    case 'ipb': default: $bridge = Yii::app()->ipbBridge; break;
                    case 'vb': $bridge = Yii::app()->vbBridge; break;
                }
                $data = $bridge->getUserData(strtolower($this->username), $this->password);
                if($data)
                {
                    $user = new User();
                    $user->email      = $this->username;
                    $user->username   = $bridge->username;
                    $user->role       = $bridge->userRole;
                    $user->status     = User::STATUS_ACTIVE;
                    $user->hashCode   = md5($bridge->email . uniqid());
                    $user->salt       = User::generateSalt();
                    $user->password   = User::hashPassword($this->password, $user->salt);
                    $user->setIsNewRecord(true);
                }
            }
        }

        if($user===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if(!$user->validatePassword($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else {
            $user->logined = date('Y-m-d H:i:s');
            if($user->save())
            {
                $this->_id = $user->id;
                $this->username = $user->username;
                $this->errorCode = self::ERROR_NONE;
            }
        }
       return !$this->errorCode;
    }
 
    public function getId(){
        return $this->_id;
    }
}