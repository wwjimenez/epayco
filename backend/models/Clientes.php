<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "clientes".
 *
 * @property int $id
 * @property int $documentos
 * @property string $nombres
 * @property string $email
 * @property string $celular
 *
 * @property Billetera[] $billeteras
 * @property Pagos[] $pagos
 */
class Clientes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['documentos', 'nombres', 'email', 'celular'], 'required'],
            [['documentos'], 'integer'],
            [['nombres'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['celular'], 'string', 'max' => 20],
            [['documentos'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'documentos' => 'Documentos',
            'nombres' => 'Nombres',
            'email' => 'Email',
            'celular' => 'Celular',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBilleteras()
    {
        return $this->hasMany(Billetera::className(), ['documento' => 'documentos']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagos()
    {
        return $this->hasMany(Pagos::className(), ['documento' => 'documentos']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function Salvar()
    {
        if($this->save())
        {
            $billetera= new Billetera();
            $billetera->documento=$this->documentos;
            $billetera->valor=0;
            $billetera->save();
            return true;
        }
        else
        {
            return false;
        }
    }



}
