/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//$(".js-example-basic-single").select2();


function toggleIsCompany() {
    var is_company = $("#respartner-is_company").is(":checked");
    if (is_company) {
        $(".field-respartner-function").hide();
        $(".field-respartner-parent_id").hide();
    } else {
        $(".field-respartner-function").show();
        $(".field-respartner-parent_id").show();
    }
};

toggleIsCompany();

/*
 * On initial
 */
var $country = $("#respartner-country_id");
var country_id = $country.find(":selected").attr('value');

/*
 * Tumbon Select Box
 */

function refreshTumbon(callback) {
    var selected_district_id = $("#respartner-district_id").find(":selected").attr('value');
    $tumbon = $("#respartner-tumbon_id");
    console.log(selected_district_id);
    var url = $tumbon.attr('url');
    $.get(url, {district_id: selected_district_id})
        .success(function (result) {

            console.log(result);
            
            $tumbon.empty();
            $tumbon.append("<option value='' selected>-- --Please Select -- </option>");
            $.each(result, data, function (index, value) {
                $opt = $("<option></option>").text(value.name).val(value.id);
                if(value.id == $("#selected_district_id").val()) {
                    $opt.attr("selected", "selected");
                }
                $tumbon.append($opt);
            });
        });

}

/*
 * District Select Box
 */
function refreshDistrict(callback) {
    var selected_province_id = $("#respartner-province_id").find(":selected").attr('value');
    $district = $("#respartner-district_id");
    console.log(selected_province_id);
    var url = $district.attr('url');
    $.get(url, {province_id: selected_province_id})
            .success(function (result) {
                console.log("refresh district");
                console.log(result);
                //fill province with options
                $district.empty();
                $district.append("<option value='' selected>--Please Select--</option>");
                $.each(result.data, function (index, value) {
                    $opt = $("<option></option>").text(value.name).val(value.id);
                    if (value.id == $("#selected_district_id").val()) {
                        $opt.attr("selected", "selected");
                    }
                    $district.append($opt);
                });
            });
}

/*
 * Province Select Box
 */
function refreshProvince(callback) {
    var $province = $("#respartner-province_id");
    var selected_country_id = $("#respartner-country_id").find(":selected").attr('value');
    console.log(selected_country_id);
    var url = $province.attr('url');
    $.get(url, {country_id: selected_country_id})
            .success(function (result) {
                //fill province with options
                console.log("refresh province");
                $province.empty();
                $province.append("<option value='' selected>--Please Select--</option>");
                $.each(result.data, function (index, value) {
                    $opt = $("<option></option>").text(value.name).val(value.id);
                    if (value.id == $("#selected_province_id").val()) {
                        $opt.attr("selected", "selected");
                    }
                    $province.append($opt);
                });
                //when province change, reset and reload district
                if (callback) {
                    callback();
                }

            });
}

function initial() {
    console.log("modal show");
    toggleIsCompany();
    var $country = $("#respartner-country_id");
    var country_id = $country.find(":selected").attr('value');
    if (country_id != "") {
        refreshProvince(function () {
            refreshDistrict();
        });
    }
}
;

$(document).on('change', '#respartner-country_id', function (e) {
    console.log("change country");
    $("#selected_country_id").attr('value', "");
    $("#selected_province_id").attr('value', "");
    $("#selected_district_id").attr('value', "");
    $("#respartner-province_id").empty().append("<option value='' selected>--Please Select--</option>");
    $("#respartner-district_id").empty().append("<option value='' selected>--Please Select--</option>");
    ;
    refreshProvince();
});

$(document).on('change', '#respartner-province_id', function (e) {
    console.log("change province");
    refreshDistrict();
});

$(document).on('change', '#respartner-district_id', function (e) {
    console.log("change district");
});

$(document).on('change', '#respartner-is_company', function (e) {
    toggleIsCompany();
});

// Initial
// initial();








