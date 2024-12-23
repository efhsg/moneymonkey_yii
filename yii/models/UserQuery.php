<?php

namespace app\models;

use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{

    public function withoutDeleted(): self
    {
        return $this->andWhere(['deleted_at' => null]);
    }

    public function active(): self
    {
        return $this->withoutDeleted()->andWhere(['status' => User::STATUS_ACTIVE]);
    }

    public function byUsername(string $username): self
    {
        return $this->andWhere(['username' => $username]);
    }

    public function byPasswordResetToken(string $token): self
    {
        return $this->andWhere(['password_reset_token' => $token]);
    }
}
