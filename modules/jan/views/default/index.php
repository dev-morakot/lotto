<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use app\modules\jan\assets\JanAsset;


JanAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'คีย์ข้อมูลหวย');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'หน้าแรก'), 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
    display: none !important;
}');
$this->registerCss('

fieldset {
    font-family: sans-serif;
    border: 5px solid #1F497D;
    background: #ddd;
    border-radius: 5px;
    padding: 10px;

}

fieldset legend {
    background: #1F497D;
    color: #fff;
    padding: 5px 10px ;
    font-size: 18px;
    border-radius: 5px;
    box-shadow: 0 0 0 5px #ddd;
    margin-left: 0px;
}
.text-on-pannel {
  background: #fff none repeat scroll 0 0;
  height: auto;
  margin-left: 20px;
  padding: 3px 5px;
  position: absolute;
  margin-top: -47px;
  border: 1px solid #337ab7;
  border-radius: 8px;
}

.panel {
  
  margin-top: 27px !important;
}

.panel-body {
  padding-top: 30px !important;
}

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.ex1 {
  width: 100%;
  height: 490px;
  overflow: scroll;
}
');
?>

<div ng-app="myapp" ng-cloak ng-controller="FormController" class="res-doc-lotto">
	<div class="row">
	<div class="col-md-6">
    <h1><?= Html::encode($this->title) ?> ( สถานะ: <span ng-if="model.state === true" style="color: green;font-weight: bold"> แทงเร็ว </span><span ng-if="model.state === false" style="color: blue;font-weight: bold"> แทงที่ละตัว </span>)</h1> 
	</div>
	</div>


	<form novalidate class="form-horizontal" name="form">
		<div class="row">
			
			<div class="col-md-9">
				<form class="form-horizontal">
				  <div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label">ประเภท</label>
				    <div class="col-sm-10">
				      <select ng-model="modline.type" class="form-control">
				      	<option value="หวยไทย">หวยไทย</option>
				      </select>
				    </div>
				  </div>
				</form>
				<div class="panel panel-primary">
				    <div class="panel-body" style="min-height: 200px">
				      <h3 class="text-on-pannel text-primary"><strong class="text-uppercase"> คีย์  </strong></h3>

				      	<div class="row">

						  <div class="col-md-1">
						  	<button type="button" ng-click="clearLotto()" class="btn btn-danger">
						  		ยกเลิก</button>
						  	
						  	<div class="row">

								<div class="" style="margin-top: 5px;margin-left: 10px">
						  			<label class="switch">
									  <input type="checkbox" name="state" ng-model="model.state">
									  <span class="slider round"></span> 
									</label>
						  		</div>
						  	</div>
						  </div>

							<!-- แทงเร็ว -->
						  <div class="col-md-3" ng-if="model.state === true">
						  	<textarea class="form-control" ng-list="/\n/" name="number_quick" ng-model="model.number_quick" placeholder="ใส่ตัวเลข 1-4 ตัว" rows="6"></textarea>
						  </div>
						  <!-- ที่ละตัว -->
						  <div class="col-md-3" ng-if="model.state === false">
						  	<input type="text" class="form-control" name="number_slowly" id="f_1" ng-model="model.number_slowly"  ng-keydown="keydown($event,2)" placeholder="ใส่ตัวเลข 1-4 ตัว" />
						  	
						  </div>


						  	<div class="col-md-2">
							
							  <div class="form-group" style="margin-top: -25px" ng-class="(form.top_amount.$error.pattern)? 'has-error has-feedback': 'has-success has-feedback'">
							    <label>บน</label>
							    <input type="text" id="f_2" ng-keydown="(model.number_slowly.length === 2)?keydown($event,3):keydown($event,4)" ng-pattern="/^[0-9]{1,7}$/" required ng-model="model.top_amount" name="top_amount" class="form-control"  placeholder="บน">
							  <span ng-class="(form.top_amount.$error.pattern)? 'glyphicon glyphicon-remove form-control-feedback': 'glyphicon glyphicon-ok form-control-feedback'" aria-hidden="true"></span>
  								<span id="inputSuccess2Status" class="sr-only">(success)</span>
  								<small style="color: red" ng-show="form.top_amount.$error.pattern">กรุณาใส่ตัวเลข</small>
							  </div>
								
							 </div>

							 <div class="col-md-2">
							  <div class="form-group" style="margin-left: 3px;margin-top: -25px" ng-class="(form.below_amount.$error.pattern)? 'has-error has-feedback': 'has-success has-feedback'">
							    <label>ล่าง</label>
							    <input type="text"  id="f_3" ng-keydown="(model.number_slowly.length === 2)&&keydown($event,5)" ng-pattern="/^[0-9]{1,7}$/" required ng-model="model.below_amount" name="below_amount" class="form-control"  placeholder="ล่าง">
							  <span ng-class="(form.below_amount.$error.pattern)? 'glyphicon glyphicon-remove form-control-feedback': 'glyphicon glyphicon-ok form-control-feedback'" aria-hidden="true"></span>
  								<span id="inputSuccess2Status" class="sr-only">(success)</span>
  								<small style="color: red" ng-show="form.below_amount.$error.pattern">กรุณาใส่ตัวเลข</small>
							  </div>

							 </div>

							<div class="col-md-2">
							  <div class="form-group" style="margin-left: 3px;margin-top: -25px" ng-class="(form.otd_amount.$error.pattern)? 'has-error has-feedback': 'has-success has-feedback'">
							    <label>โต๊ด</label>
							    <input type="text" id="f_4" ng-keydown="(model.number_slowly.length === 3)&&keydown($event,5)" ng-pattern="/^[0-9]{1,7}$/" required ng-model="model.otd_amount" name="otd_amount" class="form-control"  placeholder="โต๊ด">
							  <span ng-class="(form.otd_amount.$error.pattern)? 'glyphicon glyphicon-remove form-control-feedback': 'glyphicon glyphicon-ok form-control-feedback'" aria-hidden="true"></span>
  								<span id="inputSuccess2Status" class="sr-only">(success)</span>
  								<small style="color: red" ng-show="form.otd_amount.$error.pattern">กรุณาใส่ตัวเลข</small>
							  </div>
						  	</div> 


						  	<div class="col-md-2">
						  		<div class="form-group">
						  			<label>&nbsp;</label>
						  		<button type="button" id="f_5" ng-keydown="keydown($event, 1)" ng-click="addLotto()" class="btn btn-primary"><b class="glyphicon glyphicon-ok"></b> ยืนยัน</button>
						  		</div>
						  	</div>

						  	
						  		<div class="col-md-1"></div>
						  		<div class="col-md-2"></div>
						  		<div class="col-md-7">
									<button type="button" style="margin-left: -15px" ng-click="door19()" class="btn btn-default">19 ประตู</button>
									<button type="button" ng-click="ruleBefore()" class="btn btn-default">รูดหน้า</button>
									<button type="button" ng-click="ruleAfter()" class="btn btn-default">รูดหลัง</button>
									<button type="button" ng-click="door3()" class="btn btn-default">3 ประตู</button>
									<button type="button" ng-click="door6()" class="btn btn-default">6 ประตู</button>
									<button type="button" ng-click="TwoGoBack()" class="btn btn-default">2 ตัวไป-กลับ</button>


						  		</div>
						  		
						  	
						 
						</div>
				       
				    </div>
				  </div>
			</div>
			<div class="col-md-3">
				<div class="panel panel-primary">
				  <div class="panel-heading">ฟังก์ชั่น</div>
				  <div class="panel-body">
				   <button type="button"  data-toggle="modal" data-target="#myModal" class="btn btn-success btn-block"><b class="glyphicon glyphicon-list-alt"></b> รายการซื้อ</button>
						<button type="button" data-toggle="modal" data-target="#myAll" ng-click="reportAll()" class="btn btn-primary  btn-block"><b class="glyphicon glyphicon-file"></b> สรุปยอดทั้งหมด</button>
						<button type="button" ng-click="clearBill()" class="btn btn-danger  btn-block"><b class="glyphicon glyphicon-book"></b> Clear บิลทั้งหมด</button>
				  </div>
				</div>
				<!--<div class="panel panel-primary">
				    <div class="panel-body">
				      <h3 class="text-on-pannel text-primary"><strong class="text-uppercase"> ฟังก์ชั่น  </strong></h3>
				    
				      <button type="button"  data-toggle="modal" data-target="#myModal" class="btn btn-success btn-block"><b class="glyphicon glyphicon-list-alt"></b> รายการซื้อ</button>
						<button type="button" data-toggle="modal" data-target="#myAll" ng-click="reportAll()" class="btn btn-primary  btn-block"><b class="glyphicon glyphicon-file"></b> สรุปยอดทั้งหมด</button>
						<button type="button" ng-click="clearBill()" class="btn btn-danger  btn-block"><b class="glyphicon glyphicon-book"></b> Clear บิลทั้งหมด</button>
				    </div>
				</div> -->

			</div> 
		</div>
		<div class="row">
            <div class="col-sm-6">
            	<div class="panel panel-primary">
				    <div class="panel-body ex1" style="min-height: 490px">
				      <h3 class="text-on-pannel text-primary"><strong class="text-uppercase"> แสดงผล </strong></h3>
     
    
			             <table class="table table-striped table-bordered" style="margin-bottom: 40px">
					        <thead>
					            <tr style="background: #006699;color: white">
					               
					                <th style="text-align: center">เลข</th>
					                <th style="text-align: center">บน </th>
					                <th style="text-align: center">ล่าง </th>
					                <th style="text-align: center">โต๊ด </th>
					                <th style="text-align: center">-</th>
					            </tr>
					        </thead>
					        <tbody>
					            <tr ng-repeat="line in lottos">
					                
					                <td align="center" style="font-weight: bold">{{ line.number }}</td>
					                <td align="center">{{ line.top_amount }}</td>
					                <td align="right"> {{ line.below_amount }}</td>
					                <td align="center">{{ line.otd_amount }}</td>
					                <td align="center">
					                    <span class="btn btn-danger btn-sm" ng-click="doRemoveLine(line)">
					                    	<b class="glyphicon glyphicon-remove"></b>
					                    </span>
					                </td>
					            </tr>
					        </tbody>
					    </table>
					</div>
  				</div>
            </div>
            <div class="col-sm-3">
	            <div class="panel panel-primary">
				  <div class="panel-heading">ส่วนลด เลขวิ่ง(%)</div>
				  <div class="panel-body">
				    	<form>
							 <div class="form-group" style="padding: 8px">
							    <label for="exampleInputEmail1">ส่วนลด เลขวิ่ง(%)</label>
							    <input type="text" class="form-control show-number text-right" name="discount_run" ng-model="modline.discount_run" ng-change="disTwo()"  placeholder="ส่วนลด เลขวิ่ง(%)">
							 </div>
							 <div class="form-group" style="padding: 8px">
							    <label for="exampleInputEmail1">ส่วนลด 2 ตัว (%)</label>
							    <input type="text" class="form-control show-number text-right" name="discount_two" ng-model="modline.discount_two" ng-change="disTwo()"  placeholder="ส่วนลด 2 ตัว (%)">
							 </div>
							 <div class="form-group" style="padding: 8px">
							    <label for="exampleInputEmail1">ส่วนลด 3 ตัว (%)</label>
							    <input type="text" class="form-control show-number text-right" name="discount_three" ng-model="modline.discount_three" ng-change="disTwo()"   placeholder="ส่วนลด 3 ตัว (%)">
							 </div>
						</form>
				  </div>
				</div>
				<!--<section style="margin: 10px;">
					<fieldset style="min-height:100px;">
					<legend><b> ส่วนลด </b> </legend>
						<form>
							 <div class="form-group" style="padding: 8px">
							    <label for="exampleInputEmail1">ส่วนลด เลขวิ่ง(%)</label>
							    <input type="text" class="form-control show-number text-right" name="discount_run" ng-model="modline.discount_run" ng-change="disTwo()"  placeholder="ส่วนลด เลขวิ่ง(%)">
							 </div>
							 <div class="form-group" style="padding: 8px">
							    <label for="exampleInputEmail1">ส่วนลด 2 ตัว (%)</label>
							    <input type="text" class="form-control show-number text-right" name="discount_two" ng-model="modline.discount_two" ng-change="disTwo()"  placeholder="ส่วนลด 2 ตัว (%)">
							 </div>
							 <div class="form-group" style="padding: 8px">
							    <label for="exampleInputEmail1">ส่วนลด 3 ตัว (%)</label>
							    <input type="text" class="form-control show-number text-right" name="discount_three" ng-model="modline.discount_three" ng-change="disTwo()"   placeholder="ส่วนลด 3 ตัว (%)">
							 </div>
						</form>
					</fieldset>
				</section> -->
            </div>
            <div class="col-sm-3">
            	<div class="panel panel-primary">
				  <div class="panel-heading">ยอดที่ต้องชำระ</div>
				  <div class="panel-body">
				   	 	<form>
							 <div class="form-group" style="padding: 4px">
							    <label>ยอดที่ซื้อ</label>
							    <p id='purchaseorder-amount_total' style="background-color:#449D44;color:white;" class="form-control show-number text-right" >{{modline.amount_total| number }}
							    </p>
							 </div>
							 <div class="form-group" style="padding: 4px">
							    <label>ส่วนลด</label>
							    <p id='purchaseorder-amount_total' style="background-color:#449D44;color:white;" class="form-control show-number text-right" >{{modline.discount| number }}
							 </div>
							 <div class="form-group" style="padding: 4px">
							    <label>เหลือ</label>
							    <p id='purchaseorder-amount_total' style="background-color:#449D44;color:white;" class="form-control show-number text-right" >{{modline.amount_total_remain| number }}
							    </p>
							 </div>
						</form>
				  </div>
				</div>
				<!--<section style="margin: 10px;">
					<fieldset style="min-height:100px;">
					<legend><b> ยอดที่ต้องชำระ </b> </legend>
						<form>
							 <div class="form-group" style="padding: 4px">
							    <label>ยอดที่ซื้อ</label>
							    <p id='purchaseorder-amount_total' style="background-color:#449D44;color:white;" class="form-control show-number text-right" >{{modline.amount_total| number }}
							    </p>
							 </div>
							 <div class="form-group" style="padding: 4px">
							    <label>ส่วนลด</label>
							    <p id='purchaseorder-amount_total' style="background-color:#449D44;color:white;" class="form-control show-number text-right" >{{modline.discount| number }}
							 </div>
							 <div class="form-group" style="padding: 4px">
							    <label>เหลือ</label>
							    <p id='purchaseorder-amount_total' style="background-color:#449D44;color:white;" class="form-control show-number text-right" >{{modline.amount_total_remain| number }}
							    </p>
							 </div>
						</form>
					</fieldset>
				</section>-->
				
            </div>
            <div class="row">
					<div class="col-md-6"></div>
					<div class="col-md-6">
		            	<section style="margin: 10px;">
							<fieldset style="min-height:100px;">
							<legend><b> ปริ้น </b> </legend>
								<form>
									 <div class="form-group" style="padding: 8px">
									    <label for="exampleInputEmail1">ชื่อ</label>
									    <input type="text" name="name" ng-model="modline.name" class="form-control" placeholder="ชื่อ">
									 </div>
									
								</form>
							</fieldset>
						</section>

            		</div>
				</div>
            <div class="col-md-10">
            	<button type="button" ng-click="CancelAll()" class="btn btn-danger btn-lg"><b class="glyphicon glyphicon-ban-circle"></b> ยกเลิกทั้งหมด</button>
            </div>
 			 <div class="col-md-2">
 			 	
 			 	<button type="button" ng-click="saveAs()" class="btn btn-success btn-lg"><b class="glyphicon glyphicon-plus"></b> บันทึกและพิมพ์</button>
 			 </div>
        </div>
	</form>



	<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">รายการซื้อ</h4>
      </div>
      <div class="modal-body">
       
      	<form class="form-horizontal">
		  <div class="form-group">
		    <label for="inputEmail3" class="col-sm-2 control-label">ตัวเลข</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" name="search" ng-model="search" placeholder="ตัวเลข">
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <button type="button" ng-click="SearchKeyword(search)" class="btn btn-primary">ค้นหารายการ</button>
		    </div>
		  </div>

		</form>
		<hr />
		<table class="table table-striped table-bordered" style="margin-bottom: 40px">
					        <thead>
					            <tr>
					              	 <th style="text-align: center">ลำดับ</th>
					                <th style="text-align: center">เลข</th>
					                <th style="text-align: center">บน </th>
					                <th style="text-align: center">ล่าง </th>
					                <th style="text-align: center">โต๊ด </th>
					                <th style="text-align: center">รวมราคา</th>
					            </tr>
					        </thead>
					        <tbody>
					            <tr ng-repeat="line in results">
					                <td align="center">{{ $index + 1 }}</td>
					                <td align="center" style="font-weight: bold">{{ line.number }}</td>
					                <td align="right">{{ line.top_amount }}</td>
					                <td align="right"> {{ line.below_amount }}</td>
					                <td align="right">{{ line.otd_amount }}</td>
					                <td align="right">
					                    {{ line.line_amount_total | number }}
					                </td>
					            </tr>
					        </tbody>
					        <tfoot>
					        	<tr>
					        		<td colspan="5">รวมทั้งสิ้น :</td>
					        		<td align="right"> {{ sum | number }}</td>
					        	</tr>
					        </tfoot>
					    </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>


<!-- myAll -->
<div class="modal fade" id="myAll" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" style="width: 65%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">สรุปยอดซื้อทั้งหมด</h4>
      </div>
      <div class="modal-body">
      
		<table class="table table-striped table-bordered" style="margin-bottom: 40px">
					        <thead>
					            <tr>
					              	 <th style="text-align: center">ลำดับ</th>
					                <th style="text-align: center">หมายเลขบิล</th>
					                <th style="text-align: center">ชื่อ </th>
					                <th style="text-align: center">ยอดซื้อทั้งหมด </th>
					                <th style="text-align: center">ส่วนลด </th>
					                <th style="text-align: center">คงเหลือ</th>
					                <th style="text-align: center"> บิลปกติ / บิลเลขอั้น </th>
					            </tr>
					        </thead>
					        <tbody ng-repeat="line in customers">
					            <tr>
					                <td align="center">{{ $index + 1 }}</td>
					                <td align="center" style="font-weight: bold">
					                	{{ line.code }}  <p style="color: red;font-size: 11px" ng-if="line.state ==='limit'">(2 บิล)</p>
					                </td>
					                <td align="right">{{ line.name }}</td>
					                <td align="right"> {{ line.amount_total | number }}</td>
					                <td align="right">{{ line.discount | number }}</td>
					                <td align="right">
					                    {{ line.amount_total_remain | number }}
					                </td>
					                <td align="center">
				                      
				                        <button data-toggle="modal" data-target="#myCus" ng-click="expandSelected(line)" class='btn btn-primary btn-sm'><i class='glyphicon glyphicon-file'></i></button>
				                        <button ng-if="line.state ==='limit'" data-toggle="modal" data-target="#myLimit" ng-click="BillLimit(line)" class='btn btn-default btn-sm'><i class='glyphicon glyphicon-file'></i></button>
				                    </td>
					            </tr>
					          
					        </tbody>
					        <tfoot>
					        	<tr>
					        		<td colspan="3">รวมทั้งสิ้น :</td>
					        		<td align="right" style="font-weight: bold"> {{ total | number }}</td>
					        		<td align="right" style="font-weight: bold;color: red"> {{ dis | number }}</td>
					        		<td align="right" style="font-weight: bold;color: green"> {{ amount | number }}</td>
					        	</tr>
					        </tfoot>
					    </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>


<!-- myCus -->
<div class="modal fade" id="myCus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" style="width: 34%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">หมายเลขบิล : {{bill}} </h4>
      </div>
      <div class="modal-body">
      	<h2>คุณ : {{cus_name}}</h2>
      	<hr />
		<table class="table table-striped table-bordered" style="margin-bottom: 40px">
					        <thead>
					            <tr>
					              	
					                <th style="text-align: center">เลข</th>
					                <th style="text-align: center">บน </th>
					                <th style="text-align: center">ล่าง </th>
					                <th style="text-align: center">โต๊ด </th>
					                <th style="text-align: center">ราคา</th>
					                
					            </tr>
					        </thead>
					        <tbody ng-repeat="line in customers_line">
					            <tr>
					               
					                <td align="center" style="font-weight: bold">{{ line.number }}</td>
					                <td align="right">{{ line.top_amount }}</td>
					                <td align="right"> {{ line.below_amount }}</td>
					                <td align="right">{{ line.otd_amount }}</td>
					                <td align="right">
					                    {{ line.line_amount_total | number }}
					                </td>
					               
					            </tr>
					          
					        </tbody>
					       
					    </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>


<!-- myLImit -->
<div class="modal fade" id="myLimit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" style="width: 34%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">หมายเลขบิล : {{bill}} </h4>
      </div>
      <div class="modal-body">
      	<h2>คุณ : {{cus_name}}</h2>
      	<hr />
      	<p style="color: red">บิลเลขอั้น</p>
		<table class="table table-striped table-bordered" style="margin-bottom: 40px">
					        <thead>
					            <tr>
					              	
					                <th style="text-align: center">เลข</th>
					                <th style="text-align: center">บน </th>
					                <th style="text-align: center">ล่าง </th>
					                <th style="text-align: center">โต๊ด </th>
					                <th style="text-align: center">ราคา</th>
					                
					            </tr>
					        </thead>
					        <tbody ng-repeat="line in customers_line_limit">
					            <tr>
					               
					                <td align="center" style="font-weight: bold">{{ line.number }}</td>
					                <td align="right">{{ line.top_amount }}</td>
					                <td align="right"> {{ line.below_amount }}</td>
					                <td align="right">{{ line.otd_amount }}</td>
					                <td align="right">
					                    {{ line.line_amount_total | number }}
					                </td>
					               
					            </tr>
					          
					        </tbody>
					       
					    </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

</div>