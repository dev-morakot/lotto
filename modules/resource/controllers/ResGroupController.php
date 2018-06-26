<?php

namespace app\modules\resource\controllers;

use Yii;
use app\modules\resource\models\ResGroup;
use app\modules\resource\models\ResGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
/**
 * ResGroupController implements the CRUD actions for ResGroup model.
 */
class ResGroupController extends Controller
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

    /**
     * Lists all ResGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ResGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ResGroup model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ResGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ResGroup();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ResGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ResGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionListGroupUsers($group_id){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = $this->findModel($group_id);
        return $model->resUsers;
    }
    
    public function actionAddGroupUser(){
        $postdata = Yii::$app->request->rawBody;
        $data = Json::decode($postdata);
        $user_id = $data['user_id'];
        $group_id = $data['group_id'];
        $group = $this->findModel($group_id);
        $user = \app\modules\resource\models\ResUsers::findOne(['id'=>$user_id]);
        $group->link('resUsers', $user);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $user;
    }
    
    public function actionDeleteGroupUser()
    {
        $postdata = Yii::$app->request->rawBody;
        $data = Json::decode($postdata);
        $user_id = $data['user_id'];
        $group_id = $data['group_id'];
        $user = \app\modules\resource\models\ResUsers::findOne(['id'=>$user_id]);
        $group = $this->findModel($group_id);
        $group->unlink('resUsers', $user,true);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $user;
    }

    /**
     * Finds the ResGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ResGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ResGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
