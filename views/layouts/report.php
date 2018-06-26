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
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="UTF-8">
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
