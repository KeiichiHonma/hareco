$(function(){
    $("#futures_search").ajaxForm({
        beforeSubmit : function(){
            $('#btnPc').block({
                message: null,
                overlayCSS:  {
                    backgroundColor: '#ffffff', 
                    opacity:         0,
                    cursor:          false
                }
            });
            $('#boxes').block({
                message: '<img src="/images/loadinfo.net.gif" alt="" />',
                overlayCSS:  {
                    backgroundColor: '#fdfdfd', 
                    opacity:         0.8,
                    cursor:          'wait' 
                },
                css: {
                   backgroundColor: '#fdfdfd',
                   opacity:         0.8,
                   color:'#fff',
                   height:  '0px',
                   width:   '0px',
                   border:  'none'
               }
            });
        },
        success: uploadFormResponse
    });

});
function uploadFormResponse(responseText, statusText){
    //console.log(responseText);
    if(responseText == 'error'){
        $("#breadcrumbs-three").hide();
    }else{
        $("#breadcrumbs-three").show();
        $("#boxes").html(responseText);
    }
    
    $('#btnPc').unblock();
    $('#boxes').unblock();
    return true;
}

$(function(){
    /* カレンダー */
    $('#datepicker').datepicker({
        onSelect: function(dateText, inst){
            var w = $(window).width();if (w <= 640) $("#sp").val(0);
            $("#day_type").val($dayArray.join(","));
            $("#futures_search").submit();
        }
    });

    //チェックボックス
    var $dayArray = new Array();
    var $pageValue = 1;
    
    //クリックした要素にクラス割り当てる
    $('#next').live('click',function() {
        var w = $(window).width();if (w <= 640) $("#sp").val(0);
        $pageValue = $pageValue + 1;
        $("#page").val($pageValue);
        $("#day_type").val($dayArray.join(","));
        $("#futures_search").submit();
    });

    //checkedだったら最初からチェックする
    $('dl.check-group input').each(function(){
        if ($(this).attr('checked') == 'checked') {
            $(this).next().addClass('checked');
            $dayArray.push( $(this).val() );

        }
    });
    //クリックした要素にクラス割り当てる
    $('dl.check-group label').live('click',function() {
        if ($(this).attr('class') == 'checked') {
            //$(this).removeClass('checked').prev('input').removeAttr('checked');
            var attr = $(this).attr('for');
            $("#"+attr).prop("checked", false);
            
            $(this).removeClass('checked');
            var index = $.inArray($("#"+attr).val(), $dayArray);
            $dayArray.splice(index,1);
        }
        else {
            //$(this).addClass('checked').prev('input').attr('checked','checked');
            $(this).addClass('checked');
            var attr = $(this).attr('for');
            $("#"+attr).attr('checked','checked');
            $("#"+attr).prop("checked", true);
            $dayArray.push( $("#"+attr).val() );
        }
        var w = $(window).width();if (w <= 640) $("#sp").val(0);
        $("#day_type").val($dayArray.join(","));
        $("#futures_search").submit();
    });

//ラジオボタン
    var radio = $('dl.radio-group');
    $('input', radio).css({'opacity': '0'})
    //checkedだったら最初からチェックする
    .each(function(){
        if ($(this).attr('checked') == 'checked') {
            $(this).next().addClass('checked');
        }
    });
    //クリックした要素にクラス割り当てる
    $('label', radio).click(function() {
        $(this).parent().parent().each(function() {
            $('label',this).removeClass('checked');
        });
        $(this).addClass('checked');
        var attr = $(this).attr('for');
        $("#"+attr).attr('checked','checked');
        
        var w = $(window).width();if (w <= 640) $("#sp").val(0);
        $("#day_type").val($dayArray.join(","));
        $("#futures_search").submit();
    });

});