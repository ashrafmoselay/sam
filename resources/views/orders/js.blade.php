
@section('javascript')
<script type="text/javascript">
$(document).ready(function(){
    initTypeahead();
 $(document).on("click",".btn-danger",function(e){
  e.preventDefault();
  $(this).closest('div').parent('div').remove();
  calculateCost();
 });
 $(document).on("click",".addnewproducts",function(e){
  e.preventDefault();
  var clone = $('.cloneDiv').html();
  $(".productList:last:visible").after(clone);
  initTypeahead();
 });
 $(document).on("input",".price,.qty",function(e){
  e.preventDefault();
  var parent = $(this).closest('div').parents('.productList');
  var qty = parseInt(parent.find(".qty").val());
  var price = parseFloat(parent.find(".price").val());
  var cost = parseFloat(parent.find(".originalprice").val());
  var total = qty * price;
  if(isNaN(total))total=0;
     total = parseFloat(total).toFixed(2);
  parent.find(".total").val(total);
  calculateCost();
 });
 $(document).on("input",".modal-body .total,.modal-body .paid",function(e){
   e.preventDefault();
   var due = parseFloat($(".modal-body .total").val()) - parseFloat($(".modal-body .paid").val());
   $(".modal-body .due").val(due);
 });
 $(document).on("input",".orderContainer .paid,.orderContainer .totalCost,.discount",function(e){
  e.preventDefault();
  var totalCost = parseFloat($(".totalCost").val());
  var paid = parseFloat($(".orderContainer .paid").val());
  var discount = parseFloat($(".discount").val());
  if(isNaN(paid))paid=0;
  if(isNaN(discount))discount=0;
  var dis_type = $(".dis_type").is(':checked');
  $(".discount").parent('div').find('label span').remove();
  if(dis_type){
   discount = totalCost * (discount/100);
   discount = parseFloat(discount).toFixed(2);
   $(".discount").parent('div').find('label').append('<span style="color:red"> '+discount+' ﺝ</span>');
  }
  var due = totalCost - paid - discount;
  if(isNaN(due))due=$(".totalCost").val();
     due = parseFloat(due).toFixed(2);
  $(".orderContainer .due").val(due);
  if($(".newbalance").length){
    var newbalance = parseFloat($(".oldbalnace").val()) + parseFloat(due);
    $(".newbalance").val(newbalance);
  }
 });
 $('.dis_type').bind('change', function () {
  $(".discount").trigger('input');
 });
 $(document).on("click",".profit",function(e){
  e.preventDefault();
  var btn = $(this);
  var profit = 0;
  $(".orderContainer .productList").each(function(){
   var cost = $(this).find('input.originalprice').val();
   var qty = $(this).find('input.qty').val();
   var price = $(this).find('input.price').val();
   profit += qty*price - qty*cost;
  });
  swal({
   title:'الربح', text:"ربحك من هذه الفاتورة = "+ parseFloat(profit).toFixed(2)+" ﻡ.ﺝ",type:"success",confirmButtonText: "تمام",
  });
  //btn.html();
 });
  $(document).on("change","#clientsList",function(e){
    e.preventDefault();
    var balance = $(this).find("option:selected").attr("balance");
    $(".oldbalnace").val(balance);

  });
 $(document).on("submit",".modal-body form",function(e){
  e.preventDefault();
  var form = $(this);
  var url_ = form.attr('action');
  $.ajax({
   url:url_,
   type:'POST',
   data:form.serialize(),
   success:function(result){
    $("#clientsList").html(result);
    $(".close").trigger("click");
    $("#clientsList option:last").attr("selected", "selected");
    var text = $("#clientsList option:last").text();
    $('.bootstrap-select .filter-option').text(text);
    $("#clientsList").trigger("change");
   }
  });

 });
 sure = false;
 $(document).on("keydown","input",function(event){
     if(event.keyCode == 13) {
        event.preventDefault();
        return false;
     }
   });
 $(document).on("click",".orderForm .submitform",function(e){
  e.preventDefault();
  var form = $(".orderForm");
  //alert('here');
  if(!sure){
   var show = false;
   var validqty = true;
   var generalvalid = true;
   var items = "\n";
   $("div.productList:visible").each(function() {
       var cost = parseFloat($(this).find('input.originalprice').val());
       var price = parseFloat($(this).find('input.price').val());
       var afqty = parseFloat($(this).find('span.afqty').text());
    if(price < cost){
     show = true;
     items += "\n"+$(this).find('input.typeahead').val() + "     "+cost+" ----- "+price;
    }
    if(isNaN(cost) || isNaN(cost)){
     generalvalid = false;
     return false;
    }
       if(afqty<0){
     validqty = false;
     return false;
       }
   });
   if(!validqty){
       swal({
    title:'خطأ !', text:'المية غير متاحة لبعض المنتجات',type:"error",confirmButtonText: "ﻡﺎﻤﺗ",
    });
    return false;
   }else if(!generalvalid){

       swal({
    title:'خطأ !', text:'ﺢﻴﺤﺻ ﻞﻜﺸﺑ ﺓﺭﻮﺗﺎﻔﻟا ﺕﺎﻧﺎﻴﺑ ﺔﺑﺎﺘﻛ ءﺎﺟﺮﺑ',type:"error",confirmButtonText: "ﻡﺎﻤﺗ",
    });
   }else if(show){
    swal({
     title:'إنتبه!', text:'سعر البيع أقل من سعر التكلفة لبعض المنتجات'+items,type:"warning",confirmButtonText: "تمام",
    });
   }else sure = true;
  }
  if(sure){
   form.submit();
  }
  $(".submitform").removeClass("submitform");
  sure = true;
 });
  $(document).on("change",".storeName",function(e){
    var elm = $(this).closest('div.productList').find('input.typeahead');
    if(elm.val()){
      elm.typeahead('lookup').focus();
      setTimeout(function(){
        $("ul.typeahead").find("li.active a").trigger("click");
      }, 200);
    }
  });
  $(document).on("change",".unitclass",function(e){
    e.preventDefault();
    var cost = $(this).find("option:selected").attr("rel");
    cost = parseFloat(cost).toFixed(2);
    var price = $(this).find("option:selected").attr("price");
    //var selval = $("#clientsList").val();
    //var clientType = $("select option[value='"+selval+"']").attr('rel');
    var clientType = $("#clientsList").find("option:selected").val();
    //console.log(clientType);
    if(clientType==2){
      price = $(this).find("option:selected").attr("price2");
    }else if(clientType==3){
      price = $(this).find("option:selected").attr("price3");
    }
    $(this).closest('div.productList').find('.originalprice').val(cost);
    $(this).closest('div.productList').find('input.price').val(price);
    $(this).closest('div.productList').find('.qty').trigger('input');

  });

});
function initTypeahead(){
 //var store_id = $(this).parents('div.productList').find('.storeName').val();
 var path = "{{ route('autocomplete') }}";
 $('input.typeahead').typeahead({
        source:  function (query, process) {
          var store = $(this.$element).closest('div.productList').find('.storeName').val();
          var unit = $(this.$element).closest('div.productList').find('.unit').val();
        return $.get(path, { query: query,store_id: store,unit:unit }, function (data) {

                return process(data);
            });
        },

     updater:function (item) {
         return item;
     },

        afterSelect: function (item) {

          var elm = $(this.$element);

          elm.parents('div.productList').find('.unitclass').attr('cost',item.cost_price);
          elm.parents('div.productList').find('.unitclass').attr('unitid',item.unitid);
          elm.parents('div.productList').find('.unitclass').attr('title',item.title);
          elm.parents('div.productList').find('.unitclass').attr('price',item.price);
          elm.parents('div.productList').find('.unitclass').attr('price2',item.price2);
          elm.parents('div.productList').find('.unitclass').attr('price3',item.price3);
          var unitid = item.unitid.split(",");
          var title = item.title.split(",");
          var cost = item.cost_price.split(",");
          var price = item.price.split(",");
          var price2 = item.price2.split(",");
          var price3 = item.price3.split(",");
          elm.parents('div.productList').find('.unitclass').html("");
          option = "";
          storUnitName = "";
          for(i=0;i<unitid.length;i++){
            if(item.storeID==unitid[i]){
              storUnitName = title[i];
            }
            option += "<option price='"+price[i]+"' price2='"+price2[i]+"' price3='"+price3[i]+"' rel='"+cost[i]+"' value = '"+unitid[i]+"'>"+title[i]+"</option>"
          }
          elm.parents('div.productList').find('.unitclass').html(option);
          $(".unitclass").trigger("change");
          var qty = parseFloat(item.quantity).toFixed(2);
          if(isInt(item.quantity)){
            qty = item.quantity;
          }
          elm.closest('div.productList').find('.avilableQty').text("متاح:"+qty+" "+storUnitName);
          /*elm.closest('div.productList').find('input.originalprice').val(item.cost);
          elm.closest('div.productList').find('input.price').val(price);
          $(".price").trigger('input');*/
        },
    });

}
function calculateCost(){
 var totalCost =0;
 $(".total:visible").each(function() {
  if (!isNaN($(this).val())) {
   totalCost += parseFloat($(this).val());
  }
 });
 totalCost = parseFloat(totalCost).toFixed(2);
 $(".totalCost").val(totalCost);
 //$(".paid").val(0);
 $(".paid").trigger("input");
}
function isInt(value) {
  return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseFloat(value))
}
</script>
@stop()