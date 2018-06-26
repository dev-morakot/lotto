<?php

namespace app\modules\admin\controllers;

use yii;
use yii\web\Controller;
use app\models\Settings;
use yii\web\UploadedFile;
use app\modules\resource\models\ResCountry;
use app\modules\resource\models\ResProvince;
use app\modules\resource\models\ResDistrict;
use app\modules\resource\models\ResSubdistrict;
use yii\helpers\Json;
use app\modules\resource\models\ResAddress;
/**
 * Default controller for the `admin` module
 */
class SettingsController extends Controller {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Displays a single Log model.
     * @param integer $id
     * @return mixed
     */
    public function actionView() {
        $model = Settings::current();
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Log model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate() {
        $model = Settings::current();
        $isLoaded = $model->load(Yii::$app->request->post());
        if ($isLoaded) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile) {
                $result = $model->upload();
                if ($result) {
                    Yii::$app->session->setFlash('success', 'upload success ' . $model->imageFile->name);
                    $model->logo = $model->imageFile->name;
                    $model->imageFile = null;
                } else {
                    Yii::$app->session->setFlash('warning', 'upload error');
                }
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionResourceForFormAjax() {
        $countries = ResCountry::find()->all();
        $provinces = ResProvince::find()->all();
        $districts = ResDistrict::find()->all();
        $subdistricts = ResSubdistrict::find()->all();
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'countries'=>$countries,
            'provinces'=>$provinces,
            'districts'=>$districts,
            'subdistricts'=>$subdistricts
        ];
    }
    
    public function actionLoadAddressLines($id=null){
        if($id){
            $addrs = ResAddress::find()
                    ->with(['province','country','district','subdistrict'])
                    ->where(['setting_id'=>$id])
                    ->asArray()
                    ->all();
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $addrs;
        }
    }
    
    public function actionSaveAddressLine(){
        $postdata = Yii::$app->request->rawBody;
        $json = Json::decode($postdata);
        $id = $json['id'];
        $line = $json['line'];
        $line['id'] = isset($line['id'])?$line['id']:-1;
        $addr = ResAddress::find()->where(['id'=>$line['id']])->one();
        if($addr){
            $addr->attributes = $line;
            $result = $addr->update();
        } else {
            $addr = new ResAddress();
            $addr->attributes = $line;
            $addr->setting_id = $id;
            $result = $addr->insert();
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $addr;
    }
    
    public function actionRemoveAddressLine(){
        $postdata = Yii::$app->request->rawBody;
        $json = Json::decode($postdata);
        $id = $json['id'];
        $line = $json['line'];
        $result = ResAddress::find()->where(['id'=>$line['id']])->one()->delete();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $result;
    }
    
    //
    public function actionAddressListJson($setting_id=null,$q=null,$limit=20){
          $query = ResAddress::find()
                  ->with(['country','province','district','subdistrict']);
          if($setting_id){
            $query->where(['setting_id'=>$partner_id]);
          }
          
          $addresses = $query->limit($limit)->all();
                
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $addresses;
    }

    /**
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
