<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pagos".
 *
 * @property int $id
 * @property string $idsesion
 * @property string $token
 * @property string $valor
 * @property bool $estatus
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

    /**
    * realiza el pago previo donde se envia el token al correo
    * @return bool
    */
    public function Pagoprevio($documento, $item, $valor)
    {
        $token=$this->obtenToken($item);
        $this->documento = $documento;
        $this->valor = $valor;
        $this->estatus = 0;
        $this->idsesion = $item;
        $this->token = $token;
        if($this->save())
        {
            
            //enviando el email
            if(Yii::$app->mailer->compose()
            ->setFrom('walter86_79@hotmail.com')
            ->setTo($this->documento0->email)
            ->setSubject('Confirme el pago')
            ->setTextBody($this->token)
            ->setHtmlBody('<b>Confirme el pago con el token:</b>'.$this->token)
            ->send())
            return true;
            else
            false;
        }
        else
        {
            return false;
        }

    }
    /**
    * Realiza la compra descontando de la billetera
    * @return array
    */
    public function ConfirmaPago($token, $sesion)
    {
        $buscar=Pagos::find()->where(['token' => $token])->andWhere(['idsesion' => $sesion])->andwhere(['estatus' =>0])->One();
        
        if($buscar!=null)
        {
            //descontando de la billetera
            $buscar->estatus=1;
            if($buscar->save())
            {
                $billetera= Billetera::find()->where(['documento' => $buscar->documento])->one();
                if($billetera!=null)
                {
                    if($billetera->valor<$buscar->valor)
                    {
                        return ['success' => 'false', 'cod_error' => '04', 'message_error' => 'No posee suficiente saldo', [],];
                    }
                    else
                    {
                        $billetera->valor-=$buscar->valor;
                        if($billetera->save())
                        {
                            return ['success' => 'true', 'cod_error' => '00', 'message_error' => '', [],];
                        }
                        else
                        {
                            $array=$billetera->getErrors();
                            if($array!=null)
                            {
                                foreach ($array as $key => $value) 
                                {
                                    
                                    $mensaje.= $value[0];
                                }
                            }
                            return ['success' => 'false', 'cod_error' => '04', 'message_error' => $mensaje, [],];
                        }
                    }
                }
                else
                {
                    return ['success' => 'false', 'cod_error' => '02', 'message_error' => "Cliente y token no encontrado", [],];
                }
            }
            else
            {
                $array=$buscar->getErrors();
                if($array!=null)
                {
                    foreach ($array as $key => $value) 
                    {
                        
                        $mensaje.= $value[0];
                    }
                }
                return ['success' => 'false', 'cod_error' => '04', 'message_error' => $mensaje, [],];
            }
        }
        else
        {
            return ['success' => 'false', 'cod_error' => '02', 'message_error' => "Cliente no encontrado", [],];
        }

    }

    /**
    * @param array $arreglo
    * @return String
    */
    function obtenCaracterAleatorio($arreglo) 
    {
        $clave_aleatoria = array_rand($arreglo, 1); //obtén clave aleatoria
        return $arreglo[ $clave_aleatoria ];    //devolver ítem aleatorio
    }
    
    /**
    * @param string $car
    * @return String
    */
    function obtenCaracterMd5($car) 
    {
        $md5Car = md5($car.Time()); //Codificar el carácter y el tiempo POSIX (timestamp) en md5
        $arrCar = str_split(strtoupper($md5Car));   //Convertir a array el md5
        $carToken = $this->obtenCaracterAleatorio($arrCar);    //obtén un ítem aleatoriamente
        return $carToken;
    }
 
    /**
    * @param string $item
    * @return String
    */
    function obtenToken($item) 
    {
        //crear alfabeto
        $mayus = "ABCDEFGHIJKMNPQRSTUVWXYZ";
        $mayusculas = str_split($mayus);    //Convertir a array
        $item = str_split($item);    //Convertir a array
        //crear array de numeros 0-9
        $numeros = range(0,9);
        //revolver arrays
        shuffle($mayusculas);
        shuffle($numeros);
        shuffle($item);
        //Unir arrays
        $arregloTotal = array_merge($mayusculas,$numeros,$item );
        $newToken = "";
        
        for($i=0;$i<6;$i++) {
                $miCar = $this->obtenCaracterAleatorio($arregloTotal);
                $newToken .= $this->obtenCaracterMd5($miCar);
        }
        return $newToken;
    }


}
