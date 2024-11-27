<?php


namespace app\modules\api\models;


use app\models\Program;
use app\models\Search\ProgramSearch;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ProgramRest extends Program
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'degree'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    // public function scenarios()
    // {
    //     // bypass scenarios() implementation in the parent class
    //     return Model::scenarios();
    // }


    public function search($params)
    {
        $query = ProgramRest::find();


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'degree', $this->degree]);

        return $dataProvider;
    }

    public function fields()
    {
        return [
            // field name is the same as the attribute name
            'id',
            // field name is "email", the corresponding attribute name is "email_address"
            'name',
            // field name is "name", its value is defined by a PHP callback
            'image' => function ($model) {
                return base64_encode($model->image);
            },
        ];
    }
}
