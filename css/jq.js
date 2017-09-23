$(function () {

$('.tooltipactive').tooltip()
$('.dropdown-login input').click(function (event) {
  event.stopPropagation();
});

$('#cinemamod').click(function () {
        if($("#cinemabg").is(":hidden")){
            $("#cinemabg").show();
            $("#cinemamod").text("ON");
        }else{
            $("#cinemabg").hide();
            $("#cinemamod").text("OFF");
        }
});

$('.favadd').click(function () {
		var id = $(this).attr("data-idserial");
        var status = $(this).attr("data-sfaved");
		$.ajax({
            type: "POST",
            data: 'idserial='+id,
            url: "/inc/function/favorite.php",
			cache: false,
			success: function(data){
                if(status == 0){
                    $('#favadd').html("Odstrániť z obľúbených");
                    $('#favadd').attr("data-sfaved","1");
                    $('.favadd').css("border-color","#e67e22");
                }else{
                    $('#favadd').html("Pridať do obľúbených");
                    $('#favadd').attr("data-sfaved","0");
                    $('.favadd').css("border-color","#2ecc71");
                }
            }
		});
});

$('#showfavorited').click(function () {
        if($("#favoritedlist").is(":hidden")){
            $("#favoritedlist").slideDown();
        }else{
            $("#favoritedlist").slideUp();
        }
});


});