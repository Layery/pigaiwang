/**
 * Created by llf on 2018/8/14.
 */
(function () {
        // edit
    $('.edit').on('click', function (e) {
        var currRow = $(this).parents('tr');

        $.ajax({
            url: '/user/editDialog',
            async: false,
            type: 'post',
            dataType: 'json',
            data: {id: currRow.attr('id')},
            success: function (s) {
                if (s.status != 'Y') {
                    alert(s.msg);
                    return false;
                }
                var hospitalId = s.data.hospital_id;
                var sectionId = s.data.section_id;
                var secHtml = groupHtml = '';
                console.log(hospitalId + '---' + sectionId);
                // 找到它左右的二级部门
                if (hospitalId) {
                    $.ajax({
                        url: '/section/ajaxGetChild',
                        data: {id: hospitalId},
                        dataType: 'json',
                        type: 'POST',
                        async: false,
                        success: function (ss) {
                            if (ss.status == 'Y') {
                                $.each($(ss.data), function (ii, vv) {
                                    secHtml += '<option value="' + vv.id + '">' + vv.name + '</option>';
                                });
                                $("select[name='section_id']").html(secHtml);
                            }
                        }
                    });
                }
                if (sectionId > 0) {
                    // 找到它左右的三级部门
                    $.ajax({
                        url: '/section/ajaxGetChild',
                        data: {id: sectionId},
                        dataType: 'json',
                        type: 'POST',
                        async: false,
                        success: function (sss) {
                            if (sss.status == 'Y') {
                                $.each($(sss.data), function (iii, vvv) {
                                    groupHtml += '<option value="' + vvv.id + '">' + vvv.name + '</option>';
                                });
                                $("select[name='group_id']").html(groupHtml);
                            }
                        }
                    });
                }
                // 表单项赋值
                $.each(s.data, function (i, v) {
                    if (i == 'sex') {
                        $("input[type='radio']").each(function () {
                            if ($(this).val() == v) $(this).attr('checked', 'checked');
                        })

                    } else if (i == 'hospital_id') {
                        $("select[name='hospital_id']").find('option').each(function () {
                            if ($(this).val() == v) {
                                $(this).attr('selected', 'selected');
                            }
                        })
                    } else if (i == 'section_id') {
                        $("select[name='section_id']").find('option').each(function () {
                            if ($(this).val() == v) {
                                $(this).attr('selected', 'selected');
                            }
                        })
                    } else if (i == 'group_id') {
                        $("select[name='group_id']").find('option').each(function () {
                            if ($(this).val() == v) {
                                $(this).attr('selected', 'selected');
                            }
                        })
                    } else if (i == 'remark') {
                        $("textarea[name='remark']").val(v);
                    } else if (i == 'role_id') {
                        $("select[name='role_id']").find('option').each(function () {
                            if ($(this).val() == v) {
                                $(this).attr('selected', 'selected');
                            }
                        })
                    } else if (i == 'data_auth') {
                        $("select[name='data_auth']").find('option').each(function () {
                            if ($(this).val() == v) {
                                $(this).attr('selected', 'selected');
                            }
                        })
                    } else {
                        $("input[name='" + i + "']").val(v);
                    }
                });
                // 表单title
                $('.dialog-title').text('修改用户');
                $("input[name='username']").attr("disabled", true);
                // 追加一个id input框
                var input = '<input type="hidden" name="id" value="' + currRow.attr('id') + '">';
                $("#form-add-user").append(input);
                $(".dialog-add-user").show();
            }
        });
    });

    // disable
    $('.stop').on('click', function (e) {
        var tip = $.trim($(this).text());
        if (confirm('确定' + tip + '该用户?') == false) {
            return false;
        }
        var uid = $(this).parents("tr").attr('id');
        var status = $(this).attr('data-id');
        $.post('/user/disableUser', {id: uid, status: status}, function (s) {
            if (s.status == 'N') {
                alert(s.msg);
                return false;
            }
            alert('操作成功');
            window.location.reload();
        }, 'json');
    });

    // resetPw
    $('.resetPw').on('click', function (e) {
        if (confirm('确定重置该用户密码?') == false) {
            return false;
        }
        var uid = $(this).parents("tr").attr('id');
        $.post('/user/resetPwd', {id: uid}, function (s) {
            if (s.status == 'N') {
                alert(s.msg);
                return false;
            }
            alert('密码已重置');
        }, 'json');
    });

    // query
    $(".query").on('click keydown', function (e) {
        $(".user-list").submit();
    });

    // create user
    $(".add-user").on('click', function () {
        $("input[name='username']").attr("disabled", false);
        $("select[name='hospital_id'] option:first").prop("selected", 'selected');
        $("select[name='section_id'] option:first").prop("selected", 'selected');
        $("select[name='group_id'] option:first").prop("selected", 'selected');
        $("select[name='role_id'] option:first").prop("selected", 'selected');
        $("select[name='data_auth'] option:first").prop("selected", 'selected');
        $('.dialog-title').text('新增用户');
        $('.dialog-add-user').show();

    });

    // save btn event
    $("#btn-save-user").click(function () {
        var formObj = new FormData($("#form-add-user")[0]);
        $.ajax({
            url: '/user/saveupdate',
            data: formObj,
            dataType: 'json',
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (s) {
                if (s.status == 'N') {
                    alert(s.msg);
                    return false;
                }
                if (s.status == 'Y') alert('操作成功');
                window.location.reload();
            }
        });
    });

    // 交互
    $("select[name='hospital_id'], select[name='section_id']").on('change', function (e) {
        if (!$(this).val()) {
            return false;
        }
        var isHospital = $(e.target).attr('name') == 'hospital_id';
        $.post('/section/ajaxGetChild', {id: $(this).val()}, function (s) {
            if (s.status == 'Y') {
                var htmlStr = '';
                $(s.data).each(function (i, v) {
                    htmlStr += "<option value='" + v.id + "'>" + v.name + "</option>";
                });
                if (isHospital) {
                    $("select[name='section_id']").html(htmlStr);
                } else {
                    $("select[name='group_id']").html(htmlStr);
                }

            }
            if (s.status == 'N') {
                alert(s.msg);
                return false;
            }
        }, 'json');
    });

    // do-export
    $(".do-export").on('click', function (e) {
            $.post('/user/doexport', function (s) {
                console.log(s);
            }, 'json');
        });
})();

