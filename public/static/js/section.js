/**
 * Created by llf on 2018/8/8.
 */
/*api 参考 http://www.htmleaf.com/jQuery/Menu-Navigation/201502141379.html*/
(function () {
    $("#tree").treeview({
        animated: "fast"
        //collapsed: true
    });

    // 新增部门弹窗
    $(".btn-add-bumen").on('click', function (){
        $("input[name='section_name']").val('');
        $("select[name='section_level']").val('请选择部门级别');
        $("select[name='section_pid']").val('请选择上级部门');
        $(".p-title-span").html('新增部门');
        $("#create-section").show();
    });

    // 联动菜单
    $("select[name='section_level']").on('change', function () {
        var level = $(this).val();
        if (level == '请选择部门级别') {
            alert('请选择部门级别');
            return false;
        }
        $.post("/section/ajaxmenu", {level: level}, function (s) {
            if (s.status == 'Y') {
                var htmlStr = '';
                $(s.data).each(function (i, v) {
                    htmlStr += "<option value='"+ v.id +"'>" + v.name + "</option>";
                });
                $("select[name='section_pid']").html(htmlStr);
            }
        }, 'json')
    });

    // 新增部门&编辑部门
    $("#btn-save-section").on('click', function (e) {
        var formObj = new FormData($("#form-section")[0]);
        $.ajax({
            url: '/section/saveUpdate',
            data: formObj,
            dataType: 'json',
            type: 'post',
            contentType: false,
            processData: false,
            success: function (s) {
                if (s.status == 'N') {
                    alert(s.msg);
                    return false;
                }
                alert('操作成功');
                window.location.reload();
            }
        })
    });


    // 添加成员 弹窗
    $(".btn-add-member").on('click', function(e) {
        if (!SECTION_ID) {
            alert('请先选中要操作的部门');
            return false;
        }
        $("#pop-div").css({
            'overflow-y': 'scroll',
            'width': 960,
            'height': 300,
        });
        $("#add-member").show();
    });


    // 添加成员 search
    $(".btn-search").on('click', function () {
        $.post('/section/popsearch', {keywords: $("#keywords").val(), id: SECTION_ID}, function (s) {
            if (s.status == 'N') {
                alert(s.msg);
                return false;
            }
            var htmlStr = '';
            $(s.list.data).each(function (i, v) {
                htmlStr += '<tr>';
                htmlStr += '<th><label class="checkbox-inline"><input type="checkbox" class="sun-box" id="'+ v.id +'" value=""></label></th>';
                htmlStr += '<th>'+ v.nickname +'</th>';
                htmlStr += '<th>'+ v.sex +'</th>';
                htmlStr += '<th>'+ v.hospital + '-' + v.section + '-' + v.group +'</th>';
                htmlStr += '<th>'+ v.duties +'</th>';
                htmlStr += '<th>'+ v.role_name +'</th>';
            });
            $(".dialog-table").find('tbody').html(htmlStr);
        }, 'json');
    });

    // enter trigger
    $(document).keyup(function(e){
        if(e.keyCode ==13){
            $(".btn-search").trigger("click");
        }
    });

    // select all
    $("#all").on('click', function () {
        var s = $(this).is(":checked");
        $(".sun-box").prop('checked', s);
    });

    // save member
    $("#btn-add-member").on('click', function (e) {
        var ids = [];
        $(".sun-box").each(function (i, v) {
            var s = $(v).prop('checked');
            if (s) {
                ids.push($(v).attr('id'));
            }
        });
        if (ids.length <= 0) {
            alert('请至少选择一个成员');
            return false;
        }
        $.post('/section/addmember', {id: SECTION_ID, member_list: ids}, function (s) {
            if (s.status == 'N') {
                alert('添加失败');
                return false;
            }
            alert('添加成功');
            // window.location.reload();
        });
    });

    // delete
    $(".delBtn").on('click', function (e) {
        e.stopPropagation();
        if (confirm('确定删除该部门吗?') == false) {
            return false;
        }
        var sectionId = $(this).attr('id').split('-')[2];
        var level = $(this).parents('li').attr('data-id').split('-')[1];
        $.post('/section/delsection', {id: sectionId, level: level}, function (s) {
            if (s.status == 'N') {
                alert(s.msg);
                return false;
            }
            alert('操作成功');
            window.location.reload();
        }, 'json');
    });

    // 编辑
    $(".editBtn").on('click', function(e) {
        e.stopPropagation();
        var sectionId = $(this).parents('li').attr('data-id').split('-')[0];
        var sectionLevel = $(this).parents('li').attr('data-id').split('-')[1];
        var curr_pid = $(this).parents('li').attr('data-id').split('-')[2];
        var nodeText = $("#node-text-"+sectionId).find(".span-a-text").text();
        $('select[name="section_level"] > option').each(function (i, v) {
            if ($(v).val() == sectionLevel) {
                $(v).attr('selected', 'selected');
                return false;
            }
        });
        $.post('/section/ajaxmenu', {level: sectionLevel}, function (s) {
            if (s.status == 'Y') {
                var htmlStr = '';
                $(s.data).each(function (i, v) {
                    if ($(v).attr('id') == curr_pid) {
                        var ck = 'selected="selected"';
                    } else {
                        var ck = '';
                    }
                    htmlStr += "<option value='"+ v.id +"' " + ck +">" + v.name + "</option>";
                });
                $("select[name='section_pid']").html(htmlStr);
            }
        }, 'json');
        $("input[name='section_name']").val(nodeText);
        $(".p-title-span").html('修改部门');
        $("#form-section").append('<input type="hidden" name="id" value="'+ sectionId +'">');
        $("#create-section").show();
    });

    // 交互
    $(".node-text").on('click', function () {
        window.location.href=$(this).attr('data-id');
    });
    $(".list-group li span").filter(".node-text").on("mouseover", function() {
        $(this).css({'cursor': 'pointer'});
        $(this).find('b').show();
    });
    $(".list-group li span").filter(".node-text").on("mouseout", function() {
        $(this).find('b').hide();
    });

})();