var nowTemp = new Date(); 
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    
/** Checkin */

var checkin = $('#arrivo_0').datepicker({
    
    format: 'dd/mm/yyyy', 
    weekStart: 1,
    language: locale,
    onRender: function(date) {
        return date.valueOf() < now.valueOf() ? 'disabled' : '';
    }
    
}).on('changeDate', function(ev) {
    
    var newDate = new Date(ev.date);
    var newDateTomorrow =  new Date(ev.date);
    
    if (ev.date.valueOf() > checkout.date.valueOf()) {
        
        newDateTomorrow.setDate(newDateTomorrow.getDate() + 1);
        $("#partenza_button_0").text( ("0" + newDateTomorrow.getDate()).slice(-2) + "/" + ("0" + (newDateTomorrow.getMonth() + 1)).slice(-2) + "/" + newDateTomorrow.getFullYear()  );
        console.log(newDateTomorrow);
        checkout.setValue(newDateTomorrow);
        
    } else {
        
        var dateTomorrow = $("#partenza_0").val();
        dateTomorrow = dateTomorrow.substr(3,2) + "/" + dateTomorrow.substr(0,2)  +"/" + dateTomorrow.substr(6,4);
        newDateTomorrow = new Date( dateTomorrow  );
        
    }
    
    $("#arrivo_button_0").text( ("0" + newDate.getDate()).slice(-2) + "/" + ("0" + (newDate.getMonth() + 1)).slice(-2) + "/" + newDate.getFullYear()  );
    $("#arrivo_button_0").removeClass("selected");
    
    checkin.hide();
    checkin.update([newDate, dateTomorrow]);
    checkout.update([newDate, dateTomorrow]);
    
    $('#partenza_0')[0].focus();
    $("#partenza_button_0").trigger("click");
    
}).data('datepicker');	

/* Checkout */

checkout = $('#partenza_0').datepicker({

    format: 'dd/mm/yyyy', 
    weekStart: 1,
    language: locale,
    onRender: function(date) {
        return (date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '');
    }
    
}).on('changeDate', function(ev) {
    
    var newDate = new Date(ev.date);
    $("#partenza_button_0").text(("0" + newDate.getDate()).slice(-2) + "/" + ("0" + (newDate.getMonth() + 1)).slice(-2) + "/" + newDate.getFullYear()  );
  
    checkout.hide();
    checkin.update();
    checkout.update();
    
}).data('datepicker');

/** Focus sulle date */

$('#arrivo_0').bind("focus", function () {
    
    $("#daterange_0").addClass("left").removeClass("right");
    $("#partenza_0" ).closest(".data_picker").addClass("opacity");	
    
});
    
$("#partenza_0").bind ("focus", function () {
    
    $("#daterange_0").addClass("right").removeClass("left");
    $("#arrivo_0" ).closest(".data_picker").addClass("opacity");
    
});

/** Click sui bottoni */

$("#arrivo_button_0").bind("click", function (e) {
    
    e.preventDefault();
    checkin.show();
    $(this).addClass("selected");
    $("#date_info_0").hide();
    $("#daterange_0").addClass("left").removeClass("right");
    $("#partenza_0" ).closest(".data_picker").addClass("opacity");			

});

$("#partenza_button_0").bind("click",function (e) {
    
    e.preventDefault();
    checkout.show();
    $(this).addClass("selected");
    $("#date_info_0").hide();
    $("#daterange_0").addClass("right").removeClass("left");
    $("#arrivo_0" ).closest(".data_picker").addClass("opacity");
    
});

function changeMacrolocalitaSearchFirst(obj) {

    var action = $("#macrolocalita option:selected").attr("data-url");
    $("#search_first").attr("action", "/" + action);

}
