<?php

namespace backend\controllers;
use backend\models\Billetera;
use backend\models\Clientes;
use backend\models\Pagos;
use Yii;
class ApisoapController extends \yii\web\Controller
{
    /*public function actionIndex()
    {
        return $this->render('index');
    }*/
    /**
     * @inheritdoc
     */
    public function actions()
    {
        //metodos del servicio soap
        return [
            'consultasaldo' => 'mongosoft\soapserver\Action',
            'registro' => 'mongosoft\soapserver\Action',
            'recarga' => 'mongosoft\soapserver\Action',
            'compra' => 'mongosoft\soapserver\Action',
            'confirmarcompra' => 'mongosoft\soapserver\Action',
        ];
    }

    /**
     * @param int $documento
     * @return array
     * @soap
     */
    public function getConsultasaldo($documento)
    {
        $model = Billetera::find()->where(['documento' => $documento])->One();
        if($model==null)
        {
        	return ['success' => 'false', 'cod_error' => '01', 'message_error' => 'Cliente no encontrado', 'resultado' =>'',];
        }
        else
        {
        	return ['success' => 'true', 'cod_error' => '00', 'message_error' => '', 'resultado' => $model->valor];	
        }
	}

	/**
     * @param int $documento
     * @param string $nombres
     * @param string $email
     * @param string $celular
     * @return array
     * @soap
     */
    public function getRegistro($documento, $nombres, $email, $celular)
    {
        $model = new Clientes();
        $model->documentos=$documento;
        $model->nombres=$nombres;
        $model->email=$email;
        $model->celular=$celular;
        if($model->salvar())
        {
        	return ['success' => 'true', 'cod_error' => '00', 'message_error' => '', 'resultado' => 'Registro Exitoso'];		
        }
        else
        {
        	$mensaje="";
        	$array=$model->getErrors();

        	if($array!=null)
        	{
        		foreach ($array as $key => $value) 
        		{
        			
        			$mensaje.= $value[0];
        		}
        	}
        	
        	return ['success' => 'false', 'cod_error' => '02', 'message_error' => $mensaje, [],];
        }
	}

	/**
     * @param int $documento
     * @param string $celular
     * @param double $valor
     * @return array
     * @soap
     */
    public function getRecarga($documento, $celular, $valor)
    {
        $buscar=Clientes::find()->where(['documentos' => $documento])->andWhere(['celular' => $celular])->one();
        if($buscar!=null)
        {
	        $model =Billetera::find()->where(['documento' => $documento])->One();
	        if($model==null)
            {
                $model =new Billetera();
                $model->documento=$documento;
    	        $model->valor=$valor;
    	        if(!$model->save())
    	        {
    	        	$mensaje="";
    	        	$array=$model->getErrors();

    	        	if($array!=null)
    	        	{
    	        		foreach ($array as $key => $value) 
    	        		{
    	        			
    	        			$mensaje.= $value[0];
    	        		}
    	        	}
    	        	return ['success' => 'false', 'cod_error' => '02', 'message_error' => $mensaje, [],];
    	        }
    	        else
    	        {
    	        	return ['success' => 'true', 'cod_error' => '00', 'message_error' => '', 'resultado' => $model->valor];	
    	        }
            }
            else
            {
                $model->documento=$documento;
                $model->valor+=$valor;
                if(!$model->save())
                {
                    $array=$model->getErrors();

                    if($array!=null)
                    {
                        foreach ($array as $key => $value) 
                        {
                            
                            $mensaje.= $value[0];
                        }
                    }
                    return ['success' => 'false', 'cod_error' => '02', 'message_error' => $mensaje, [],];
                }
                else
                {
                    return ['success' => 'true', 'cod_error' => '00', 'message_error' => '', 'resultado' => $model->valor]; 
                }   
            }
    	}
    	else
    	{
    		return ['success' => 'false', 'cod_error' => '01', 'message_error' => 'Cliente no encontrado', [],];
    	}
	}


	/**
     * @param int $documento
     * @param String $item
     * @param double $valor
     * @param String $sesion
     * @return array
     * @soap
     */
    public function getCompra($documento, $item, $valor)
    {
        $pago=new Pagos();
        $mensaje="";
        if($pago->pagoprevio($documento, $item, $valor))
        {
            return ['success' => 'true', 'cod_error' => '00', 'message_error' => $mensaje, [],];
        }
        else
        {
            $array=$pago->getErrors();

            if($array!=null)
            {
                foreach ($array as $key => $value) 
                {
                    
                    $mensaje.= $value[0];
                }
            }
            return ['success' => 'false', 'cod_error' => '02', 'message_error' => $mensaje, [],];
        }
	}

    /**
     * @param String $token
     * @param String $sesion
     * @return array
     * @soap
     */
    public function getConfirmarcompra($token, $sesion)
    {
        $pago=new Pagos();
        $mensaje="";
        $array=$pago->confirmapago($token, $sesion);
        if($array['success'] == 'true')
        {
            return ['success' => 'true', 'cod_error' => '00', 'message_error' => $mensaje, [],];
        }
        else
        {
            return ['success' => 'false', 'cod_error' => $array['cod_error'], 'message_error' => $array['message_error'], [],];
        }
    }
}
