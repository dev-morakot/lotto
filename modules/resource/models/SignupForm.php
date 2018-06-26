<?php
namespace app\modules\resource\models;

use app\modules\resource\models\ResUsers;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $code;
    public $firstname;
    public $lastname;
    public $active;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\ResUsers', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => 'app\models\ResUsers', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            
        ];
    }
    

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new ResUsers();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->code = $this->code;
            $user->firstname = $this->firstname;
            $user->lastname = $this->lastname;
            $user->active = $this->active;
            $user->setPassword($this->password);
            
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
