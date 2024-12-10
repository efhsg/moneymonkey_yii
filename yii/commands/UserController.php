<?php

namespace app\commands;

use Yii;
use yii\console\ExitCode;
use yii\console\Controller;
use app\services\UserService;
use app\exceptions\UserCreationException;

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
            $this->userService->create($username, $email, $password);
            $this->stdout("User '{$username}' has been created successfully.\n");
            return ExitCode::OK;
        } catch (UserCreationException $e) {
            $this->stdout("Failed to create user '{$username}': {$e->getMessage()}\n");
            return ExitCode::UNSPECIFIED_ERROR;
        } catch (\Throwable $e) {
            Yii::error("An unexpected error occurred: " . $e->getMessage(), __METHOD__);
            $this->stdout("An unexpected error occurred: {$e->getMessage()}\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }


}
