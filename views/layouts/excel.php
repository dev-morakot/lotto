<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\PdfReportAsset;

//PdfReportAsset::register($this);

//ยกเลิก Toolbar ทำให้ wkhtmltopdf error
if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}
// must use full path for wkhtmltopdf
$this->registerCssFile("@app/web/css/reportstyle/base.css");
$this->registerCssFile("@app/web/css/reportstyle/bootstrap.min.css");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>"
      xmlns:o="urn:schemas-microsoft-com:office:office" 
      xmlns:x="urn:schemas-microsoft-com:office:excel" 
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

       <div class="container-fluid">
        <?= $content ?>
       </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
