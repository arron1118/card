define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'user/index',
        add_url: 'user/add',
        edit_url: 'user/edit',
        delete_url: 'user/delete',
        export_url: 'user/export',
        modify_url: 'user/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', search: false,},
                    {field: 'nickname', title: '昵称'},
                    {field: 'realname', title: '真实姓名'},
                    {field: 'phone', title: '手机号'},
                    {field: 'header_img', title: '头像', templet: ea.table.image, search: false,},
                    {field: 'identifier', title: '身份证'},
                    {field: 'openid', title: '微信openid'},
                    {field: 'email', title: '邮箱'},
                    {field: 'gender', search: 'select', selectList: ["保密","男","女"], title: '性别'},
                    {field: 'country', title: '国家'},
                    {field: 'province', title: '省份'},
                    {field: 'city', title: '市'},
                    {field: 'status', search: 'select', selectList: ["禁用","启用"], title: '状态', templet: ea.table.switch},
                    {field: 'create_time', title: '创建时间', search: false,},
                    {width: 250, title: '操作', templet: ea.table.tool},
                ]],
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
    };
    return Controller;
});
