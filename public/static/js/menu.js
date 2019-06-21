/**
 * Created by llf on 2018/8/23.
 */

(function () {

    // create user
    $(".add-user").on('click', function () {
        $('.dialog-title').text('新增菜单');
        $('.dialog-add-user').show();
    });

    $("input[name='level']").on('blur', function (e) {
        $("input[name='module'], input[name='controller'], input[name='action'], input[name='url']").attr("disabled", false);
        $("option[value!='top']").attr('disabled', false);
        var val = parseInt($.trim($(this).val()));
        if (val == 1) {
            $("input[name='module'], input[name='controller'], input[name='action'], input[name='url']").attr("disabled", true);
            $("option[value!='top']").attr('disabled', true);
        }
    });

    $("input[name='url']").on('focus', function (e) {
        var temp = {};
        // temp.module = $("input[name='module']").val();
        temp.controller = $("input[name='controller']").val();
        temp.action = $("input[name='action']").val();
        var url = '/';
        $.each(temp, function (i, v) {
            url += $.trim(v) + '/';
        });
        url = url.substring(0, url.length-1);
        url = url.toLowerCase(url);
        $("input[name='url']").val(url);
    });

    // save btn event
    $("#btn-save-menu").click(function () {
        var formObj = new FormData($("#form-add-user")[0]);
        $.ajax({
            url: '/menu/saveupdate',
            data: formObj,
            dataType: 'json',
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(s) {
                if (s.status == 'N') {
                    alert(s.msg);
                    return false;
                }
                if (s.status == 'Y') alert('操作成功');
                window.location.reload();
            }
        });
    });

    $(".edit").click(function () {
        var currRow = $(this).parents('tr');
        var tdObj = currRow.children('td');
        var map = {
            'name' : $(tdObj[1]).text(),
            'weight' : $(tdObj[7]).text(),
            'module': $(tdObj[3]).text(),
            'controller': $(tdObj[4]).text(),
            'action': $(tdObj[5]).text(),
            'url': $(tdObj[2]).text(),
            'level': $(tdObj[8]).text()
        };
        var curr_pid = currRow.attr('data-pid') == 0 ? 'top' : currRow.attr('data-pid');
        $.each(map, function (i, v) {
            $("#form-add-user").find("input[name='"+ i +"']").val(v);
        });
        $("input[name=status][value=" + $(tdObj[6]).text() +"]").attr('selected', true);
        $("option[value="+ curr_pid +"]").attr("selected", "selected");
        $('.dialog-title').text('修改菜单');
        // 追加一个id input框
        var input = '<input type="hidden" name="id" value="'+ currRow.attr('id') +'">';
        $("#form-add-user").append(input);
        $('.dialog-add-user').show();
    });

    $(".stop").on('click', function () {
        var status = confirm("代码干掉了吗?");
        if (!status) return false;

        var id = $(this).parents("tr").attr('id');
        $.get('/menu/deletemenu', {id: id}, function (s) {
            if (s.status == 'N') {
                alert('操作失败');
                return false;
            }
            alert('删除成功');
            window.location.reload();
        }, 'json');
    });
})();
