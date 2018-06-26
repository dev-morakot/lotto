<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\Settings;
use app\modules\resource\models\ResAddress;

$this->registerCss('
        .header {
            font-size:10px;
        }
        ');
?>
<?php
$setting = Settings::current();
$addr = $setting->defaultAddress;
?>
<div class="row">
    <div class="col-xs-6 header">
        <div><?= Html::img(Yii::getAlias('@img_com') . '/' . $setting->logo, ['width' => 120]) ?></div>
        <div><span><?= $addr->company_name ?> เลขประจำตัวผู้เสียภาษี:<?= $setting->tax_no ?></span></div>
        <div><span><?= $addr->address1 ?> <?= $addr->address2 ?></span></div>
        <div><span>
                <?php
                if ($addr->district) {
                    echo 'อ.' . $addr->district->name . ', ';
                }
                if ($addr->province) {
                    echo 'จ.' . $addr->province->name . ', ';
                }
                if ($addr->postal_code) {
                    echo $addr->postal_code . ', ';
                }
                if ($addr->country) {
                    echo $addr->country->name;
                }
                ?>
            </span></div>
    </div>
    <div class='col-xs-6 text-right'><h4><?= $docname ?></h4></div>
</div>
