/**
 * Created by llf on 2018/11/5.
 */

$(function () {
    /**
     * 获取当前table指定的td内容
     *
     * @param tdIndex 从0开始
     * @param trIndex 从1开始
     */
    function tableDataPicker (tdIndex, trIndex) {
        // var e = event;
        tdIndex = (tdIndex == undefined) ? 0 : tdIndex;

        trIndex = (trIndex == undefined) ? $(this).parents('tr').index()+1 : trIndex;
        // var tableObj = $(e.target).parents('table');
        var tableObj = $(this).parents('table');
        return tableObj.find('tr:eq('+ trIndex +') td:eq('+ tdIndex +')').html();
    }

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
        $('#pop-comein-consult_desc').text(d.consult_desc);
        $('#pop-comein-detail').show();
    });

    // 治疗记录详情
    $('.btn-cure-detail').click(function () {
        var d = $.parseJSON($(this).parents('tr').find('.td-data').html());
        console.log(d);
        $('.cure-client-status').text(d.status);
        $('.cure-client-real_name').text(d.real_name);
        $('.cure-client-sex').text(d.sex);
        $('.cure-client-phone').text(d.phone);
        $('.cure-client-hospital_name').text(d.hospital_name);
        $('.cure-client-remark').text(d.remark);
        $('.cure-client-cure_time').text(d.cure_time);
        $('.cure-client-beauty_time').text(d.beauty_time);
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

    // 治疗记录编辑保存
    $('.btn-save-cure-edit').click(function () {
        var formObj = new FormData($('.form-edit-cure')[0]);
        $.ajax({
            url: '/reception/editCureLog',
            data: formObj,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'json',
            success: function (s) {
                alert(s.msg);
                if (s.status == 'N') {
                    return false;
                }
                window.location.reload();
            }
        });
    })

    // 治疗记录编辑弹窗
    $('.btn-cure-edit').click(function () {
        var d = $.parseJSON($(this).parents('tr').find('.td-data').html());
        $('.pop-cure-goods_order_id').text(d.goods_order_id);
        $('.pop-cure-sku_name').text(d.sku_name);
        $('.pop-cure-use_number').text(d.use_number);
        $('.pop-cure-remark').text(d.remark);
        if (d.doctor_id > 0) { //医生
            $('.pop-cure-doctor_id').val(d.doctor_id);
            $('.pop-cure-doctor_name').val(d.doctor_name);
        }
        if (d.pair_name) { // 配台
            $('.pair_id').val(d.pair_id);
            $('.pop-cure-pair_name').val(d.pair_name);
        }
        if (d.patrol_name) { // 巡回
            $('.patrol_id').val(d.patrol_id);
            $('.pop-cure-patrol_name').val(d.patrol_name);
        }
        if (d.hocus_id > 0) { // 麻醉
            $('.pop-cure-hocus_id').val(d.hocus_id);
            $('.pop-cure-hocus_name').val(d.hocus_name);
        }
        if (d.aide_id > 0) { // 助理
            $('.pop-cure-aide_id').val(d.aide_id);
            $('.pop-cure-aide_name').val(d.aide_name);
        }
        $('.pop-cure-id').val(d.id);
        $('.pop-cure-edit').show();
    })

    // 保存确认到院
    $('.btn-save-besure-comein').on('click', function () {
        var formObj = new FormData($('.form-besure-comein-wraper')[0]);
        $.ajax({
            url: '/reception/beSureComeIn',
            data: formObj,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'json',
            success: function (s) {
                alert(s.msg);
                if (s.status == 'N') {
                    return false;
                }
                $('.pop-edit-live').hide();
                window.location.reload();
            }
        });
    });
    
    // 确认到院弹窗
    $('.btn-besure-comein').click(function () {
        var trObj = $(this).parents('tr');
        var tdObj = trObj.children('td');
        $('.pop-besure-real_name').text($(tdObj[1]).text());
        $('.pop-besure-client_id').val($(this).parents('tr').attr('data-client'));
        $('.pop-besure-booking_id').val($(this).parents('tr').attr('id'));
        $('.pop-besure-href').attr('href', '/customer/detail4Reception?id=' + $(this).parents('tr').attr('data-client'));

        if (trObj.attr('data-cid') > 0) {
            $('.hide-consult-id').val(trObj.attr('data-cid'));
            $('.block-consult-name').val(trObj.attr('data-cname'));
            $('.btn-select-zxs').hide();
        } else {
            $('.hide-consult-id').val('0');
            $('.block-consult-name').val('');
            $('.btn-select-zxs').show();
        }
        $('.pop-besure-comein').show();
    });

    // 关联订单
    $('.btn-link-order').on('click', function () {
        $.post('/reception/getWaitLinkOrder', {client_id: $(this).parents('tr').attr('data-client')}, function (s) {
            if (s.status == 'Y') {
                if (s.data.length <= 0) {
                    alert('该客户没有待关联咨询师的订单');
                    return false;
                }
                var htmlStr = '';
                $.each(s.data, function (i, v) {
                    htmlStr += '<tr class="item" id="'+ v.id +'" data-order="'+ v.order_id +'">';
                    htmlStr += '<td><input type="checkbox" name="" class="td-checkbox" value="'+v.id +'"></td>';
                    htmlStr += '<td>'+v.insert_time+'</td>';
                    htmlStr += '<td>'+ v.order_id +'</td>';
                    htmlStr += '<td>'+ v.hospital_name +'</td>';
                    htmlStr += '<td>'+ v.consult_name +'</td>';
                    htmlStr += '<td>¥ '+ v.total_price +'</td>';
                    htmlStr += '<td>'+ v.status +'</td>';
                    htmlStr += '</tr>';
                });
                $('.pop-relation-order table tbody').html(htmlStr);
            }
            $('.pop-relation-order').show();
        });
    });

    // 保存现场编辑
    $('.btn-edit-live-save').on('click', function () {
        var formObj = new FormData($('.form-pop-edit-live')[0]);
        formObj.append('submit', 1);
        $.ajax({
            url: '/reception/editLive',
            data: formObj,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'json',
            success: function (s) {
                alert(s.msg);
                if (s.status == 'N') {
                    return false;
                }
                $('.pop-edit-live').hide();
                window.location.reload();
            }
        })

    });

    $('input[name=is_deal]').click(function () {
        if (parseInt($(this).val()) == 1) {
            $('.pop-edit-live-deal_remark :first').prop('selected', true);
            $('.deal_remark-tip').show();
            $('.select-deal_remark-tip').hide();
        } else {
            $('.select-deal_remark-tip').show();
        }
    })

    $('.pop-edit-live-deal_remark').change(function () {
        if (parseInt($(this).val()) > 0) {
            $('.deal_remark-tip').hide('normal');
        } else {
            $('.deal_remark-tip').show('normal');
        }
    });


    // 保存编辑分诊接待
    $('.btn-pop-reception').click(function () {
        var formObj = new FormData($('.form-pop-reception-wraper')[0]);
        $.ajax({
            url: '/reception/editReception',
            data: formObj,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'json',
            success: function (s) {
                alert(s.msg);
                if (s.status == 'N') {
                    return false;
                }
                window.location.reload();
            }
        });
    });

    //编辑分诊接待弹窗
    $('.btn-edit-reception').click(function () {
        var trObj = $(this).parents('tr');
        var tdObj = trObj.children('td');
        $('.pop-reception-status').find('option:contains('+ $(tdObj[1]).text() +')').attr('selected', true);
        $('.pop-reception-part_type').find('option:contains('+ $(tdObj[7]).text() +')').attr('selected', true);
        $('.pop-reception-consult_name').text($(tdObj[8]).text());
        $('.pop-reception-id').val(trObj.attr('id'));
        $('.pop-reception-remark').html($(tdObj[9]).text());
        $('.pop-edit-reception').show();
    });


    // 选择咨询师弹框
    $(".pop-select-zxs").on('click', function (e) {
        $("#title-comein").removeClass().addClass('zxs').text('选择咨询师');
        $("#pop-select-comein").show();
    });

    // 到院信息弹框查询
    $(".btn-select-comein").on('click', function (e) {
        $.post('/customer/selectComein', {keywords: $("#keywords-comein").val()}, function (s) {
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
            $(".block-consult-name").val(obj.nickname);
            $(".hide-consult-id").val(obj.id);
        }
        $("#pop-select-comein").hide();
    });

    // 根据名称检索点击赋值
    $('.callback-result').on('click', '.tip-span', function () {
        $('#callback-client-phone').val($(this).attr('data-phone'));
        $('input[name="sex"][value="' + $(this).attr('data-sex') +'"]').attr("checked",true);
        $('#callback-client-name').val($(this).text());
        $('#callback-client-id').text($(this).attr('id'));
        $('#callback-client-sex').text($(this).attr('data-sex') == 1 ? '男' : '女');
        $('#callback-client-phone').val($(this).attr('data-phone'));
        $('#callback-client-source').val($(this).attr('data-source'));
        $('#callback-client-level').val($(this).attr('data-level'));
        $('#callback-client-href').attr('href', '/customer/detail4Reception?id=' + $(this).attr('id')).show();
        if ($(this).attr('data-consult') == 0) {
            $(".hide-consult-id").val('');
            $(".block-consult-name").val('');
            $(".dispatch-consult").show();
        } else {
            $(".hide-consult-id").val($(this).attr('data-consult'));
            $(".block-consult-name").val($(this).attr('data-consult_name'));
            $(".dispatch-consult").hide();
        }
        $('.red-msg').html('').hide();
        $('.callback-result').hide();
    });


    /**
     * 客户信息置空
     *
     * @param sourcePhone
     */
    function resetInput(sourcePhone = false) {
        $('input[name="sex"][value="' + 2 +'"]').attr("checked",true);
        $("#callback-client-source option:first").prop("selected", 'selected');
        $("#callback-client-level option:first").prop("selected", 'selected');
        $('#callback-client-href').attr('href', 'javascript:;').hide();
        $('#callback-client-id').text('');
        $(".hide-consult-id").val('');
        $(".block-consult-name").val('');
        $(".dispatch-consult").show();
    }

    // 新增到院
    $('.real_name, .phone_crypt').on('blur, change, keyup', function () {
        var isPhone = $(this).hasClass('phone_crypt') ? 1 : 0;
        var keywords = $.trim($(this).val());
        $.post('/customer/detailWithCheck', {keywords: keywords, isPhone: isPhone, isCheckDataAuth: 0}, function (s) {
            if (isPhone) {
                if (s.status == 'Y' && s.data.length > 0) {
                    $('.red-msg-phone').html('').hide();
                    var data = s.data[0];
                    $('#callback-client-name').val(data.real_name);
                    $('#callback-client-id').text(data.id);
                    $('input[name="sex"][value="' + data.sex +'"]').attr("checked",true);
                    $('#callback-client-source').val(data.source);
                    $('#callback-client-level').val(data.level);
                    $('#callback-client-href').attr('href', '/customer/detail4Reception?id=' + data.id).show();
                    if (data.consult_id == 0) {
                        $(".hide-consult-id").val('');
                        $(".block-consult-name").val('');
                        $(".dispatch-consult").show();
                    } else {
                        $(".hide-consult-id").val(data.consult_id);
                        $(".block-consult-name").val(data.consult_name);
                        $(".dispatch-consult").hide();
                    }
                } else if (s.status == 'Y' && s.data.length <= 0) { // 客户不存在
                    $('.red-msg-phone').html(s.msg).show();
                    resetInput(true);
                } else {
                    $('.red-msg-phone').html(s.msg).show();
                    resetInput(true);
                }
            } else { // if not phone ajax check
                if (s.status == 'Y' && s.data.length > 0) {
                    var callbackStr = '';
                    $.each(s.data, function (i, v) {
                        callbackStr += '<span class="tip-span" id="' + v.id +'" title="id=' + v.id +'" data-consult_name="'+ v.consult_name +'" data-consult="'+ v.consult_id +'" data-phone="' + v.phone+'" data-sex="' + v.sex +'" data-level="'+ v.level +'" data-source="' + v.source +'">' + v.real_name + '</span>';
                    });
                    $('.callback-result').html(callbackStr).show();
                } else if (s.status == 'Y' && s.data.length <= 0) { // 客户不存在
                    resetInput();
                } else {
                    resetInput();
                    $('.red-msg-real_name').html(s.msg).show();
                }
            }
        });


    });

    // 根据名称检索点击赋值
    $('.callback-result').on('click', '.tip-span', function () {
        var dispatch = $(this).parent('.callback-result').attr('id');
        var id = $(this).attr('id');
        $('.' + dispatch + '_id').val(id);
        $("input[name=" + dispatch +"_name]").val($(this).text());
        $('.red-msg-' + dispatch).html('').hide();
        $('.callback-result').hide();
    });

    // 输入检索
    $('.pair_name, .patrol_name').on('keyup', function () {
        var dispatch = $(this).attr('name').split('_')[0];
        $.post('/reception/getUserOnHospital', {keywords: $(this).val()}, function (s) {
            if (s.status == 'Y') {
                var callbackStr = '';
                $.each(s.data, function (i, v) {
                    callbackStr += '<span class="tip-span" id="' + v.id +'" title="id=' + v.id +'">' + v.nickname + '</span>';
                });
                $('.callback-result-' + dispatch).html(callbackStr).show();
            } else {
                $('.' + dispatch + '_id').val(0);
                $('.red-msg-' + dispatch).html(s.msg).show();
            }
        });
    });

    // 选中医师的联动
    $('select[name=doctor_id], select[name=hocus_id], select[name=aide_id]').on('change', function () {
        var dispatch = $(this).attr('name').split('_')[0];
        $("input[name=" + dispatch +"_name]").val($(this).find("option:selected").attr('data-name'));
    });

    // 保存划扣登记
    $('.btn-save-cure').click(function () {
        var formObj = new FormData($('.form-for-cure')[0]);
        formObj.append('order_id', $('.info-gorder-id').text());
        $.ajax({
            url: '/reception/saveCureLog',
            data: formObj,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'json',
            success: function (s) {
                alert(s.msg);
                if (s.status == 'N') {
                    return false;
                }
                window.location.reload();
            }
        });
    });

    // js 加减计算
    $('.info-gorder-table').on('blur', '.j_input', function () {
        var preg = /^\d+$/;
        var orderId = $(this).parents('tr').attr('data-order-id');
        if (!preg.test($(this).val())) {
            alert('您的输入有误呢');
        }
        var currInputNumber = parseInt($(this).val());
        currInputNumber = isNaN(currInputNumber) ? 0 : (currInputNumber < 0 ? 0 : currInputNumber);
        if (currInputNumber > parseInt($(this).parents('tr').attr('data-free'))) {
            alert('划扣数量不得超过可划扣数量');
            currInputNumber =  $(this).parents('tr').attr('data-free');
        }
        $('.' + orderId + '-use').val(currInputNumber);
        $(this).parents('tr').find('.j_input').val(currInputNumber);
    });

    // js 加减计算
    $('.info-gorder-table').on('click', '.js-cal-btn', function (e) {
        var isAdd = $(this).hasClass('j_add');
        var orderId = $(this).parents('tr').attr('data-order-id');
        var temp = $(this).parents('tr').find('.j_input').val();
        var currInputNumber = temp ? parseInt(temp) : 0;
        console.log(currInputNumber);
        var currTrObj = $(this).parents('tr');
        var preg = /^\d+$/;
        if (!preg.test(currInputNumber)) {
            alert('您的输入有误呢');
            currInputNumber = 0;
        }
        if (isAdd) {
            if (currInputNumber+1 > parseInt(currTrObj.attr('data-free'))) {
                alert('划扣数量不得超过可划扣数量');
                currInputNumber = currTrObj.attr('data-free');
            } else {
                currInputNumber++;
            }
        } else {
            if (currInputNumber > 0) {
                currInputNumber--;
            }
        }
        $('.' + orderId + '-use').val(currInputNumber);
        $(this).parents('tr').find('.j_input').val(currInputNumber);
    });

    // 划扣展示大订单
    $('.huakou-table > tbody > tr').click(function () {
        var client_id = $(this).attr('data-client');
        $('.reception-index-key').val($(this).attr('id'));
        if (!!client_id) {
            var btn_html = '<span class="edit-btn blue">划扣登记</span>';
            $.post("/reception/userorderlist", {
                "client_id": client_id,
                "option_btn": btn_html,
            }, function (data) {
                if (data.status) {
                    $('.user-order-list').html('');
                    $('.user-order-list').append(data.data);
                    $('.center-tit').show();
                }
            })
        }
    });

    // 划扣登记弹窗
    $('.deduct-right').on('click', '.pop-huakou-btn', function () {
        var order_id = $(this).parents('tr').attr('data-big-order');
        var client_id = $(this).parents('tr').attr('data-client');
        if (order_id > 0) {
            $.ajax({
                url: '/reception/getCanStrokeList',
                data: {order_id: order_id},
                type: 'POST',
                dataType: 'json',
                success: function (s) {
                    var htmlStr = '';
                    if (s.status == 'Y') {
                        $.each(s.data.goods, function (i, v) {
                            htmlStr += '<tr data-order-id="'+ i +'" data-free="' + v.free_number +'"><td>' + v.tao_title + '</td>';
                            htmlStr += '<input type="hidden" class="' + i + '-order-id" name="sun_order[goods_order_id][]" value="' + i + '">';
                            htmlStr += '<input type="hidden" class="' + i + '-order-title" name="sun_order[sku_name][]" value="'+v.tao_title+'">';
                            htmlStr += '<input type="hidden" class="' + i + '-use" name="sun_order[use_number][]" value="">';
                            htmlStr += '<td>';
                            htmlStr += '<div class="add-count">';
                            htmlStr += '<a class="js-cal-btn j_minus" href="javascript:;" title="最大可划扣数量:' + v.free_number +'">-</a>';
                            htmlStr += '<input type="text"  title="最大可划扣数量:' + v.free_number +'" class="j_input" value="0">';
                            htmlStr += '<a class="js-cal-btn j_add" href="javascript:;" title="最大可划扣数量:' + v.free_number +'">+</a>';
                            htmlStr += '</div>';
                            htmlStr += '</td></tr>';
                        });
                        $('.info-gorder-table > tbody').html(htmlStr);
                        $('.info-gorder-id').html(order_id);
                        $('.info-client-id').attr('value', client_id);

                        var d = s.data.cure;
                        $('.pop-cure-remark').text(d.remark);
                        // 医生
                        $('.pop-cure-doctor_id option:first').prop('selected', 'selected');
                        $('.pop-cure-doctor_name').val('');
                        // 配台
                        $('.pair_id').val(0);
                        $('.pop-cure-pair_name').val('');
                        // 巡回
                        $('.patrol_id').val('0');
                        $('.pop-cure-patrol_name').val('');
                        // 麻醉
                        $('.pop-cure-hocus_id option:first').prop('selected', 'selected');
                        $('.pop-cure-hocus_name').val('');
                        // 助理
                        $('.pop-cure-aide_id option:first').prop('selected', 'selected');
                        $('.pop-cure-aide_name').val('');
                        $('.pop-cure-remark').val('');

                        $('.pop-huakou').show();

                    }
                }
            });
        }
    });

    // show order children
    $('.deduct-right').on('click', '.order-num', function () {
        var order_id = $(this).attr('data-id');
        if (order_id > 0) {
            $.post("/order/getordergoods", {"order_id": order_id}, function (s) {
                if (s.status) {
                    $('.tr-order-' + order_id).html(s.data);
                    $('.show-order-' + order_id).show();
                }
            });
        }
    });


    //

    $('.datepicker').each(function () {
        laydate.render({
            elem: this,
            // done: function(value){
            //     if (value) {
            //
            //     }
            // }
        });
    });
});