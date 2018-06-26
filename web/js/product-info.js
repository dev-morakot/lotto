/**
 * Deprecated.
 * 3 more bugs and a lot of code lines.
 * 
 */

var totalRow = 0;
    var currentProductId = null;

    var demo_ID = $("#product_id").val();
    var lbl = $("input[name=lbl_txt]").val();

    if(lbl == "") {
        var demo_ID = null;
        var lbl = null;
    }

    if(demo_ID !== null) {
        var demo_ID = $("#product_id").val();
        productID(demo_ID);
    }

    $(document).ready(function() {
        $("#not_id").show();
        $("#have_id").hide();
    });

    function addRow(){

        var partner_id = $("#SelectPartner").val();
        var inputPartnerName = $("#inputPartnerName").val();
        var name_sup = $("input[name=name_sup]").val();
        var code_sup = $("input[name=code_sup]").val();
        var tablePartner = $("#tablePartner");
        var html = "";

        if(currentProductId != null) {
            $.ajax({
                url: '/product/product-product/product-info-add',
                data: {
                    partner_hidden: partner_id,
                    product_name_sup: name_sup,
                    product_code_sup: code_sup,
                    id: currentProductId
                },
                type: "POST",
                success: function(data) {

                    html += "<tr>";
                    html += "<td style='text-align: center'> &nbsp;</td>";
                    html += "<td><input type='hidden' name='partner_hidden[]' value='" + partner_id +"'>" + inputPartnerName  +"</td>";
                    html += "<td><input type='hidden' name='product_code_sup[]' value='" + code_sup + "'>" + code_sup +"</td>";
                    html += "<td><input type='hidden' name='product_name_sup[]' value='" + name_sup + "'>" + name_sup +"</td>";
                    html += "<td align='center'><a href='javascript:void(0)' onclick='return removeRow(this,"+ totalRow +", " + data + ")' class='btn btn-danger'><i class='glyphicon glyphicon-remove'></i></a></td>";
                    html += "</tr>";

                    tablePartner.find("tbody").append(html);
                    refreshNumber();
                }
            });
        } else {

            html += "<tr>";
            html += "<td style='text-align: center'> &nbsp;</td>";
            html += "<td><input type='hidden' name='partner_hidden[]' value='" + partner_id +"'>" + inputPartnerName  +"</td>";
            html += "<td><input type='hidden' name='product_code_sup[]' value='" + code_sup + "'>" + code_sup +"</td>";
            html += "<td><input type='hidden' name='product_name_sup[]' value='" + name_sup + "'>" + name_sup +"</td>";
            html += "<td align='center'><a href='javascript:void(0)' onclick='return removeRow(this,"+ totalRow +", null)' class='btn btn-danger'><i class='glyphicon glyphicon-remove'></i></a></td>";
            html += "</tr>";

            tablePartner.find("tbody").append(html);
            refreshNumber();
        }

        return false;

    }

    function removeRow(me , index , id){

        $(me).parents('tr').remove();
        refreshNumber();

        if(currentProductId != null) {
            $(me).parents('tr').remove();
            refreshNumber();

            $.ajax({
                url: '/product/product-product/partner-delete',
                data: {
                    id: id
                }
            });
        }
    }

    function refreshNumber(){
        clearForm();
        renderRowNumber();
    }

    function clearRow(){
        $("#tablePartner tbody tr").remove();
    }

    function clearForm(){
        $("#SelectPartner").val("");
        $("#inputPartnerName").val("");
        $("input[name=code_sup]").val("");
        $("input[name=name_sup]").val("");
    }

    function renderRowNumber(){
        var row = 0;
        var tablePartner = $("#tablePartner");
        tablePartner.find("tbody tr").each(function(i) {
            var cell = $(this).find("td:first");
            cell.text(i + 1);
        });
    }



    function productID(id){
        clearRow();

        $.ajax({
            url: '/product/product-product/find-product-id',
            data: {
                id: id 
            },
            dataType: 'json',
            success: function(data) {

                partnerInfo(id);
                currentProductId = id;
            }
        });
    }

    function partnerInfo(id) {
        $.ajax({
            url: '/product/product-product/partner-info',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                if(data != null) {
                    $.each(data, function(index, item) {

                        var tr = "";
                        tr += "<tr>";
                        tr += "<td onclick='return editRow("+ item.id +")' align='center'></td>";
                        tr += "<td onclick='return editRow("+ item.id +")'><input type='hidden' name='partner_hidden[]' value='" + item.partner_id + "'>" + item.partner_name + "</td>";
                        tr += "<td onclick='return editRow("+ item.id +")'><input type='hidden' name='product_code_sup[]' value='" + item.product_code_sup + "'>" + item.product_code_sup + "</td>";
                        tr += "<td onclick='return editRow("+ item.id +")'><input type='hidden' name='product_name_sup[]' value='" + item.product_name_sup + "'>" + item.product_name_sup + "</td>";
                        tr += "<td align='center'><a href='javascript:void(0)' onclick='return removeRow(this," + totalRow +", " + item.id +")' class='btn btn-danger'><b class='glyphicon glyphicon-remove'></b></a></td>";
                        tr += "</tr>";

                        $("#tablePartner").find("tbody").append(tr);
                        totalRow++;
                    });

                    refreshNumber();
                }
            }
        });
    }

    function editRow(id){
       
        $.ajax({
            url: '/product/product-product/find-supplier-info',
            data: {
                id: id 
            },
            contentType: "application/json",
            dataType: 'json',
            success: function(data) {

                var tr = $("#tablePartner").find("tr");

                if(tr) {

                    var  $opt = $("<option selected></option>").val(data[0].partnerid).text(data[0].name);
                    $("#SelectPartner").append($opt).trigger("select2:close");
                    $("#inputPartnerName").val(data[0].name);
                    $("#inputPartnerID").val(data[0].partnerid);

                    $("input[name=id]").val(data[0].id);
                    $("input[name=code_sup]").val(data[0].product_code_sup);
                    $("input[name=name_sup]").val(data[0].product_name_sup);

                    $("#have_id").show();
                    $("#not_id").hide();
                }
            }
        });
    }

    function addEditRow(){

        var id = $("input[name=id]").val();
        var product_id = $("input[name=lbl_txt]").val();
        var partner_id = $("#SelectPartner").val();
        var code_sup = $("input[name=code_sup]").val();
        var name_sup = $("input[name=name_sup]").val();

        $.ajax({
            url: '/product/product-product/partner-line-edit-row',
            data: {
                id: id,
                product_id: product_id,
                partner_id: partner_id,
                code_sup: code_sup,
                name_sup: name_sup
            },
            type: "POST",
            dataType: 'json',
            success: function(data) {

                productID(data);
                $("#not_id").show();
                $("#have_id").hide();
                

            }
        });
    }