<?php

namespace app\services;

use Yii;
use Exception;
use app\models\User;

class UserService
{
    public function create(string $username, string $email, string $password): User
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user = $this->createUserInstance($username, $email, $password);

            if (!$user->save()) {
                throw new Exception('Failed to save user: ' . json_encode($user->errors));
            }

            $transaction->commit();
            return $user;

        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error("Error creating user '{$username}': " . $e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function generatePasswordResetToken(User $user): bool
    {
        return $this->updateUserAttribute($user, 'password_reset_token', Yii::$app->security->generateRandomString() . '_' . time());
    }

    public function removePasswordResetToken(User $user): bool
    {
        return $this->updateUserAttribute($user, 'password_reset_token', null);
    }

    public function softDelete(User $user): bool
    {
        if ($user->deleted_at !== null) {
            Yii::warning("Attempted to soft delete an already deleted record.");
            return false;
        }

        return $this->updateUserAttribute($user, 'deleted_at', time());
    }

    public function restoreSoftDelete(User $user): bool
    {
        if ($user->deleted_at === null) {
            Yii::warning("Attempted to restore a record that is not deleted.");
            return false;
        }

        return $this->updateUserAttribute($user, 'deleted_at', null);
    }

    private function createUserInstance(string $username, string $email, string $password): User
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;

        return $user;
    }

    private function updateUserAttribute(User $user, string $attribute, $value): bool
    {
        $user->$attribute = $value;
        try {
            return $user->save(false, [$attribute]);
        } catch (Exception $e) {
            Yii::error("Error updating {$attribute} for user ID {$user->id}: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
