<?php

namespace app\models;

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
 */
class Sector extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sectors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
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
     * @return \yii\db\ActiveQuery
     */
    public function getIndustries()
    {
        return $this->hasMany(Industry::class, ['sector_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
