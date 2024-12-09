<?php

namespace app\commands;

use Yii;
use yii\console\ExitCode;
use yii\console\Controller;
use app\services\UserService;

class UserController extends Controller
{

    protected $userService;

    public function __construct($id, $module, UserService $userService, $config = [])
    {
        $this->userService = $userService;
        parent::__construct($id, $module, $config);
    }

    public function actionCreate(string $username, string $email, string $password): int
    {
        try {
            $user = $this->userService->create($username, $email, $password);

            if ($user->hasErrors()) {
                echo "Failed to create user '{$username}'.\n";
                foreach ($user->errors as $attribute => $errors) {
                    echo ucfirst($attribute) . ': ' . implode(", ", $errors) . "\n";
                }
                return ExitCode::UNSPECIFIED_ERROR;
            }

            echo "User '{$username}' has been created successfully.\n";
            return ExitCode::OK;
        } catch (\Throwable $e) {
            Yii::error("An error occurred while creating user '{$username}': " . $e->getMessage(), __METHOD__);
            echo "An unexpected error occurred: {$e->getMessage()}\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }


}
