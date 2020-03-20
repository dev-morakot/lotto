<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\web\View;
use app\models\Settings;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php $this->registerJs("
            numeral.language('th');
            bootbox.setDefaults({
                backdrop:true,
                closeButton:true,
                animage:true,
                className:'bic-modal'
            });
            ",View::POS_END)?>
</head>
<body>
    <?php
    if(!isset($this->params['body_container'])){
        $this->params['body_container'] = "container";
    } 
?>
<?php $this->beginBody() ?>
    
<div class="wrap">
    <?php
   
    NavBar::begin([
        'brandLabel' => Yii::$app->params['companyLabel'],
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-fixed-top navbar-inverse',
        ],
        'innerContainerOptions'=>[
            'class'=>'container'
        ]
    ]);
    $menuItems = [];
    if(Yii::$app->user->isGuest){
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems = [
            [
                'label' => 'สรุปยอดหวย',
                'url'=>['/resource/default'],
                'visible'=>Yii::$app->user->can('partner/menu_main'),
                'items'=>[
                    ['label'=>'สรุปยอดซื้อ','url'=>'/resource/res-doc-report/report-all'],                    
                    ['label' => 'สรุปเลขสองตัว', 'url' => '/resource/res-doc-report/report-two'],
                    ['label' => 'สรุปเลขสามตัว', 'url' => '/resource/res-doc-report/report-three'],
                    ['label' => 'สรุปเลขทั้งหมด', 'url' => '/resource/res-doc-report/all']
                ],
                
            ],
            [
                'label' => 'ตั้งค่าเลขไม่รับซื้อ',
                'url'=>['/resource/res-res-traints'],
                'linkOptions' => ['target' => '_blank']
            ]  
            /*[
                'label' => 'คีย์ข้อมูลหวย',
                'url'=>['/resource/res-doc-lotto/'],
                
            ],
            [
                'label' => 'ตรวจหวย',
                'url'=>['/resource/res-doc-check-lotto/'],
                
            ],*/
            //['label' => 'About', 'url' => ['/site/about']],
            //['label' => 'Contact', 'url' => ['/site/contact']],
            ];
        
        $groups = @app\modules\resource\models\ResUsers::currentUserGroups();
        $names = [];
        foreach ($groups as $g){
            $names[] = $g->name;//Html::tag('label',$g->name,['class'=>'label label-info']);
        }
        $tags = @implode(', ', $names);
        
        $menuItems[] = [
          'label' => 'Me',
          'items'=>[
             ['label'=>'Profile ('.Yii::$app->user->identity->username.')', 'url'=>['/site/profile']],
             //'<li class="divider"></li>',
             '<li style="padding-left:22px; padding-right:22px; color:grey;"><small>'.$tags.'</small></li>',
             '<li class="divider"</li>',
             [
                 'label' => 'Logout',
                 'url' => ['/site/logout'],
                 'linkOptions' => ['data-method' => 'post']
             ],
          ],
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
        
    <div class="<?=$this->params['body_container']?>">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'homeLink'=>isset($this->params['homeLink'])? $this->params['homeLink']:false
        ]) ?>
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('warning')): ?>
            <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?= Yii::$app->session->getFlash('warning') ?>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?=Yii::$app->params['companyLabel']?> <?= date('Y') ?></p>
        <p class="pull-right">
            <?php
                $dt = new \DateTime();
                $dbdt = Yii::$app->db->createCommand('SELECT NOW()')->queryScalar();
            ?>
            <span><b>Date/Time:</b> <?=$dt->format('Y-m-d H:i:s')?></span>
            <span><b>Timezone:</b> <?=$dt->getTimezone()->getName()?></span>
            <span><b>DB Date/Time:</b> <?=$dbdt?></span>
            <?php if(false): ?>
            <br>
            <span><?= $this->context->action->uniqueId ?></span>
            <span><b>Action:</b><?=$this->context->action->id?>, <b>Ctrl:</b><?=get_class($this->context)?>, <b>Module:</b><?=$this->context->module->id?></span>
            <?php endif;?>
        </p>
        
        <p class="pull-right"><?php Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
