<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 *  เพื่อ Link ไปยังเอกสารที่เกี่ยวข้อง
 *
 * @property User|null $user This property is read-only.
 *
 */
class RelatedModelRouter extends Model {
    private static $route = array(
        'sale_order'=>'/sale/sale-order/view',
        'purchase_order'=>'/purchase/purchase-order/view',
        'stock_picking'=>'/stock/stock-picking/view',
        'purchase_order_intl'=>'/purchase_intl/purchase-order-intl/view',
        'stock_goods_import'=>'/stock/stock-goods-import/view',
        'account_invoice'=>'/account/account-invoice/view',
        'purchase_request'=>'/purchase/purchase-request/view',
        'account_stock'=>'/account/account-stock/view',
        'account_payment'=>'/account/account-payment/view',
        'qc_inspection'=>'/stock/qc-inspection/view',
        'account_stock_picking'=>'/account/account-stock-picking/view',
        'stock_count'=>'/stock/stock-count/view',
        'stock_fg_import'=>'/stock/stock-fg-import/view',
        'purchase_receipt'=>'/stock/purchase-receipt/view',
        'stock_lot'=>'/stock/stock-lot/view',
        'project_project'=>'/project/project-project/view'
    );
    /**
     * get relate link for current model
     * @param type $model
     * @return dictionary
     */
    public static function relatedLink($model) {
        if(!isset($model['related_model'])){
            return NULL;
        }
        $related_table = $model['related_model'];
        $related_model = $model['related_model'];
        $related_id = $model['related_id'];
        if ($related_model) {
            $query = new \yii\db\Query();
            $query->select('name, id')->from($related_table)->where(['id'=>$related_id]);
            $command = $query->createCommand();
            $result = $command->queryOne();
            $url = Url::to([self::$route[$related_table], 'id' => $related_id]);
            if($result){
                return ['name' => $result['name'], 'url' => $url];
            } else {
                return ['name'=>'unknow','url'=>$url];
            }
        }
        return NULL;
    }
    
    /**
     * get relate link for current model
     * @param type $model
     * @return html
     */
    public static function relatedHtml($model){
        $info = self::relatedLink($model);
        if($info){
            return '<span>'.$info['name'].'</span>'.'<a href="'.$info['url'].'" target="_blank" data-pjax="0"><span class="glyphicon glyphicon-arrow-right"></span></a>';
        } else {
            return NULL;
        }
    }
    
    /**
     * get relate link for current model
     * @param type $model
     * @return html
     */
    public static function relatedHtmlArrow($model){
        $info = self::relatedLink($model);
        if($info){
            return '<a href="'.$info['url'].'" target="_blank" data-pjax="0"><span class="glyphicon glyphicon-arrow-right"></span></a>';
        } else {
            return NULL;
        }
    }
    
    public static function createDocLink($model){
        $route = @self::$route[$model::tableName()];
        $host_info = isset(Yii::$app->params['host_info'])?Yii::$app->params['host_info']:null;
        if($host_info){
            Yii::$app->urlManager->setHostInfo($host_info);
        }
        if($route){
            return Html::a(@$model['name'],Yii::$app->urlManager->createAbsoluteUrl([$route,'id'=>@$model['id']]));
        } else {
            return "ERROR LINK";
        }
    }

}
