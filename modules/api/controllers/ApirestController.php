<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;

class ApirestController extends ActiveController
{
    public function actionIndex()
    {
        return "hola";
    }

}
