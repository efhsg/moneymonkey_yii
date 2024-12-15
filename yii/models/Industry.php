<?php

namespace app\models;

use yii\db\{
    ActiveQuery,
    ActiveRecord
};

/**
 * This is the model class for table "industries".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $sector_id
 *
 * @property Sector $sector
 * @property Stock[] $stocks
 * @property User $user
 */
class Industry extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'industries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'sector_id'], 'required'],
            [['user_id', 'sector_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['sector_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sector::class, 'targetAttribute' => ['sector_id' => 'id']],
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
            'sector_id' => 'Sector ID',
        ];
    }

    /**
     * Gets query for [[Sector]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSector(): ActiveQuery
    {
        return $this->hasOne(Sector::class, ['id' => 'sector_id']);
    }

    /**
     * Gets query for [[Stock]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStocks(): ActiveQuery
    {
        return $this->hasMany(Stock::class, ['industry_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
