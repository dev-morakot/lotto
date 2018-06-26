<?php

namespace app\modules\resource\controllers;

use Yii;
use app\modules\resource\models\ResPartner;
use app\modules\resource\models\ResPartnerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use app\modules\resource\models\ResCountry;
use app\modules\resource\models\ResProvince;
use app\modules\resource\models\ResDistrict;
use app\modules\resource\models\ResAddress;
use app\modules\resource\models\ResSubdistrict;
use yii\helpers\Json;
use app\models\base\Model;

/**
 * ResPartnerController implements the CRUD actions for ResPartner model.
 */
class ResPartnerController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access'=> [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index','create','update','delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions'=>['create','update','delete'],
                        'roles' => ['partner/update']
                    ],
                    [
                        'allow'=>true,
                        'actions'=>['index'],
                        'roles'=>['partner/menu_main','partner/index']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all ResPartner models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ResPartnerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $companyList = ArrayHelper::map(ResPartner::find()->where(['is_company'=>true])->orderBy('name')->asArray()->all(), 'id', 'name');
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'companyList' => $companyList
        ]);
    }

    /**
     * Displays a single ResPartner model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "ResPartner #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new ResPartner model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        Yii::info(['create post',Yii::$app->request->post()]);
        $request = Yii::$app->request;
        $model = new ResPartner();
        $addrs = [new ResAddress()];
        
        if($model->load($request->post())){
            $data = Yii::$app->request->post('ResAddress', []);
            foreach(array_keys($data) as $index){
                $addrs[$index] = new ResAddress();
            }
            Yii::info(['init addrs'=>$addrs]);
            //$addresses = Model::createMultiple(ResAddress::className(),[],Yii::$app->request->post());
            Model::loadMultiple($addrs, Yii::$app->request->post());
            Yii::info(['loaded multiple',ArrayHelper::getColumn($addrs, 'first_name')]);
            
            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($addrs) && $valid;
            
            if($valid){
                $tx = Yii::$app->db->beginTransaction();
                
                try {
                    if($flag = $model->save(false)){
                        foreach ($addrs as $addr){
                            $addr->partner_id = $model->id;
                            if(! ($flag = $addr->save(false))){
                                $tx->rollBack();
                                break;
                            }
                        }
                        $first_addr = @$addrs[0];
                        if($first_addr){
                            $model->default_address_id = $first_addr->id;
                            $model->save(false);
                        }
                        
                    }

                    if($flag){
                        $tx->commit();
                        return $this->redirect(['view','id'=>$model->id]);
                    }
                } catch (Exception $ex) {
                    $tx->rollBack();
                }
                
                
            }
            
        }
        
        
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ResPartner model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $prev_addresses = $model->addresses;
        Yii::info(['post'=>Yii::$app->request->post()]);
        
        if($model->load(Yii::$app->request->post())){
            $oldIds = ArrayHelper::map($prev_addresses, 'id', 'id');
            Yii::info(['old_ids',$oldIds]);
            
            $addresses = Model::createMultiple(ResAddress::className(),
                    $prev_addresses,Yii::$app->request->post('ResAddress'));
            Yii::info(['crateMultiple', ArrayHelper::map($addresses,'id','first_name')]);
            
            Model::loadMultiple($addresses, Yii::$app->request->post());
            Yii::info(['new_ids', ArrayHelper::map($addresses, 'id','first_name')]);
            
            // เดิม - ใหม่ = id ที่ลบออก
            $deletedIDs = array_diff($oldIds, array_filter(ArrayHelper::map($addresses, 'id', 'id')));
            Yii::info(['deleted_ids'=>$deletedIDs]);
            $valid = $model->validate();
            $valid = Model::validateMultiple($addresses) && $valid;
            
            if($valid){
                $tx = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            ResAddress::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($addresses as $modelAddress) {
                            $modelAddress->partner_id = $model->id;
                            if (! ($flag = $modelAddress->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                        
                        $first_addr = @$addresses[0];
                        if($first_addr && $model->default_address_id == null){
                            $model->default_address_id = $first_addr->id;
                            $model->save(false);
                        }
                    }
                    if ($flag) {
                        $tx->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $tx->rollBack();
                }
            }
        }
        
        return $this->render('update', [
                        'model' => $model,
        ]);
        
    }

    /**
     * Delete an existing ResPartner model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing ResPartner model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete() {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the ResPartner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ResPartner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ResPartner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPartnerList($q =null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $query = new Query;
        $query->select('id, name AS text')
              ->from('res_partner');
        if (!is_null($q)) {
            $query->where(['like', 'name', $q]);
        }
        $query->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
//        } elseif ($id > 0) {
//            $out['results'] = ['id' => $id, 'text' => ResPartner::find($id)->nameFull];
//        }
        return $out;
    }
    
   /**
    * For angular component bicPartnerSelect
    * @param type $q
    * @param type $limit
    * @param type $is_supplier
    * @param type $is_customer
    * @return type
    */
    public function actionPartnerListJson($q="",$limit=20,$partner_type=null,$active_type="active"){
          $query = ResPartner::find()
                ->where(['or',['like','name',$q],['like','code',$q]]);
          if($partner_type){
              if($partner_type=='customer'){
                  $query->andWhere(['customer'=>true]);
              }
              if($partner_type=='supplier'){
                  $query->andWhere(['supplier'=>true]);
              }
          }
         
          if($active_type=="active"){
              $query->andWhere(['active'=>true]);
          }
          $partners = $query->limit($limit)->asArray()->all();
        //Yii::info($query->createCommand()->rawSql);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //Yii::info(\yii\helpers\VarDumper::dumpAsString($partners, 2));
        return $partners;
    }
    
    public function actionProvinceDepdrop($country_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        $selected = "";
        $out = ResProvince::find()->where(['country_id'=>$country_id])->all();
        return ['data'=>$out,'selected'=>''];
    }
    
    public function actionDistrictDepdrop($province_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        $selected = "";
        $out = ResDistrict::find()->where(['province_id'=>$province_id])->all();
        return ['data'=>$out,'selected'=>''];
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
                    ->with(['country','province','district','subdistrict'])
                    ->where(['partner_id'=>$id])
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
            $addr->partner_id = $id;
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
    public function actionAddressListJson($partner_id=null,$q=null,$limit=20){
          $query = ResAddress::find();
          $partner = ResPartner::findOne(['id'=>$partner_id]);
          if($partner){  
            $query->where(['partner_id'=>$partner->id]);
          }
          
          $addresses = $query->limit($limit)->all();
                
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $addresses;
    }
    
    /**
     * เฉพาะ Adresss ที่เป็นของ องค์กรเราในหน้า setting องค์กร
     */
    public function actionAddressWeListJson(){
        $setting = \app\models\Settings::current();
          $query = ResAddress::find();
            $query->where(['is_company_addr'=>true]);
          
          
          $addresses = $query->all();
                
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $addresses;
    }

    /**
     * for Angular component report sale Partner
     */
    public function actionFindPartnerJson($q="", $order_by=null,$sort="asc",$itemsPerPage = 20, $currentPage = 1) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = ResPartner::find()->alias('p')
            ->where(['or', ['like','p.code', $q], ['like','p.name',$q]]);
        if($order_by) {
            $sort_by = ($sort == 'asc')?SORT_ASC:SORT_DESC;
            $query->orderBy(['p.'.$order_by=>$sort_by]);
        }
        $totalItems = $query->count();
        $offset = ($currentPage - 1) * $itemsPerPage;
        $query->offset($offset);
        $query->limit($itemsPerPage);
        $partner = $query->asArray()->all();
        return ['lines' => $partner, 'totalItems' => $totalItems];
    }
    
    /**
     * for Select2 Widget
     * @return type
     */
    public function actionPartnerAddressList($q =null, $partner_id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result = [];
        $addrs = ResAddress::find()->where(['partner_id'=>$partner_id])->all();
        foreach ($addrs as $addr){
            $result[] = ['id'=>$addr->id,'text'=>$addr->displayName];
        }
        return ['results'=>$result];
    }
    
    /**
     * Select2
     * @param type $q
     * @param type $id
     * @return type
     */
    public function actionCountryList($q="",$id=null){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, name AS text')
                ->from('res_country')
                ->where(['like', 'name', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => ResCountry::find($id)->name];
        }
        return $out;
    }
    
    /**
     * Select2
     * @param type $q
     * @param type $id
     * @return type
     */
    public function actionProvinceList($q="",$id=null,$country_id=null){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, name AS text')
                ->from('res_province')
                ->where(['like', 'name', $q]);
            
            $query->andFilterWhere(['country_id'=>$country_id])
                ->limit(20);
            
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => ResProvince::find($id)->name];
        }
        return $out;
    }
    
    /**
     * Select2
     * @param type $q
     * @param type $id
     * @return type
     */
    public function actionDistrictList($q="",$id=null,$province_id=null){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, name AS text')
                ->from('res_district')
                ->where(['like', 'name', $q]);
            
            $query->andFilterWhere(['province_id'=>$province_id])
                ->limit(20);
            
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => ResDistrict::find($id)->name];
        }
        return $out;
    }
    
    /**
     * Select2
     * @param type $q
     * @param type $id
     * @return type
     */
    public function actionSubDistrictList($q="",$id=null,$district_id=null){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, name AS text')
                ->from('res_subdistrict')
                ->where(['like', 'name', $q]);
            
            $query->andFilterWhere(['district_id'=>$district_id])
                ->limit(20);
            
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => ResSubdistrict::find($id)->name];
        }
        return $out;
    }

}
