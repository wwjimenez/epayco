<?php

namespace backend\controllers;
use Yii;

class ClientsoapController extends \yii\web\Controller
{
    /*public function actionIndex()
    {
        $client = new \mongosoft\soapclient\Client([
    	'url' => 'http://127.0.0.1/epayco/backend/web/index.php?r=apisoap/consultasaldo',
		]);
        //$client = Yii::$app->siteApi;
		$resultado= $client->getConsultasaldo(1);
		print_r($resultado);
    }*/

    public function actionIndex()
    {
        $client = new \mongosoft\soapclient\Client([
    	'url' => 'http://127.0.0.1/epayco/backend/web/index.php?r=apisoap/registro',
		]);
        //$client = Yii::$app->siteApi;
		$resultado= $client->getRegistro(17389814, 'walter wilfredo jimenez jaimes', 'walter86.79@gmail.com', '041413456787');
		print_r($resultado);
    }

}
