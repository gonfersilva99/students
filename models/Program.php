<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "program".
 *
 * @property int $id
 * @property string $name
 * @property string $degree
 * @property string|null $image
 *
 * @property Student[] $students
 * @property Subject[] $subjects
 */
class Program extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'program';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'degree'], 'required'],
            [['image'], 'string'],
            [['name', 'degree'], 'string', 'max' => 45],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'degree' => 'Degree',
            'image' => 'Image',
        ];
    }

    /**
     * Gets query for [[Students]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudents()
    {
        return $this->hasMany(Student::className(), ['program_id' => 'id']);
    }

    /**
     * Gets query for [[Subjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(Subject::className(), ['program_id' => 'id']);
    }

    public static function getAllAsArray(){

        // The solution below looks more interesting

        // $query=Program::find()
        //     ->orderBy([
        //         'name' => SORT_ASC,
        //     ]);
        // $items = $query->asArray()->all();
        // $data=ArrayHelper::map($items, 'id', 'name');
        // return($data);


        $programs = Program::find()
            ->select(['name'])
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
        return $programs;
    }
}
