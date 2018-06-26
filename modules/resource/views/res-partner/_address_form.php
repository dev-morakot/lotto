<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="address-section" ng-controller="AddressFormController as ctrl">
    <div class="add-form panel panel-default" ng-show="showAddressForm" >

        <div class="panel-body">
            <!-- *Note that novalidate is used to disable browser's native form validation. -->
            <form novalidate name="addrForm" class="addr-form form-horizontal">
                <div class="row">
                    <div class="col-sm-5 form-horizontal">
                        <div class="form-group  field-type" style="display: block;">
                            <label class="control-label col-sm-4">
                                Address Type
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control" ng-model="addr.type">
                                    <option value=""></option>
                                    <option value="billing">Billing</option>
                                    <option value="shipping">Shipping</option>
                                    <option value="mailing">Mailing</option>
                                    <option value="reporting">Reporting</option>
                                    <option value="other">Other</option>
                                </select>
                                <tt>{{addr.type}}</tt>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 form-horizontal">
                        <div class="form-group form-group-sm  field-first_name" style="display: block;">
                            <label class="control-label col-sm-4">
                                First Name
                            </label>
                            <div class="col-sm-8">
                                <input type="text" ng-model="addr.first_name" maxlength="256" class="form-control  ">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5 form-horizontal">
                        <div class="form-group form-group-sm  field-last_name" style="display: block;">
                            <label class="control-label col-sm-4">
                                Last Name
                            </label>
                            <div class="col-sm-8">
                                <input type="text" ng-model="addr.last_name" value="" maxlength="256" class="form-control  ">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 form-horizontal"><div class="form-group form-group-sm  field-company" style="display: block;">
                            <label class="control-label col-sm-4">
                                Company
                            </label>
                            <div class="col-sm-8">
                                <input type="text" ng-model="addr.company_name" value="" maxlength="256" class="form-control  ">
                            </div>
                        </div></div>
                </div>
                <div class="row">
                    <div class="col-sm-5 form-horizontal">
                        <div class="form-group form-group-sm  field-address nf-required-field" style="display: block;">
                            <label class="control-label col-sm-4">
                                Address
                            </label>
                            <div class="col-sm-8">
                                <input type="text" 
                                       ng-model="addr.address1" 
                                       value="" 
                                       maxlength="256" class="form-control bic-required-field"
                                       required>
                            </div>
                        </div></div>
                    <div class="col-sm-5 form-horizontal"><div class="form-group form-group-sm  field-address2" style="display: block;">
                            <label class="control-label col-sm-4">
                                Address2
                            </label>
                            <div class="col-sm-8">
                                <input type="text" ng-model="addr.address2" value="" maxlength="256" class="form-control  ">
                            </div>
                        </div></div>
                </div>
                <div class="row">
                    <div class="col-sm-5 form-horizontal">
                        <div class="form-group form-group-sm  field-city" style="display: block;">
                            <label class="control-label col-sm-4">
                                City
                            </label>
                            <div class="col-sm-8">
                                <input type="text" ng-model="addr.city" value="" maxlength="256" class="form-control  ">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 form-horizontal">
                        <div class="form-group form-group-sm" style="display: block;">
                            <label class="control-label col-sm-4">
                                Postal Code
                            </label>
                            <div class="col-sm-8">
                                <input type="text" 
                                       name="postal_code"
                                       ng-model="addr.postal_code" 
                                       value="" maxlength="256" 
                                       class="form-control bic-required-field"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5 form-horizontal">
                        <div class="form-group form-group-sm  field-country_id nf-required-field" style="display: block;">
                            <label class="control-label col-sm-4">
                                <span class="label-text">
                                    Country
                                </span>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control"
                                        ng-model='addr.country_id'
                                        ng-options="s.id as s.name for s in countries">
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 form-horizontal"><div class="form-group form-group-sm  field-province_id" style="display: block;">
                            <label class="control-label col-sm-4">
                                <span class="label-text">
                                    Province
                                </span>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control"
                                        ng-model='addr.province_id'
                                        ng-options="s.id as s.name for s in provinces| filter:{country_id:addr.country_id}">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5 form-horizontal">
                        <div class="form-group form-group-sm  field-district_id" style="display: block;">
                            <label class="control-label col-sm-4">
                                <span class="label-text">
                                    District
                                </span>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control"
                                        ng-model='addr.district_id'
                                        ng-options="s.id as s.name for s in districts| filter:{province_id:addr.province_id}">
                                </select>
                                <tt>{{addr.district}}</tt>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 form-horizontal">
                        <div class="form-group form-group-sm  field-subdistrict_id" style="display: block;">
                            <label class="control-label col-sm-4">
                                <span class="label-text">
                                    Subdistrict
                                </span>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control"
                                        ng-model='addr.subdistrict_id'
                                        ng-options="s.id as s.name for s in subdistricts| filter:{district_id:addr.district_id}">
                                </select>
                                <tt>{{addr.subdistrict_id}}</tt>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5 form-horizontal">
                        <div class="form-group form-group-sm  field-phone" style="display: block;">
                            <label class="control-label col-sm-4">
                                Phone
                            </label>
                            <div class="col-sm-8">
                                <input type="text" ng-model="addr.phone" value="" maxlength="256" class="form-control  ">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 form-horizontal">
                        <div class="form-group form-group-sm  field-fax" style="display: block;">
                            <label class="control-label col-sm-4">
                                Fax
                            </label>
                            <div class="col-sm-8">
                                <input type="text" ng-model="addr.fax" value="" maxlength="256" class="form-control  ">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="btn-toolbar">
                <a href="#" class="btn btn-success btn-save" 
                   ng-click="saveAddressLine()">Save</a>
                <a href="#" class="btn btn-default btn-cancel" 
                   ng-click="showAddressForm = false">Cancel</a>
            </div>
            <div class="alert alert-warning" 
                 role="alert" 
                 ng-show="addrForm.$invalid">โปรดระบุข้อมูล</div>
        </div>
        
    </div>
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
                <tr>
                    <th></th>
                    <th>Address Type</th>
                    <th>Fistname</th>
                    <th>Lastname</th>
                    <th>Company</th>
                    <th>Address</th>
                    <th>Address2</th>
                    <th>City</th>
                    <th>Postal Code</th>
                    <th>Country</th>
                    <th>Province</th>
                    <th>District</th>
                    <th>Phone</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="line in lines" ng-click="modifyAddressLine(line)">
                    <td><span class="glyphicon glyphicon-remove-circle" ng-click="removeAddressLine(line)"></span></td>
                    <td>{{line.type}}</td>
                    <td>{{line.first_name}}</td>
                    <td>{{line.last_name}}</td>
                    <td>{{line.company_name}}</td>
                    <td>{{line.address1}}</td>
                    <td>{{line.address2}}</td>
                    <td>{{line.city}}</td>
                    <td>{{line.postal_code}}</td>
                    <td>{{line.country.name}}</td>
                    <td>{{line.province.name}}</td>
                    <td>{{line.district.name}}</td>
                    <td>{{line.phone}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <button class="btn btn-xs btn-info" ng-click="openAddressForm()">เพิ่ม</button>
</div>

</div>
