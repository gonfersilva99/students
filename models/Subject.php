<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subject".
 *
 * @property int $id
 * @property string $name
 * @property int $year
 * @property int $semester
 * @property int $program_id
 *
 * @property Program $program
 * @property StudentSubject[] $studentSubjects
 * @property Student[] $students
 */
class Subject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'year', 'semester', 'program_id'], 'required'],
            [['year', 'semester', 'program_id'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['name', 'program_id'], 'unique', 'targetAttribute' => ['name', 'program_id']],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::className(), 'targetAttribute' => ['program_id' => 'id']],
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
            'year' => 'Year',
            'semester' => 'Semester',
            'program_id' => 'Program',
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
        return $this->hasMany(StudentSubject::className(), ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[Students]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudents()
    {
        return $this->hasMany(Student::className(), ['id' => 'student_id'])->viaTable('student_subject', ['subject_id' => 'id']);
    }

    public function subscribe($user_id)
    {
        $student_subject = null;
        $student = Student::findByUserOrNew($user_id);
        if ($student->id) {
            $student_subject = new StudentSubject();
            $student_subject->subject_id = $this->id;
            $student_subject->student_id = $student->id;
            $student_subject->save();
        }
        return ($student_subject);
    }

    public function unsubscribe($user_id)
    {
        $res = false;
        $student = Student::findByUserOrNew($user_id);
        if ($student->id) {
            StudentSubject::deleteAll(['student_id' => $student->id, 'subject_id' => $this->id]);
            $res=true;
        }
        return ($res);
    }

    public function isSubscribed($user_id){
        return $this->getStudents()->where(['user_id' => $user_id])->one();
    }


}
