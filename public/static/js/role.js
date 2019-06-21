/**
 * Created by llf on 2018/8/7.
 */
$(function () {

    $(".center-tit tr").on('mouseover', function (e) {
        $(this).css({'cursor': 'pointer'});
    });

    var CURR_ROLE;

    $("#add").on('click', function(){
        $(".add-pop").show();
    });

    // delete
    $("#delete").on('click', function () {
        if (!CURR_ROLE) {
            alert("请先点击选择角色条目"); return false;
        }
        if (confirm('确定删除该角色吗?') == false) {
            return false;
        }
        $.post('/role/delete', {id: CURR_ROLE}, function(s) {
            alert(s);
            window.location.reload();
        });
    });

    // 编辑pop
    $("#edit").on('click', function() {
        if (!CURR_ROLE) {
            alert("请先点击选择角色条目"); return false;
        }
        var tdObj = $("#"+ CURR_ROLE).find('td');
        $("#form-edit-role").find("input[name='role_name']").val($(tdObj[0]).text());
        $("#form-edit-role").find("input[name='role_desc']").val($(tdObj[1]).text());
        $("#form-edit-role").append('<input type="hidden" name="id" value="'+ CURR_ROLE +'">');
        $(".edit-pop").show();
    });

    // edit function
    $('#role-edit').click(function () {
        var formObj = new FormData($("#form-edit-role")[0]);
        $.ajax({
            url: '/role/edit',
            dataType: 'json',
            type: 'post',
            data: formObj,
            contentType: false,
            processData: false,
            success: function (s) {
                if (s.status == 'Y') {
                    alert('修改成功');
                    window.location.reload();
                } else {
                    alert(s.msg);
                }
            }
        })
    });
    // 创建角色
    $("#role-submit").on('click', function () {
        var formObj = new FormData($("#form-add-role")[0]);
        $.ajax({
            url: '/role/add',
            dataType: 'html',
            type: 'post',
            data: formObj,
            contentType: false,
            processData: false,
            success: function (s) {
                alert(s);
                if (s == '添加成功') {
                    window.location.reload();
                }
            }
        })

    });

    // 保存权限
    $("#role-menu-submit").on('click', function(e) {
        var isSelect = $(".list-group li").filter(".node-checked");
        if (isSelect.length == 0) {
            alert('请至少勾选一项权限');
            return false;
        }
        var menuList = [];
        $(".list-group li").filter(".node-checked").each(function (i, v) {
            menuList.push($(v).find(".data-span").attr('data-id'));
        });

        $.post('/role/savemenus', {roleId: CURR_ROLE, menuList: menuList}, function (s) {
            if (s.status == 'N') {
                alert(s.msg);
                return false;
            }
            alert('操作成功');
            window.location.reload();
        }, 'json');

    });
    

    $(".center-tit > tbody > tr").on('click', function(e) {
        var roleID = $(this).attr('id');
        CURR_ROLE = roleID;
        $(this).siblings('tr').css('background', 'white');
        $(this).css('background', '#F5F5F5');
        $.post('/menu/get', {'roleId': roleID}, function (s) {
            $("#menu-tree").treeview({
                showBorder: false,
                showCheckbox: true,
                levels: 2,  // 树展开的级别
                data: s,
                onNodeChecked: function(event, node) {
                    var selectNodes = getChildNodeIdArr(node); //
                    if (selectNodes) {
                        $('#menu-tree').treeview('checkNode', [selectNodes, { silent: true }]);
                    }
                    setParentNodeCheck(node);
                    var oParent=$("#menu-tree").treeview('getParent', node);
                    $("#menu-tree").treeview('checkNode', [ oParent.nodeId, { silent:true } ]);
                },
                onNodeUnchecked: function (event, node) {
                    var selectNodes = getChildNodeIdArr(node);
                    if (selectNodes) {
                        $('#menu-tree').treeview('uncheckNode', [selectNodes, { silent: true }]);
                    }

                    var bCancle=false,
                        oParent=$("#menu-tree").treeview('getParent', node),
                        oSiblings=$("#menu-tree").treeview('getSiblings', node);

                    if(oSiblings.length>0){
                        console.log("数据"+oSiblings);
                        for(item in oSiblings){
                            if(oSiblings[item].state.checked){
                                bCancle=true;
                                break;
                            }
                        }
                    }
                    if(!bCancle){//没有已选的子节点，删除父级元素勾选效果
                        $("#menu-tree").treeview('uncheckNode', [ oParent.nodeId, { silent:true } ]);
                    }



                }

            });
            $(".right").show();
        }, 'json');
    });

    function getChildNodeIdArr(node) {
        var ts = [];
        if (node.nodes) {
            for (x in node.nodes) {
                ts.push(node.nodes[x].nodeId);
                if (node.nodes[x].nodes) {
                    var getNodeDieDai = getChildNodeIdArr(node.nodes[x]);
                    for (j in getNodeDieDai) {
                        ts.push(getNodeDieDai[j]);
                    }
                }
            }
        } else {
            ts.push(node.nodeId);
        }
        return ts;
    }

    function setParentNodeCheck(node) {
        var parentNode = $("#menu-tree").treeview("getNode", node.parentId);
        // console.log(parentNode.nodeId);
        if (parentNode.nodes) {
            var checkedCount = 0;
            for (x in parentNode.nodes) {
                if (parentNode.nodes[x].state.checked) {
                    checkedCount ++;
                } else {
                    break;
                }
            }
            if (checkedCount === parentNode.nodes.length) {
                $("#menu-tree").treeview("checkNode", parentNode.nodeId);
                setParentNodeCheck(parentNode);
            }
        }
    }
});