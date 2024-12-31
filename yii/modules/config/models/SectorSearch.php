<?php
/** @noinspection PhpUnused */

namespace app\modules\config\models;

use InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SectorSearch represents the model behind the search form of `app\models\Sector`.
 */
class SectorSearch extends Sector
{

    public int $industries_count = 0;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['name'], 'safe'],
            [['industries_count'], 'integer'],
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

    public function search(array $params, int $userId): ActiveDataProvider
    {
        if (!$userId) {
            throw new InvalidArgumentException('User ID must be provided for SectorSearch.');
        }

        $query = Sector::find()
            ->select([
                'sectors.*',
                'industries_count' => '(SELECT COUNT(*) FROM industries WHERE industries.sector_id = sectors.id)',
            ])
            ->where(['user_id' => $userId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
                'attributes' => [
                    'name',
                    'industries_count' => [
                        'asc' => ['industries_count' => SORT_ASC],
                        'desc' => ['industries_count' => SORT_DESC],
                    ],
                ],
            ],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

}
