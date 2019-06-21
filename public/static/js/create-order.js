$(function(){

    $(".expurgate").click(function() {
        $(this).parent().remove();
    });

    $(".hover-table tr #inputPane-one").click(function() {
        $(this).parent().remove();
    });
    /*加减*/
    $(document).ready(function(){
        $('.add-count').each(function(){
            var _this=$(this);
            var add=$(_this).find(".j_add");//添加数量
            var reduce=$(_this).find(".j_minus");//减少数量
            var num=1;//数量初始值
            var num_txt=$(_this).find(".j_input");//接受数量的文本框
            $(add).click(function(){
                num = $(num_txt).val();
                num++;
                num_txt.val(num);
                //ajax代码可以放这里传递到数据库实时改变总价
            });
            /*减少数量的方法*/
            $(reduce).click(function(){
                //如果文本框的值大于0才执行减去方法
                num =  $(num_txt).val();
                if(num >0){
                    //并且当文本框的值为1的时候，减去后文本框直接清空值，不显示0
                    if(num==1)
                    { num--;
                        num_txt.val("1");
                    }
                    //否则就执行减减方法
                    else
                    {
                        num--;
                        num_txt.val(num);
                    }

                }
            });
        })
    });

    $(document).ready(function(){
        $("#flip").click(function(){
            $("#panel").slideToggle("slow");
        });
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

    $("#choice .nav-item").on("click",function () {
        $(this).css("border","1px solid #0099ff");
        $(this).siblings().css("border","1px solid #ccc");
        var n=$("#choice .nav-item").index(this);
        if(n==4){
            $("#many").removeAttr("readonly").css("background","#fff")
        }

    });

  /*  $(".list").on("click",function() {
        var index = parseInt($(this).parent().children().eq(0).text())+parseInt(1);
        console.log(index);
        var H= parseInt($(this).height());
        // $('.conceal').toggle()
        console.log(index * H);
        $(".conceal").css("top",index*H+"px").toggle()
    });*/

});

function particularsOne(obj) {
    $(obj).click(function(){
        $(".particulars-top").toggle();
    });
}
