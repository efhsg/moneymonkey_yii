<?php

namespace app\modules\config\models;

use InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * IndustrySearch represents the model behind the search form of `app\modules\config\models\Industry`.
 */
class IndustrySearch extends Industry
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'sector_id'], 'integer'],
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

    public function search(array $params, ?int $userId): ActiveDataProvider
    {
        if (!$userId) {
            throw new InvalidArgumentException('User ID must be provided for IndustrySearch.');
        }

        $query = Industry::find()
            ->joinWith('sector s')
            ->andWhere(['s.user_id' => $userId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // Uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Apply grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sector_id' => $this->sector_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

}
