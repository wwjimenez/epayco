<?php

namespace backend\modules\rest\controllers;
use Yii;
use \yii\web\Response;
use yii\httpclient\Client;
use yii\helpers\Json;

class RestController extends \yii\web\Controller
{
	public $enableCsrfValidation=false;
    
    
    /**
     * @param int $documento
     * @return Json
     */
    public function actionConsultasaldo()
    {
        $request = Yii::$app->request;
        //verificamos que se llegue de forma correcta.
        if($request->isPost && ($request->post('documento')!==null)) 
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //url del soap
            $client = new \mongosoft\soapclient\Client(
                [
                    'url' => 'http://127.0.0.1/epayco/backend/web/index.php?r=apisoap/consultasaldo',
                ]);
            //guardando los parametros para consumir el soap
            $documento=$request->post('documento');
            $resultado= $client->getConsultasaldo($documento);
            return $resultado;
        }
        else
        {
            return ['success' => 'false', 'cod_error' => '03', 'message_error' => 'Faltan Parametros', 'resultado' => ''];
        }
    }

    /**
     * @param int $documento
     * @param string $nombres
     * @param string $email
     * @param double $celular
     * @return Json
     */
    public function actionRegistro()
    {
        $request = Yii::$app->request;
        //verificamos que se llegue de forma correcta.
        if($request->isPost && ($request->post('documento')!==null &&  $request->post('nombres')!==null && $request->post('email')!==null && $request->post('celular')!==null)) 
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //url del soap
            $client = new \mongosoft\soapclient\Client(
                [
                    'url' => 'http://127.0.0.1/epayco/backend/web/index.php?r=apisoap/registro',
                ]);
            //guardando los parametros para consumir el soap
            $documento=$request->post('documento');
            $nombres=$request->post('nombres');
            $email=$request->post('email');
            $celular=$request->post('celular');
            $resultado= $client->getRegistro($documento, $nombres, $email, $celular);
            return $resultado;
        }
        else
        {
            return ['success' => 'false', 'cod_error' => '03', 'message_error' => 'Faltan Parametros', 'resultado' => ''];
        }
    }

    /**
     * @param int $documento
     * @param string $celular
     * @param double $valor
     * @return Json
     */
    public function actionRecarga()
    {
        $request = Yii::$app->request;
        //verificamos que se llegue de forma correcta.
        if($request->isPost && ($request->post('documento')!==null &&  $request->post('celular')!==null && $request->post('valor')!==null)) 
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //url del soap
            $client = new \mongosoft\soapclient\Client(
                [
                    'url' => 'http://127.0.0.1/epayco/backend/web/index.php?r=apisoap/recarga',
                ]);
            //guardando los parametros para consumir el soap
            $documento=$request->post('documento');
            $celular=$request->post('celular');
            $valor=$request->post('valor');
            $resultado= $client->getRecarga($documento, $celular, $valor);
            return $resultado;
        }
        else
        {
            return ['success' => 'false', 'cod_error' => '03', 'message_error' => 'Faltan Parametros', 'resultado' => ''];
        }
    }

    /**
     * @param int $documento
     * @param string $item
     * @param string $valor
     * @return Json
     */
    public function actionCompra()
    {
       
        $request = Yii::$app->request;
        $sesion="";
        //verificamos que se llegue de forma correcta.
        if($request->isPost && ($request->post('documento')!==null &&  $request->post('item')!==null && $request->post('valor')!==null  )) 
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //url del soap
            $client = new \mongosoft\soapclient\Client(
                [
                    'url' => 'http://127.0.0.1/epayco/backend/web/index.php?r=apisoap/compra',
                ]);
            //guardando los parametros para consumir el soap
            $documento=$request->post('documento');
            $item=Yii::$app->session->id;
            $valor=$request->post('valor');
            
            $resultado= $client->getCompra($documento, $item, $valor);
            return $resultado;
        }
        else
        {
            return ['success' => 'false', 'cod_error' => '03', 'message_error' => 'Faltan Parametros', 'resultado' => ''];
        }
    }


    /**
     * @param string $token
     * @return Json
     */
    public function actionConfirmarcompra()
    {
       
        $request = Yii::$app->request;
        //verificamos que se llegue de forma correcta.
        if($request->isPost && ($request->post('token')!==null)) 
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //url del soap
            $client = new \mongosoft\soapclient\Client(
                [
                    'url' => 'http://127.0.0.1/epayco/backend/web/index.php?r=apisoap/confirmarcompra',
                ]);
            //guardando los parametros para consumir el soap
            $token=$request->post('token');
            $item=Yii::$app->session->id;
            
            
            $resultado= $client->getConfirmarcompra($token, $item);
            return $resultado;
        }
        else
        {
            return ['success' => 'false', 'cod_error' => '03', 'message_error' => 'Faltan Parametros', 'resultado' => ''];
        }
    }

    
}