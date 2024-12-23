<?php

namespace app\modules\config\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SectorSearch represents the model behind the search form of `app\models\Sector`.
 */
class SectorSearch extends Sector
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $userId): ActiveDataProvider
    {
        if (!$userId) {
            throw new \InvalidArgumentException('User ID must be provided for SectorSearch.');
        }

        $query = Sector::find()->where(['user_id' => $userId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
            ],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

}
