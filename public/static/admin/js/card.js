define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'card/index',
        add_url: 'card/add',
        edit_url: 'card/edit',
        delete_url: 'card/delete',
        export_url: 'card/export',
        modify_url: 'card/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', search: false,},
                    {field: 'company', title: '公司名称'},
                    {field: 'username', title: '姓名'},
                    {field: 'position', title: '职位'},
                    {field: 'phone', title: '手机号'},
                    {field: 'header_img', title: '头像', templet: ea.table.image, search: false,},
                    {field: 'address', title: '地址'},
                    {field: 'user_card_history_count', title: '浏览', search: false,},
                    {field: 'user_card_support_count', title: '点赞', search: false,},
                    {field: 'status', search: 'select', selectList: ["禁用","启用"], title: '状态', templet: ea.table.switch},
                    {field: 'create_time', title: '创建时间', search: false,},
                    {width: 150, title: '操作', templet: ea.table.tool},
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
