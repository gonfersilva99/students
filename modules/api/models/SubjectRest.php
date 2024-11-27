<?php


namespace app\modules\api\models;


use app\models\Search\SubjectSearch;
use app\models\Subject;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

class SubjectRest extends Subject implements Linkable
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
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Subject::find();

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
            'year' => $this->year,
            'semester' => $this->semester,
            'program_id' => $this->program_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function searchSubscribed($params)
    {
        $query = Subject::find();

        $query->joinWith("studentSubjects.student");
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

        $query->andWhere(['user_id' => $this->user_id]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'year' => $this->year,
            'semester' => $this->semester,
            'subject.program_id' => $this->program_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }


    public function searchSpecial($params)
    {
        $query = SubjectRest::find();


        // add conditions that should always apply here
        $query->innerJoinWith("program");

//        $query = (new Query())
//            ->from('subject')
//            ->innerJoin('program', 'Subject.program_id=Program.id')
//            ->select(['Subject.id', 'Subject.name', 'Program.degree']);

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
            'year' => $this->year,
            'semester' => $this->semester,
            'program_id' => $this->program_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }


    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['/subject', 'id' => $this->id], true),
            'edit' => Url::to(['/subject/edit', 'id' => $this->id], true),
            'program' => Url::to(['/program', 'id' => $this->program_id], true),
            'index' => Url::to(['/subject'], true),
        ];
    }


}
