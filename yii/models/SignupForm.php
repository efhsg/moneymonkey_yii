<?php

namespace app\models;

use yii\base\Model;
use app\services\UserService;
use app\exceptions\UserCreationException;

/**
 * SignupForm handles user registration.
 */
class SignupForm extends Model
{
    public string $username = '';
    public string $email = '';
    public string $password = '';

    private UserService $userService;

    public function __construct(UserService $userService, $config = [])
    {
        $this->userService = $userService;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['password'], 'string', 'min' => 3, 'max' => 255],
        ];
    }

    public function signup(): ?User
    {
        if ($this->validate()) {
            $user = new User([
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
            ]);

            if ($user->validate()) {
                try {
                    return $this->userService->create($this->username, $this->email, $this->password);
                } catch (UserCreationException $e) {
                    $this->addError('username', $e->getMessage());
                }
            } else {
                $this->addErrors($user->getErrors());
            }
        }

        return null;
    }
}
