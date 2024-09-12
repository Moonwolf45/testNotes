<?php

namespace app\models\form;

use app\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * RegisterForm is the model behind the register form.
 *
 * @property string $email
 * @property string $password
 * @property string $password_confirm
 */
class RegisterForm extends Model
{
    public $email;
    public $password;
    public $password_confirm;

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            [['email', 'password', 'password_confirm'], 'required'],
            ['email', 'email'],
            [['email', 'password', 'password_confirm'], 'trim'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false,
                'message' => 'Пароли не совпадают']
        ];
    }

    /**
     * @return bool
     *
     * @throws Exception
     */
    public function register(): bool
    {
        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generatePasswordResetToken();

        if ($user->save()) {
            Yii::$app->user->login($user, 3600*24*30);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'password_confirm' => 'Подтвердите пароль'
        ];
    }
}