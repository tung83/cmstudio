// JavaScript Document
function operationFrm(id,type)
{
	var frm=document.actionForm;
	switch(type)
	{			
		case "E":
			frm.Edit.value = 1;
            frm.idLoad.value = id;
    		frm.action="";
    		frm.submit();
			break;
		case "D":
            confirm( function(){
                frm.Del.value = 1;
                frm.idLoad.value = id;                    
                frm.submit();
            });
            return false;
			break;
		default:
			return;
	}			
}
function del_all(){
    var frm=document.actionForm;
    confirm(function(){
        var favorite = [];
        $.each($("input[name='checkbox_child']:checked"), function(){            
            favorite.push($(this).val());
        });
        var res = favorite.join(",");
        frm.listDel.value = res;
        frm.submit(); 
    });
    return false;
}
function frm_update(frm,e)
{
	var a=confirm('Bạn có muốn cập nhật record này không?');
	return a;
}


$("#reset").on("click",function(){location.href=location.href;})

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
$("input[type='number']").keyup(function(){
    addCommas(this.value);
})

$("#category").on("change",function(){
    if($("#customer").val()==0) 
    {
        alert("Bạn chưa chọn khách hàng tạo đơn!!");
        $("#category").val(0);
    }
})

$(".qty_change").on("keyup",function(e){
    var qty=$(this).val();
    var id=$(this).attr("id");
    var row=id+'_row';
    var price=$("#"+row).find(".price").data('price');
    var sum=price*qty;
    var total=0;
    $("#"+row).find(".qty").html('<label data-qty="'+qty+'">'+qty+'</lable>');
    $("#"+row).find(".sum").html($.number(sum,0,',','.'));
    $("#"+row).find(".sum").data('sum',sum);
    $(".row_for_total").each(function(){
        total+=$(this).find(".sum").data('sum');      
    });
    $("#total").html('<label data-total="'+total+'">'+$.number(total,0,',','.')+'VNĐ</lable>');
    if(e.keyCode == 8){
       //alert("keypress");
    }
})

