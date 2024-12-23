<?php

namespace app\modules\config\models;

use app\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sectors".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 *
 * @property Industry[] $industries
 * @property User $user
 * @property int $industriesCount Read-only computed attribute for the count of industries.
 */
class Sector extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'sectors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'name'], 'required'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Industries]].
     *
     * @return ActiveQuery
     */
    public function getIndustries(): ActiveQuery
    {
        return $this->hasMany(Industry::class, ['sector_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Read-only computed attribute for the count of industries.
     *
     * @return int
     */
    public function getIndustriesCount(): int
    {
        return $this->getIndustries()->count();
    }
}
