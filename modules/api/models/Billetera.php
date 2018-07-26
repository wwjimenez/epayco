<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "billetera".
 *
 * @property int $id
 * @property int $documento
 * @property string $valor
 *
 * @property Clientes $documento0
 */
class Billetera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'billetera';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['documento', 'valor'], 'required'],
            [['documento'], 'integer'],
            [['valor'], 'number'],
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
            'documento' => 'Documento',
            'valor' => 'Valor',
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
