define(["jquery", "easy-admin"], function ($, ea) {    var init = {        table_elem: '#currentTable',        table_render_id: 'currentTableRenderId',        index_url: 'user.card_history/index',        add_url: 'user.card_history/add',        edit_url: 'user.card_history/edit',        delete_url: 'user.card_history/delete',        export_url: 'user.card_history/export',        modify_url: 'user.card_history/modify',    };    var Controller = {        index: function () {            ea.table.render({                init: init,                cols: [[                    {type: 'checkbox'},                    {field: 'id', title: 'id', search: false,},                    {field: 'card.company', title: '公司名称'},                    {field: 'card.logo', title: '公司LOGO', search: false, templet: ea.table.image},                    {field: 'card.username', title: '姓名'},                    {field: 'card.position', title: '职位'},                    {field: 'card.phone', title: '手机号'},                    {field: 'user.nickname', title: '微信用户'},                    {field: 'view_time', title: '浏览时间', search: false, sort: true, },                    {field: 'create_time', title: '创建时间', search: false,},                    {width: 250, title: '操作', templet: ea.table.tool},                ]],            });            ea.listen();        },        add: function () {            ea.listen();        },        edit: function () {            ea.listen();        },    };    return Controller;});