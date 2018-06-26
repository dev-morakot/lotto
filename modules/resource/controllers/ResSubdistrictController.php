<?php 

namespace app\modules\resource\controllers;

use Yii;
use app\modules\resource\models\ResSubdistrict;
use app\modules\resource\models\ResSubdistrictSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;


class ResSubdistrictController extends Controller {

	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex(){

    	 $searchModel = new ResSubdistrictSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {

    	$request = Yii::$app->request;
    	if($request->isAjax) {
    		Yii::$app->response->format = Response::FORMAT_JSON;
    		return [
    			'title' => 'ตำบล/แขวง #'. $id,
    			'content' => $this->renderAjax('view', [
    				'model' => $this->findModel($id),
    			]),
    			 'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
    		];
    	} else {
    		return $this->render('view', [
    			'model' => $this->findModel($id),
    		]);
    	}
    }

    public function actionCreate(){

    	$request = Yii::$app->request;
    	$model = new ResSubdistrict();

    	if($request->isAjax) {

    		Yii::$app->response->format = Response::FORMAT_JSON;
    		if($request->isGet) {
    			return [
    				'title'=> "สร้าง ตำบล/แขวง",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
    			];
    		} else if($model->load($request->post()) && $model->save()) {
    			return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new ResDistrict",
                    'content'=>'<span class="text-success">Create ResDistrict success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];       
    		} else {
    			 return [
                    'title'=> "สร้าง ตำบล/แขวง",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];      
    		}
    	} else {

    		if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
    	}
    }

    public function actionUpdate($id) {

    	$request = Yii::$app->request;
    	$model = $this->findModel($id);

    	if($request->isAjax) {

    		Yii::$app->response->format = Response::FORMAT_JSON;
    		if($request->isGet) {

    			return [
    				'title' => "แก้ไข ตำบล/แขวง #". $id,
    				'content' => $this->renderAjax('update', [
    					'model' => $model,
    				]),
    				'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]).
    					Html::button('Save', ['class' => 'btn btn-primary', 'type' => 'submit'])
    			];
    		} else if($model->load($request->post()) && $model->save()) {
    			return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "ResDistrict #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
    		} else {

    			return [
    				'title' => 'แก้ไข ตำบล/แขวง #'. $id,
    				'content' => $this->renderAjax('update', [
    					'model' => $model
    				]),
    				'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
    			];
    		}
    	} else {

    		if($model->load($request->post()) && $model->save()) {
    			return $this->redirect(['view', 'id' => $model->id]);
    		} else {
    			return $this->render("update", [
    				'model' => $model,
    			]);
    		}
    	}
    }


    public function actionDelete($id) {

    	$request = Yii::$app->request;
    	$this->findModel($id)->delete();

    	if($request->isAjax) {
    		Yii::$app->response->format = Response::FORMAT_JSON;
    		return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
    	} else {

    		return $this->redirect(['index']);
    	}
    }

    public function actionBulkDelete(){

    	$request = Yii::$app->request;
    	$pks = explode(',', $request->post('pks'));
    	foreach ($pks as $key => $pk) {
    		$model = $this->findModel($pk);
    		$model->delete();
    	}

    	if($request->isAjax) {

    		Yii::$app->response->format = Response::FORMAT_JSON;
    		return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
    	} else {

    		return $this->redirect(['index']);
    	}
    }

    protected function findModel($id) {

    	if(($model = ResSubdistrict::findOne($id)) !== null) {
    		return $model;
    	} else {

    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
}

?>