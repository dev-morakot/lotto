<?php

namespace app\modules\resource\controllers;

use Yii;
use app\modules\resource\models\ResUsers;
use app\modules\resource\models\ResUsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\resource\models\SignupForm;

/**
 * ResUsersController implements the CRUD actions for ResUsers model.
 */
class ResUsersController extends Controller
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
     * Lists all ResUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ResUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ResUsers model.
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
     * Creates a new ResUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ResUsers();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                return $this->redirect(['index']);
            }
           
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ResUsers model.
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
     * Deletes an existing ResUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionUserListJson(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $users = ResUsers::find()->all();
        return $users;
    }
    
    /**
     * จัดการผู้ใช้ระบบ
     * @return type
     */
    public function actionManage(){
        return $this->render('manage');
    }
    
    public function actionResUserListJson($q=null){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = ResUsers::find();
        if($q){
            $query->where(['like','username',$q]);
        }
                $users = $query->all();
        return $users;
    }
    
    public function actionCenterUserListJson($q=null){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $res_ids = ResUsers::find()->select('id')->all();
        $query = \app\models\User::find();
        if($q){
            $query->where(['like','username',$q]);
            
        }
        $query->andWhere(['not in','id',$res_ids]);
                $users = $query->all();
        return $users;
    }
    
    public function actionAddToResUser($id){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = \app\models\User::findOne(['id'=>$id]);
        $resUser = new ResUsers();
        $resUser->attributes = $model->attributes;
        $resUser->id = $model->id;
        
        $result = $resUser->save();
        if($result){
            return ['status'=>'success','message'=>'เรียบร้อย'];
        } else {
            return ['status'=>'fail','message'=>$resUser->getErrors()];
        }
    }
    

    /**
     * Finds the ResUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ResUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ResUsers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
