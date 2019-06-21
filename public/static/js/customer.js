/**
 * Created by llf on 2018/9/17.
 */


$(function() {

    // 点击弹窗, 判断是否展示图片
    $('.btn-header').on('click', function (e) {
        var s = $(".img-box").find("img").attr('src');
        if (s) {
            var htmlStr = '<img src="'+ s +'" style="width: 100%; height: 100%">';
            $(".upload-img").html(htmlStr);
            $(".da-img").html(htmlStr);
            $(".zhong-img").html(htmlStr);
            $(".xiao-img").html(htmlStr);
        }
        $('#pop-header').show();
    });

    $(".pop-select-zxs").on('click', function (e) {
        $("#title-comein").removeClass().addClass('zxs').text('选择咨询师');
        $("#pop-select-comein").show();
    });

    $(".pop-select-kfry").on('click', function (e) {
        $("#title-comein").removeClass().addClass('kfry').text('选择开发人员');
        $("#pop-select-comein").show();
    });

    // 到院信息弹框查询
    $(".btn-select-comein").on('click', function (e) {
        $.post('/customer/selectComein', {keywords: $("#keywords-comein").val()}, function (s) {
            console.log(s);
            if (s.status == 'N') {
                alert(s.msg);
                return false;
            }
            var htmlStr = '';
            $.each(s.data, function (i, v) {
                htmlStr += '<tr>';
                htmlStr += '<th><label class="checkbox-inline"><input type="radio" class="sun-box" name="test" id="'+ v.id +'" data-id="'  + v.nickname +'" value=""></label></th>';
                htmlStr += '<th>'+ v.nickname +'</th>';
                htmlStr += '<th>'+ v.sex +'</th>';
                htmlStr += '<th>'+ v.hospital + '-' + v.section + '-' + v.group + '</th>';
                htmlStr += '<th>'+ v.duties +'</th>';
                htmlStr += '<th>'+ v.role_name +'</th>';
                htmlStr += '</tr>';
            });
            $(".dialog-table").find('tbody').html(htmlStr);
        }, 'json');
    });

    // 到院信息弹框保存
    $("#btn-sure-comein").on('click', function (e) {
        var obj = new Object();
        $(".sun-box").each(function (i, v) {
            var s = $(v).prop('checked');
            if (s) {
                obj.id = $(v).attr('id');
                obj.nickname = $(v).attr('data-id');
            }
        });
        if (!obj.id) {
            alert('请选择一个成员');
            return false;
        }
        var status = $("#title-comein").attr('class');
        if (status == 'zxs') {
            $(".consult_id").val(obj.nickname);
            $("input[name='consult_id']").val(obj.id);
        } else {
            $(".develop_id").val(obj.nickname);
            $("input[name='develop_id']").val(obj.id);
        }
        $("#pop-select-comein").hide();
    });


    /**
     * 编辑客户
     */
    $(".btn-edit-customer").on('click', function (e) {
        var formObj = new FormData($("#form-edit-customer")[0]);
        $.ajax({
            url: '/customer/' + $(this).attr('data-id') + '?id=' + $('.curr-customer-id').attr('data-id'),
            data: formObj,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'json',
            success: function (s) {
                if (s.status == 'N') {
                    alert(s.msg);
                    return false;
                }
                alert('编辑成功');
                location.replace(document.referrer);
            }
        });
    });

    // 添加新客户
    $(".btn-save-customer").on('click', function (e) {
        var formObj = new FormData($("#form-customer")[0]);
        $.ajax({
            url: '/customer/add',
            data: formObj,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'json',
            success: function (s) {
                if (s.status == 'N') {
                    alert(s.msg);
                    return false;
                }
                alert('创建成功');
                history.go(-1);
            }
        });
    });

    // 展示pop-select
    $('.select-customer').on('click', function (e) {
        $("#pop-select").show();

    });

    // render select table
    $(".btn-select-cusomer").on('click', function (e) {
        $.post('/customer/selectCustomer', {keywords: $("#keywords").val()}, function (s) {
            if (s.status == 'N') {
                alert(s.msg);
                return false;
            }
            var htmlStr = '';
            $.each(s.data, function (i, v) {
                htmlStr += '<tr>';
                htmlStr += '<th><label class="checkbox-inline"><input type="radio" name="render-radio" class="sun-box" id="'+ v.id +'" data-id="'  + v.phone_crypt + '-' + v.real_name +'" value=""></label></th>';
                htmlStr += '<th>'+ v.id +'</th>';
                htmlStr += '<th>'+ v.real_name +'</th>';
                htmlStr += '<th>'+ v.phone_crypt +'</th>';
                htmlStr += '</tr>';
            });
            $(".dialog-table").find('tbody').html(htmlStr);
        }, 'json');
    });

    // 保存所选客户
    $("#btn-sure-customer").on('click', function (e) {
        var obj = new Object();
        $(".sun-box").each(function (i, v) {
            var s = $(v).prop('checked');
            if (s) {
                obj.id = $(v).attr('id');
                obj.phone = $(v).attr('data-id').split('-')[0];
                obj.name = $(v).attr('data-id').split('-')[1];
            }
        });
        if (!obj.id) {
            alert('请至少选择一个成员');
            return false;
        }
        $("input[name='introducer']").val(obj.name);
        $("input[name='introducer_id_text']").val(obj.id);
        $("input[name='introducer_id']").val(obj.id);
        $("#pop-select").hide();
    });

    /**
     * 展示图片 , type限制 todo
     */
    $("input[name='header']").on('change', function (e) {
        var fileObj = e.target.files[0];
        var reader = new FileReader();
        reader.readAsDataURL(fileObj);
        reader.onload = function(e){
            var data_url = reader.result;
            var htmlStr = '<img id="thumbnail" src="'+data_url+'" style="width: 100%; height: 100%">';
            var daStr = '<img id="da-img" class="da-img" src="'+data_url+'" style="width: 100%; height: 100%">';
            var zhongStr = '<img id="zhong-img" class="zhong-img" src="'+data_url+'" style="width: 100%; height: 100%">';
            var xiaoStr = '<img id="xiao-img"  class="xiao-img" src="'+data_url+'" style="width: 100%; height: 100%">';

            $(".upload-img").html(htmlStr);
            $(".da-img").html(daStr);
            $(".zhong-img").html(zhongStr);
            $(".xiao-img").html(xiaoStr);

            $('#thumbnail').imgAreaSelect({
                aspectRatio: '1:1',
                handles: true,
                fadeSpeed: 200,
                onInit: preview,
                onSelectStart: preview,
                onSelectChange: preview,
                instance: true,
                minWidth: 160
            });
        };
    });

    function preview(img, selection) {
        if (!selection.width || !selection.height) {
            return;
        }
        var imgW = $("#thumbnail").width();
        var imgH = $("#thumbnail").height();
        var scaleX = 120 / selection.width;
        var scaleY = 120 / selection.height;

        var scaleX2 = 50 / selection.width;
        var scaleY2 = 50 / selection.height;

        var scaleX3 = 30 / selection.width;
        var scaleY3 = 30 / selection.height;
        $('.da-img img').css({
            width: Math.round(scaleX * imgW),
            height: Math.round(scaleY * imgH),
            marginLeft: -Math.round(scaleX * selection.x1),
            marginTop: -Math.round(scaleY * selection.y1)
        });
        $('.zhong-img img').css({
            width: Math.round(scaleX2 * imgW),
            height: Math.round(scaleY2 * imgH),
            marginLeft: -Math.round(scaleX2 * selection.x1),
            marginTop: -Math.round(scaleY2 * selection.y1)
        });
        $('.xiao-img img').css({
            width: Math.round(scaleX3 * imgW),
            height: Math.round(scaleY3 * imgH),
            marginLeft: -Math.round(scaleX3 * selection.x1),
            marginTop: -Math.round(scaleY3 * selection.y1)
        });
        // output value
        $('#jcrop-x1').val(selection.x1);
        $('#jcrop-y1').val(selection.y1);
        $('#jcrop-w').val(selection.width);
    }

    // 上传头像
    $(".save-header").on('click', function (e) {
        var file = $("input[name='header']")[0].files[0];
        if (!file || file.size <= 0) {
            alert('请选择图片文件');
            return false;
        }
        var formObj = new FormData();
        formObj.append('header', file);
        $.ajax({
            url: '/customer/uploadHeader?t='+Math.random() + '&x1=' + $('#jcrop-x1').val() + '&y1=' + $('#jcrop-y1').val() + '&w=' + $('#jcrop-w').val(),
            data: formObj,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'json',
            success: function (s) {
                if (s.status == 'N') {
                    alert(s.msg);
                    return false;
                }
                var htmlStr = '<img src="'+ s.data.src +'" style="width: 100%; height: 100%">';
                var hideStr = '<input type="hidden" name="avatar" value="' + s.data.path + '">';
                $(".input-hide-header").html(hideStr);
                $(".img-box").html(htmlStr);
                $('#pop-header').hide();
                alert('头像上传成功');
            }
        });
    });

    /** 联动相关 **/
    // $("input[name='age']").on('change', function (e) {
    //     var reg = /^\d+$/;
    //     var age = $(this).val();
    //     var plugin = $("input[name='birthday']").val();
    //     var s = reg.test(age);
    //     if (s && age <= 200 && plugin) {
    //         var dateObj = new Date();
    //         var currYear = dateObj.getFullYear();
    //         var m = plugin.split('-')[1];
    //         var d = plugin.split('-')[2];
    //         $("input[name='birthday']").val(currYear-age + '-' + m + '-' + d);
    //     }
    // });

    $(".doClose").click(function (e) {
        $(".imgareaselect-outer").hide();
        $(".imgareaselect-selection").hide();
        $(".imgareaselect-handle").hide();
        $(".imgareaselect-border4").hide();
        $(".imgareaselect-border3").hide();
        $(".imgareaselect-border2").hide();
        $(".imgareaselect-border1").hide();
    });

    $('.order-num').click(function(){
        var order_id = $(this).attr('data-id');
        if(order_id > 0){
            $.post("/order/getordergoods", {"order_id": order_id}, function (s) {
                if (s.status) {
                    $('.tr-order-'+order_id).html(s.data);
                    $('.show-order-'+order_id).show();
                }
            });
        }
    });

    $("#province").on('change', function (e) {
        $.get('/customer/getAreaData?t'+ Math.random(), {pid: $(this).val(), type: 2}, function(s) {
            if (s.status == 'Y' && s.data.length > 0) {
                var html = '';
                $.each(s.data, function (i, v) {
                    html += '<option value="' + v.id + '">' + v.name + '</option>';
                });
                $("#city").html(html);
            }
        }, 'json');

    });

    $("select[name='source']").on('change', function (e) {
        if ($(this).val() == 4) {
            $("#introducer_id").show();
        } else {
            $("input[name='introducer_id']").val('');
            $("#introducer_id").hide();
        }
    });


    // 一堆js detail操作
    $('.btn-detail-booking').on('click', function () {
        var obj = $.parseJSON($('.detail-' + $(this).parents('tr').attr('id')).text());
        $('.common-book-status').text(obj.status_text);
        if (obj.status == 0) {
            $('.common-book-status').text(obj.status_text);
            $('.common-book-isshow').show();
        }
        $('.common-common-id').text(obj.client_id);
        $('.common-common-name').text(obj.real_name);
        $('.common-common-phone').text(obj.phone);
        $('.common-common-sex').text(obj.sex);
        $('.common-common-source').text(obj.source);
        $('.common-common-level').text(obj.level);
        $('.common-book-type').text(obj.type_id);
        $('.common-book-part').text(obj.part_id);
        $('.common-book-remark').text(obj.remark);
        $('.common-book-hospital').text(obj.hospital);
        $('.common-book-doctor').text(obj.doctor_name);
        $('.common-book-come-time').text(obj.booking_date);
        $('.common-book-option').text(obj.insert_name);
        $('.common-book-time').text(obj.insert_time);
        $('.common-book-abort').text(obj.cancel_reason);
        $('.pop-common-dialog').show();
    });

    $('.btn-detail-consult').click(function () {
        var obj = $.parseJSON($('.detail-' + $(this).parents('tr').attr('id')).text());
        $('.common-common-id').text(obj.client_id);
        $('.common-common-name').text(obj.real_name);
        $('.common-common-phone').text(obj.phone);
        $('.common-common-sex').text(obj.sex);
        $('.common-common-source').text(obj.source);
        $('.common-common-level').text(obj.level);
        $('.common-consult-channel').text(obj.consult_channel);
        $('.common-consult-type').text(obj.consult_type);
        $('.common-consult-remark').text(obj.remark);
        $('.common-consult-time').text(obj.insert_time);
        $('.common-consult-option').text(obj.consult_user);
        $('.common-consult-hospital').text(obj.hospital);
        $('.pop-common-dialog').show();
    });

    $('.btn-detail-visit').click(function () {
        var obj = $.parseJSON($('.detail-' + $(this).parents('tr').attr('id')).text());
        $('.common-visit-status').text(obj.status);
        $('.common-common-id').text(obj.client_id);
        $('.common-common-name').text(obj.real_name);
        $('.common-common-phone').text(obj.phone);
        $('.common-common-sex').text(obj.sex);
        $('.common-common-source').text(obj.source);
        $('.common-common-level').text(obj.level);
        $('.common-visit-name').text(obj.name);
        $('.common-visit-plantime').text(obj.plan_time);
        $('.common-visit-realtime').text(obj.real_time);
        $('.common-visit-remark').text(obj.remark);
        $('.common-visit-result').text(obj.visit_result);
        $('.common-visit-insert').text(obj.insert_time);
        $('.common-visit-type').text(obj.visit_type);
        $('.common-visit-hospital').text(obj.hospital);
        $('.common-visit-user').text(obj.create_id);

        $('.pop-common-dialog').show();
    });

    // 治疗记录详情
    $('.btn-cure-detail').click(function () {
        var d = $.parseJSON($(this).parents('tr').find('.td-data').html());
        $('.cure-client-status').text(d.status);
        $('.cure-client-real_name').text(d.real_name);
        $('.cure-client-sex').text(d.sex);
        $('.cure-client-phone').text(d.phone);
        $('.cure-client-hospital_name').text(d.hospital_name);
        $('.cure-client-remark').text(d.remark);
        $('.cure-client-beauty_time').text(d.beauty_time);
        $('.cure-client-cure_time').text(d.cure_time);
        $('.cure-client-part_type').text(d.part_type);
        $('.cure-client-source').text(d.source);
        $('.cure-client-goods_order_id').text(d.goods_order_id);
        $('.cure-client-sku_name').text(d.sku_name);
        $('.cure-client-doctor_name').text(d.doctor_name);
        $('.cure-client-use_number').text(d.use_number);
        $('.cure-client-pair_name').text(d.pair_name);
        $('.cure-client-aide_name').text(d.aide_name);
        $('.cure-client-hocus_name').text(d.hocus_name);
        $('.cure-client-create_user').text(d.create_name);
        $('.pop-cure-detail').show();
    })

    // 到院记录详情
    $('.btn-reception-detail').click(function () {
        var d = $.parseJSON($(this).parents('tr').find('.td-data').html());
        $('#pop-comein-status').text(d.status);
        $('#pop-comein-real_name').text(d.real_name);
        $('#pop-comein-sex').text(d.sex);
        $('#pop-comein-phone').text(d.phone);
        $('#pop-comein-hospital_name').text(d.hospital_name);
        $('#pop-comein-remark').text(d.remark);
        $('#pop-comein-insert_time').text(d.insert_time);
        $('#pop-comein-part_type').text(d.part_type);
        $('#pop-comein-source').text(d.source);
        $('#pop-comein-deal').text(d.is_deal + (d.deal_remark ? '/' + d.deal_remark : ''));
        $('#pop-comein-create_user').text(d.create_user);
        $('#pop-comein-consult_name').text(d.consult_name);
        $('#pop-comein-detail').show();
    });

});
