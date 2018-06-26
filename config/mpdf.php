<?php

return [
    'class' => kartik\mpdf\Pdf::className(),
    'mode' => kartik\mpdf\Pdf::MODE_UTF8,
    'format' => kartik\mpdf\Pdf::FORMAT_A4,
    'orientation' => kartik\mpdf\Pdf::ORIENT_PORTRAIT,
    'destination' => kartik\mpdf\Pdf::DEST_BROWSER,
    //'cssFile' => "@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css",
    'cssFile'=>'@app/web/css/pdf.css',
    //'cssInline' => "body {font-family:'Garuda'; font-size:10pt;} .container{border-collapse: collapse;border-spacing:0;}"
];
