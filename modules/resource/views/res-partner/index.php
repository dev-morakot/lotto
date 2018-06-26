<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use kartik\select2\Select2Asset;
use app\modules\resource\assets\PartnerFormAsset;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\resource\models\ResPartnerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'คู่ค้า');
$this->params['breadcrumbs'][] = $this->title;
$this->params['homeLink'] = ['label' => 'Master', 'url' => Url::toRoute('/resource/default')];

$this->params['body_container'] = 'container-fluid';

// register css and javascript
CrudAsset::register($this);
Select2Asset::register($this);

$this->registerJs('
    //quick fix bug select2 ใช้ไม่ได้บน bootstrap modal
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
');
$export = "";
$formatter = app\components\AppHelper::defaultFormatter();
?>
<div class="res-partner-index">
    <div id="ajaxCrudDatatable">
        <?php if(Yii::$app->user->can('partner/export')): ?>
        <?php
            $export = ExportMenu::widget([
            'target'=> ExportMenu::TARGET_BLANK,
            'showConfirmAlert'=>false,
            'asDropdown'=>true,
            'showColumnSelector'=>true,
            'filename'=>'Partners',
            'deleteAfterSave'=>true,
            'folder'=> '@runtime/export',
            'exportConfig'=>[
                ExportMenu::FORMAT_HTML=>false,
                ExportMenu::FORMAT_TEXT=>false,
                ExportMenu::FORMAT_PDF=>false,
            ],
            'dataProvider'=> $dataProvider,
            'columns'=>require(__DIR__.'/_columns.php'),
        ]);
        ?>
        <?php endif; ?>
        
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'pjaxSettings'=>[
                'id'=>'partner_pjax_id2',
            ],
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['title'=> 'Create new Res Partners','data-pjax'=>0,'class'=>'btn btn-default']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    //'{toggleData}',
                    $export
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => false,    
            'responsiveWrap'=>false,
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> รายการคู่ค้า',
                
                'after'=>BulkButtonWidget::widget([
                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
                                ["bulk-delete"] ,
                                [
                                    "class"=>"btn btn-danger btn-xs",
                                    'role'=>'modal-remote-bulk',
                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'Are you sure?',
                                    'data-confirm-message'=>'Are you sure want to delete this item'
                                ]),
                        ]).                        
                        '<div class="clearfix"></div>',
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>