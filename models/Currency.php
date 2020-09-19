<?php


namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class Currency extends ActiveRecord
{
    public static function tableName()
    {
        return '{{currency}}';
    }

    public function rules()
    {
        return [
            [['name', 'code', 'rate', 'nominal'], 'required'],
            ['rate', 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            ['nominal', 'integer', 'min' => 1],
            [['code', 'name'], 'string', 'min' => 1]
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

//    /**
//     * если условие состоит в том,
//     * что обновлять дату нужно каждый раз при запуске консольной команды,
//     * то нужно использовать этот метод
//     */
//    public function beforeSave($insert)
//    {
//        if (!parent::beforeSave($insert)) {
//            return false;
//        }
//
//        $this->updated_at = date('Y-m-d H:i:s');
//
//        return true;
//    }
}