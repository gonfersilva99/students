<?php

namespace app\models;

use amnah\yii2\user\models\Role;
use amnah\yii2\user\models\User;
use Yii;

/**
 * This is the model class for table "student".
 *
 * @property int $id
 * @property string $name
 * @property int $program_id
 * @property int $user_id
 *
 * @property Program $program
 * @property StudentSubject[] $studentSubjects
 * @property Subject[] $subjects
 * @property User $user
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'program_id', 'user_id'], 'required'],
            [['program_id', 'user_id'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['name'], 'unique'],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::className(), 'targetAttribute' => ['program_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'program_id' => 'Program ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Program]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['id' => 'program_id']);
    }

    /**
     * Gets query for [[StudentSubjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentSubjects()
    {
        return $this->hasMany(StudentSubject::className(), ['student_id' => 'id']);
    }

    /**
     * Gets query for [[Subjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(Subject::className(), ['id' => 'subject_id'])->viaTable('student_subject', ['student_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function findByUserOrNew($user_id){
        $model = self::find()->joinWith('user')->andWhere(['user.id' => $user_id])->one();
        if (!$model) {
            $model = new Student();
            $model->user_id = $user_id;
        }
        return $model;
    }

    public function enroll(){
        $res = false;
        $transaction = Yii::$app->db->beginTransaction();
        if($this->save()) {
            $student_role = Role::findOne(['name' => 'Student']);
            $user = User::findOne($this->user_id);
            if($student_role && $user) {
                $user->role_id = $student_role->id;
                $res = $user->save();
                $transaction->commit();

            }else{
                $transaction->rollBack();
            }
        }else{
            $transaction->rollBack();
        }
        return($res);
    }
}
