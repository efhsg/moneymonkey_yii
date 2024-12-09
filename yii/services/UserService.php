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
            $user = new User();
            $user->username = $username;
            $user->email = $email;
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->status = User::STATUS_ACTIVE;

            if ($user->save()) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }

            return $user;
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error("Error creating user '{$username}': " . $e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function generatePasswordResetToken(User $user): bool
    {
        $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        return $user->save(false);
    }

    public function removePasswordResetToken(User $user): bool
    {
        $user->password_reset_token = null;
        return $user->save(false);
    }

    public function softDelete(User $user): bool
    {
        if ($user->deleted_at !== null) {
            Yii::warning("Attempted to soft delete an already deleted record.");
            return false;
        }

        $user->deleted_at = time();

        try {
            return $user->save(false);
        } catch (Exception $e) {
            Yii::error("Error during soft delete: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    public function restoreSoftDelete(User $user): bool
    {
        if ($user->deleted_at === null) {
            Yii::warning("Attempted to restore a record that is not deleted.");
            return false;
        }

        $user->deleted_at = null;

        try {
            return $user->save(false, ['deleted_at']);
        } catch (Exception $e) {
            Yii::error("Error restoring record ID {$user->id}: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
