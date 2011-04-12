<?php

class User extends CActiveRecord
{
    const ROLE_ADMIN = 'admin';
    const ROLE_MODER = 'moderator';
    const ROLE_USER  = 'user';

    const STATUS_REGISTER = 'registered';
    const STATUS_ACTIVE   = 'active';
    const STATUS_BLOCKED  = 'blocked';
    const STATUS_REMOVED  = 'removed';

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{users}}';
	}

    public function rules()
    {
        return array(
            array('email, password, role, status, salt', 'required'),
            array('email, username, password, role, status', 'required', 'on'=>'update'),
            array('email', 'email'),
            array('id, username, email, password, role, status, salt, created, updated, logined, ip, hashCode', 'safe', 'on'=>'search'),
        );
    }

    public function validatePassword($password)
    {
        return $this->hashPassword($password,$this->salt)===$this->password;
    }

    public function hashPassword($password,$salt)
    {
        return md5($salt.$password);
    }

    public function generateSalt()
    {
        return md5(uniqid('',true));
    }

    protected function afterFind()
    {
        if(parent::afterFind())
        {
            $this->ip = long2ip($this->ip);
            return true;
        } else
            return false;
    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            $this->ip = ip2long($_SERVER['REMOTE_ADDR']);
            if($this->isNewRecord)
                $this->created = date('Y-m-d H:i:s');
            else 
                $this->updated = date('Y-m-d H:i:s');
            return true;
        }
        else
            return false;
    }

    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('username',$this->username,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('role',$this->role,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('salt',$this->salt,true);
        $criteria->compare('created',$this->created,true);
        $criteria->compare('updated',$this->updated,true);
        $criteria->compare('logined',$this->logined,true);
        $criteria->compare('ip',$this->ip);
        $criteria->compare('hashCode',$this->hashCode,true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        ));
    }

    public function activate($email=null, $key=null)
    {
        if ($email != null && $key != null)
        {
            if($user = self::model()->find("email = '{$email}'"))
            {
                if ($user->status != self::STATUS_REGISTER)
                    return false;
                if ($user->hashCode == $key)
                {
                    $user->status = self::STATUS_ACTIVE;
                    if($user->save(false, array('status')))
                    {
                        return $user;
                    }
                }
            }
        }
        return false;
    }

    public static function generatePassword()
    { 
        $consonants = array("b","c","d","f","g","h","j","k","l","m","n","p","r","s","t","v","w","x","y","z"); 
        $vocals = array("a","e","i","o","u"); 
   
        $password = '';
        srand((double) microtime() * 1000000);
        for ($i = 1; $i <= 4; $i++)
        {
            $password .= $consonants[rand(0, 19)];
            $password .= $vocals[rand(0, 4)];
        }
        $password .= rand(0, 9);
        return $password;
    }
}