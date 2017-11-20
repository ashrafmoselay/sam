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
		var clone = $('.cloneDiv:hidden').clone();
		var i = $('.productList:visible').length;
		clone.find('input.isnew').attr('name','isnew['+i+']');
		clone.show();
		$("#orderList").append(clone);
		initTypeahead();
	});
	$(document).on("input",".orderContainer .cost,.orderContainer .qty",function(e){
		e.preventDefault();
		var parent = $(this).closest('div').parents('.productList');
		var qty = parseInt(parent.find(".qty").val());
		var cost = parseFloat(parent.find(".cost").val());
		var total = qty * cost;
		if(isNaN(total))total=0;
		parent.find(".total").val(total);
		calculateCost();
	});

	$(document).on("input",".orderContainer .paid,.orderContainer .totalCost,.orderContainer .discount,.orderContainer .offer",function(e){
		e.preventDefault();
		var totalCost = parseFloat($(".totalCost").val());
		var discount = parseFloat($(".discount").val());
		if(isNaN(discount))discount=0;
		var offer = parseFloat($(".offer").val());
		if(isNaN(offer))offer=0;
		  var dis_type = $(".dis_type").is(':checked');
		  $(".discount").parent('div').find('label span').remove();
		  if(dis_type){
		   discount = totalCost * (discount/100);
		   discount = parseFloat(discount).toFixed(2);
		   $(".discount").parent('div').find('label').append('<span style="color:red"> '+discount+' ﺝ</span>');
		  }
		var requirdValue = totalCost - discount - offer;
		requirdValue = parseFloat(requirdValue);
		var paid = parseFloat($(".orderContainer .paid").val());
		if(isNaN(paid))paid=0;
		var due = requirdValue - paid;
		if(isNaN(due))due=requirdValue;
		due = due.toFixed(2);
		$(".orderContainer .due").val(due);
	});
 $('.dis_type').bind('change', function () {
  $(".discount").trigger('input');
 });
  $(document).on("change",".unit",function(e){
    var elm = $(this).closest('div.productList').find('input.typeahead');
    if(elm.val()){
      elm.typeahead('lookup').focus();
      setTimeout(function(){ 
        $("ul.typeahead").find("li.active a").trigger("click");
      }, 200);
    }
  });
  $(document).on("input",".modal-body .total,.modal-body .paid",function(e){
   e.preventDefault();
   var due = parseFloat($(".modal-body .total").val()) - parseFloat($(".modal-body .paid").val());
   $(".modal-body .due").val(due);
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
   }
  });
  
 });
  $(document).on("change",".unitclass",function(e){
    e.preventDefault();
    var cost = $(this).find("option:selected").attr("rel");
    $(this).closest('div.productList').find('.originalprice').val(cost);
    $(this).closest('div.productList').find('.qty').trigger('input');
  });
});
function initTypeahead(){
	var path = "{{ route('autocomplete') }}";
	$('input.typeahead').typeahead({
        source:  function (query, process) {
          var unit = $(this.$element).closest('div.productList').find('.unit').val();
        return $.get(path, { query: query,store_id: $('#store_id').val(),unit:unit }, function (data) {
        		
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
          //elm.parents('div.productList').find('.unitclass').attr('price',item.price);
          //elm.parents('div.productList').find('.unitclass').attr('price2',item.price2);
          //elm.parents('div.productList').find('.unitclass').attr('price3',item.price3);
          var unitid = item.unitid.split(",");
          var title = item.title.split(",");
          var cost = item.cost_price.split(",");
          //var price = item.price.split(",");
          //var price2 = item.price2.split(",");
          //var price3 = item.price3.split(",");
          elm.parents('div.productList').find('.unitclass').html("");
          option = "";
          storUnitName = "";
          for(i=0;i<unitid.length;i++){
            if(item.storeID==unitid[i]){
              storUnitName = title[i];
            }
            option += "<option rel='"+cost[i]+"' value = '"+unitid[i]+"'>"+title[i]+"</option>"
          }
          elm.parents('div.productList').find('.unitclass').html(option);
          $(".unitclass").trigger("change");
          var qty = parseFloat(item.quantity).toFixed(2);
          if(isInt(item.quantity)){
            qty = item.quantity;
          }
          elm.closest('div.productList').find('.avilableQty').text("متاح:"+qty+" "+storUnitName);
        },
    });	
}
function calculateCost(){
	var totalCost =0;
	$(".orderContainer .total:visible").each(function() {
		if (!isNaN($(this).val())) {
			totalCost += parseFloat($(this).val());
		}
	});
	totalCost = parseFloat(totalCost);
	if(isNaN(totalCost))totalCost=0;
	$(".orderContainer .totalCost").val(totalCost);
	//$(".paid").val(totalCost);
	$(".orderContainer .paid").trigger('input');
}
function isInt(value) {
  return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseFloat(value))
}
</script>
@stop()