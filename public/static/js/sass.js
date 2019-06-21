/**
 * Created by LJ on 2018/8/1.
 * for HaoYi SAAS
 */
$(function(){
    $(".menu-box .lev1").click(function () {
        $(this).parent().toggleClass("now");
    });
    $(".main-menu div a").click(function(){
        $(".main-menu div a").removeClass("now");
        $(this).addClass("now");
    });
    $(".hy-pub-popup .doCancle,.hy-pub-popup .doClose,hy-pub-popup .close").on("click",function(){
        $(this).parents(".hy-pub-popup").find(".form-reset").click();
        $(this).parents(".hy-pub-popup").hide();
    });
    //关闭弹框
    $(".show-order .order-num").on('click',function(){
        $(".show-order").removeClass("showing");
        $(this).parents(".show-order").toggleClass("showing");
        return false;
    });
    //$(".show-order-cont").on('mouseleave',function () {
    //    $(".show-order").removeClass("showing");
    // });
    $(document).keydown(function(event){
        if (event.keyCode == 13) {
            $('form').each(function() {
                event.preventDefault();
            });
        }
    });
});

$(function(){
    /*预约管理部分*/
    if($(".time-list").length>0){
        var nameBkshow=$("#name").val();
        var dateBkshow=$("#name").attr("data-date");
        $.ajax({ url: "/booking/bookingshow",data:{date:dateBkshow,name:nameBkshow},type: "post", success: function(res){
            if(!res.status){ console.log(res.info)}
            var hourHtmlStr='';//小时
            var minHtmlStr='';//分钟
            var detailHtmlStr='';//日历记事打标
            var docnamelHtmlStr='';//医生名
            var b=res.data;
            console.log(b);
            for (var i=9;i<20;i++){//时间表格
                var nStr='';
                if(i<10){
                    nStr='0'+i;
                }else{
                    nStr=i;
                }
                hourHtmlStr+='<span>'+nStr+'</span>';
                minHtmlStr+='<span id="offset'+nStr+'00">00</span><span id="offset'+nStr+'15">15</span><span id="offset'+nStr+'30">30</span><span id="offset'+nStr+'45">45</span>';
            }
            $('#domHour').html(hourHtmlStr);
            $('#domMin').html(minHtmlStr);
            $('#domMinline').html(minHtmlStr);
            for(i in b){//标记事项
                var bookItemStr='';
                detailHtmlStr+='<ul class="mark">';
                for(var f=0;f<b[i].detail.length;f++){
                    var ntop=document.getElementById('offset'+b[i].detail[f].booking_time_quantum[0]).offsetTop;
                    var nHeight=24*b[i].detail[f].booking_time_quantum.length;
                    if(b[i].detail[f].type==1){
                        var hrefStr='';
                        if(b[i].detail[f].can_edit==1){
                            hrefStr='<a href="/booking/addbooking?booking_id='+b[i].detail[f].id+'"></a>';
                        }
                        bookItemStr+='<li style="top:'+ntop+'px; height: '+nHeight+'px;" data-id="'+b[i].detail[f].id+'"data-type="'+b[i].detail[f].type+'" class="edit'+b[i].detail[f].can_edit+' status'+b[i].detail[f].status+'"><span class="name-cont">'+hrefStr+b[i].detail[f].client_name+'</span><span class="text-cont">'+b[i].detail[f].booking_type+'</span><i class="icon-detail"></i><i class="icon-del do-del">X</i> </li>';
                    }else{
                        bookItemStr+='<li style="top:'+ntop+'px; height: '+nHeight+'px;" data-id="'+b[i].detail[f].id+'"data-type="'+b[i].detail[f].type+'" class="editremark'+b[i].detail[f].can_edit+' remark editRemark">'+b[i].detail[f].remark+'<i class="icon-detail"></i><i class="icon-del do-del">X</i> </li>';
                    }

                }
                docnamelHtmlStr+='<span>'+b[i].nickname+'</span>';
                detailHtmlStr+=bookItemStr;
                detailHtmlStr+='</ul>';
            }
            if(80*b.length>800){
                $('.scrollBox div,#docName div').css("width",(80*b.length)+'px');
            }
            $('#bookShow').html(detailHtmlStr);
            $('#docName div').html(docnamelHtmlStr);
            $('.icon-detail').on('click',function (e) {
                var objParent=$(this).parents('li');
                var id=objParent.attr('data-id'),
                    type=objParent.attr('data-type');
                if($(this).siblings(".detail-fix").length>0){
                    $(this).siblings(".detail-fix").hide().remove();
                    return false;
                }
                //if($(".detail-fix").length<1) {
                var posX = $(this).position().left+10;
                var posY = $(this).position().top+10;
                $.ajax({ type: "post",url: "/booking/ajaxgetbookingdetail",data:{type:type,id:id,is_str:1}, success: function(res){
                    if(objParent.position().top>700){
                        objParent.append("<div class='detail-fix' style='bottom: 0; left:" + posX + "px'>"+res.data+"</div>");
                    }else{
                        objParent.append("<div class='detail-fix' style='top: " + posY + "px; left:" + posX + "px'>"+res.data+"</div>");
                    }

                }});
                //}
                return false;
            });
        }});
    }


    var topNum=200;
    $('.prev').click(function(){
        var nleft=$('#scrollBox').scrollLeft();
        $('#scrollBox,#docName div').animate({'scrollLeft':nleft-=topNum});
    });
    $('.next').click(function(){
        var nleft=$('#scrollBox').scrollLeft();
        $('#scrollBox,#docName').animate({'scrollLeft':nleft+=topNum});
    });
    $('#scrollBox').scroll(function(){
        $('#docName').scrollLeft($('#scrollBox').scrollLeft());
    });
    $('#docName').scroll(function(){
        $('#scrollBox').scrollLeft($('#docName').scrollLeft());
    });
    //打开详情

    $("body").click(function(){
        if($(".detail-fix").length>0){
            $(".detail-fix").remove();
        }
    });
    //删除项操作

    /*//预约管理部分*/
});
$(function(){
    $("#flip").click(function(){
        $("#panel").slideToggle("slow");
    });
    $(".condition .items").on("click",function () {
        $(this).css("background","#f5f5f5");
        $(this).siblings().css("background","#fff");
        $('.deduct-right').toggle();
        $(".submit-header").click(function(){
            $(".deduct-right").hide();
        });

        $(".right-border").click(function(e){
            e.stopPropagation();//阻止冒泡到body
        });

    });
    /*弹框*/
    $(".inner-box .right").on("click",function(){
        $(this).parents(".order-payment-prompt").hide();

    });
    $(".additional-title .close-right").on("click",function(){
        $(this).parents(".order-payment-prompt").hide();

    });

    var html = $(".number-left").text();
    if(html == null|| html==""){
        $(".power-grid #details").hide()
    }

    $("#choice .nav-item").on("click",function () {
        $(this).css("border","1px solid #0099ff");
        $(this).siblings().css("border","1px solid #ccc");
        var n=$("#choice .nav-item").index(this);
        if(n==4){
            $("#many").removeAttr("readonly").css("background","#fff")
        }

    });
    function checkMobile(str) {
        if(str==""){
            $(".err-tip").hide()
        } else{
            var re = /^1\d{10}$/;
            if (re.test(str)) {
                $(".err-tip").hide()
            } else {
                $(".err-tip").show()
            }
        }
    }

});
$(window).click(function(){
    if($(".show-order-cont").length>0){
        $(".show-order-cont").hide("showing");
    }
});
