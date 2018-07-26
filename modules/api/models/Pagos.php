<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "pagos".
 *
 * @property int $id
 * @property string $idsesion
 * @property string $token
 * @property string $valor
 * @property int $documento
 *
 * @property Clientes $documento0
 */
class Pagos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pagos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idsesion', 'token', 'valor', 'documento'], 'required'],
            [['valor'], 'number'],
            [['documento'], 'integer'],
            [['idsesion', 'token'], 'string', 'max' => 30],
            [['documento'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['documento' => 'documentos']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idsesion' => 'Idsesion',
            'token' => 'Token',
            'valor' => 'Valor',
            'documento' => 'Documento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumento0()
    {
        return $this->hasOne(Clientes::className(), ['documentos' => 'documento']);
    }
}
