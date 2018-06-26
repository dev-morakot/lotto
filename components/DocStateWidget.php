<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

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
class DocStateWidget extends Widget {
    //put your code here
    public $text = 'สถานะเอกสาร';
    public $stateList;
    public $currentState;
    private $html;


    public function init(){
        parent::init();
        foreach ($this->stateList as $key => $value) {
            if($key == $this->currentState){
                $value = '<b style="color:blue;">'.$value.'</b>';
            } else {
                $value = '<span style="color:grey;">'.$value.'</span>';
            }
            $this->html = $this->html." > ".$value;
        }
        
    }
    
    public function run(){
        return '<p>'.$this->text.':'.$this->html.'</p>';
    }
}
