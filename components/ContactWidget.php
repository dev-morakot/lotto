<?php

namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use yii;
use app\modules\resource\models\ResAddress;

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
class ContactWidget extends Widget {

    //put your code here
    public $partner_id;
    public $address_id = null;
    public $contact_person;
    public $bootstrap_enable = false;
    public $locale = 'th';
    private $html;

    public function init() {
        parent::init();
        $model = \app\modules\resource\models\ResPartner::findOne(['id' => $this->partner_id]);

        $props = [
            'th'=>[
                'label_contact'=>'ผู้ติดต่อ',
                'district'=>"อ.",
                'province'=>'จ.',
                'tel'=>'โทร',
                'fax'=>'แฟกซ์',
                'contact_person'=>'ผู้ติดต่อ'
            ],
            'en'=>[
                'label_contact'=>'Contact',
                'district'=>"",
                'province'=>'',
                'tel'=>'TEL',
                'fax'=>'FAX',
                'contact_person'=>'CONTACT PERSON'
            ]
        ];

        $p = $props[$this->locale]; // th or en
        
        
        if ($model) {
            $company = $model->company;
            $contact = ($this->contact_person && !empty($this->contact_person))?$this->contact_person:$model->contact_person;
            $this->html = "<div class='contact'>";
            if ($company) {
                $address = null;
                if ($this->address_id) {
                    $address = $company->getAddresses()->where(['id'=>$this->address_id])->one();
                } else {
                    $address = $company->defaultAddress;
                }
                // ผู้จัดจำหน่ายเป็น Sale
                $this->html .= "<div>" . $company->name . "</div>";
                if($address){
                    $this->html .= "<div>" . $address->address1 . "</div>";
                    $this->html .= "<div>" . $address->address2 . "</div>";
                    $this->html .= "<div>";
                    $this->html .= ($address->district)?$p['district'].$address->districtDisplay.", ":"";
                    $this->html .= ($address->province)?$p['province'].$address->provinceDisplay.", ":"";
                    $this->html .= ($address->postal_code)?$address->postal_code:"";
                    $this->html .= "</div>";
                }
                $this->html .= "<div>".$p['tel'].": ". $company->phone . "," . $company->mobile . "</div>";
                $this->html .= "<div>".$p['fax'].": " . $company->fax . "</div>";
                // Contact
                $this->html .= "<div><b>".$p['label_contact'].":</b></div>";
                $this->html .= "<div>" . $model->name . "</div>";
                $this->html .= "<div>".$p['tel'].': '. $model->phone . "," . $model->mobile . "</div>";
                $this->html .= "</div>"; // end contact
            } else {
                $address = null;
                if ($this->address_id) {
                    $address = $model->getAddresses()->where(['id'=>$this->address_id])->one();
                } else {
                    $address = $model->defaultAddress;
                }
                // ผู้จัดจำหน่ายเป็น บริษัท
                $this->html .= "<div>" . $model->name . "</div>";
                if($address){
                    if($address->first_name){
                        $this->html .= "<div>" . $address->first_name ." ".$address->last_name. "</div>";
                    }
                    if($address->company_name){
                        $this->html .= "<div>" . $address->company_name. "</div>";
                    }
                    $this->html .= "<div>" . $address->address1 . "</div>";
                    $this->html .= "<div>" . $address->address2 . "</div>";
                    $this->html .= "<div>";
                    $this->html .= ($address->district)?$p['district'].$address->districtDisplay.", ":"";
                    $this->html .= ($address->province)?$p['province'].$address->provinceDisplay.", ":"";
                    $this->html .= ($address->postal_code)?$address->postal_code:"";
                    $this->html .= "</div>";
                    
                }
                $this->html .= "<div>".$p['tel'].": " . $model->phone . "," . $model->mobile . "</div>";
                $this->html .= "<div>".$p['fax'].": " . $model->fax . "</div>";
                $this->html .= "<div>".$p['contact_person'].": ".$contact."</div>";
                $this->html .= "</div>"; // end contact
            }
        } else {
            //
            $this->html = "data failed";
        }
    }

    public function run() {
        return $this->html;
    }

}
