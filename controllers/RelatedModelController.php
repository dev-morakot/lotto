<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\resource\models\SignupForm;
use app\models\PasswordResetRequestForm;
/**
 * UserController implements the CRUD actions for User model.
 */
class RelatedModelController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionLoadModels($table,$q="")
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = new \yii\db\Query();
        $query->select('id,name')->from($table)
                ->where(['like','name',$q])
                ->limit(30);
        $result = $query->all();
        return $result;
    }
}
