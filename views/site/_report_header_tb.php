<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\Settings;
?>
<?php
$setting = Settings::current();
$addr = $setting->defaultAddress;
?>

<table class="container">
    <tr>
        <td style="font-size:10px;">
            <div><?= Html::img(Yii::getAlias('@img_com') . '/' . $setting->logo, ['width' => 120]) ?></div>
            <?php if($locale=='th'):?>
            <?= nl2br($setting->report_header)?>
            <div><span>เลขประจำตัวผู้เสียภาษี:<?= $setting->tax_no ?></span></div>
            <?php                    endif;?>
            <?php if($locale=='en'):?>
            <?= nl2br($setting->report_header_en)?>
            <?php endif;?>
        </td>
        <td class="text-right">
            <div><h4><?= $docname ?></h4></div>
        </td>
    </tr>
</table>
