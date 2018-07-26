<?php

namespace backend\modules\rest\controllers;

use yii\web\Controller;

/**
 * Default controller for the `rest` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionRegistro($documento, $nombres, $email, $celular)
    {
        return ("llega");
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $client = new Client(['baseUrl' => 'http://127.0.0.1/epayco/backend/web/index.php?r=apisoap/registro']);
        $response = $client->createRequest()->setUrl($documento, $nombres, $email, $celular)->addHeaders(['content-type' => 'application/xml'])->send();
        $persona = Json::decode($response->content);

        
        if($model!=null)
        {
        	return ['status' => true, 'data' => $model];
        }
        else
        {
        	return ['status' => false, 'data' => 'not data'];
        }
    }
}
