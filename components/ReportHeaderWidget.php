<?php

namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use yii;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocStateWidget
 *
 * @author wisaruthk
 */
class ReportHeaderWidget extends Widget {

    //put your code here
    public $docname;
    public $bootstrap_enable = false;
    public $locale = "th";
    private $html;

    public function init() {
        parent::init();
        $this->html = "<div class='header' style='border-bottom:1px solid black;'>";
        if ($this->bootstrap_enable) {
            $this->html .= $this->render("@app/views/site/_report_header_bs",['docname'=>$this->docname]);
        } else {
            $this->html .= $this->render("@app/views/site/_report_header_tb",['docname'=>$this->docname,'locale'=>$this->locale]);
        }
        $this->html .= "</div>"; // end header
    }

    public function run() {
        return $this->html;
    }

}
