/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Product Selector
function bicProductSelector(elJquery) {
    var oSelect2 = elJquery.select2({
        placeholder: "",
        allowClear: true,
        width: "300px",
        ajax: {
            url: "/product/product-product/product-list",
            processResults: function (data) {
                console.log(data);
                return {results: data.results};
            },
            delay: 250
        },
        templateResult: function (data) {
            // แสดงใน DropdownList
            if (data.default_code) {
                return "[" + data.default_code + "] " + data.text;
            } else {
                return data.text;
            }
        },
        templateSelection: function (data, container) {
            // แสดงในกล่องข้อความ
            if (data.default_code) {
                return "[" + data.default_code + "] " + data.text;
            } else {
                return data.text;
            }

        },
        minimumInputLength: 2,
    });
    return oSelect2;
}