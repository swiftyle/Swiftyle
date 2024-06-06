(function ($) {
    "use strict";
    $("#basicScenario").jsGrid({
        width: "100%",
        filtering: true,
        editing: true,
        inserting: true,
        sorting: true,
        paging: true,
        autoload: true,
        pageSize: 15,
        pageButtonCount: 5,
        deleteConfirm: "Do you really want to delete the client?",
        controller: db,
        fields: [
            { name: "Name", type: "text", width: 150 },
            { name: "Age", type: "number", width: 50 },
            { name: "Address", type: "text", width: 200 },
            { name: "Country", type: "select", items: db.countries, valueField: "Id", textField: "Name" },
            { name: "Married", type: "checkbox", title: "Is Married", sorting: false },
            { type: "control" }
        ]
    });
    "use strict";
    $("#dataUser").jsGrid({
        width: "100%",
        filtering: true,
        editing: true,
        inserting: true,
        sorting: true,
        paging: true,
        autoload: true,
        pageSize: 15,
        pageButtonCount: 5,
        deleteConfirm: "Do you really want to delete the user?",
        controller: {
            loadData: function (filter) {
                return $.ajax({
                    type: "GET",
                    url: "/api/users",
                    dataType: "json",
                    function(field) {
                return (!filter.name || user.name.indexOf(filter.name) > -1)
                    && (!filter.username || user.username.indexOf(filter.username) > -1)
                    && (!filter.email || user.email.indexOf(filter.email) > -1)
                    && (!filter.phone || user.phone.indexOf(filter.phone) > -1)
                    && (!filter.phone_verified || user.phone_verified === filter.phone_verified)
                    && (!filter.gender || user.gender === filter.gender)
                    && (!filter.role || user.role === filter.role)
                    && (!filter.created_at || user.created_at.indexOf(filter.created_at) > -1);
            } // Mengirim filter sebagai bagian dari permintaan GET
        });
},
    // insertItem, updateItem, dan deleteItem tetap sama
    insertItem: function (item) {
        return $.ajax({
            type: "POST",
            url: "/api/users",
            data: item,
            dataType: "json"
        });
    },
updateItem: function (item) {
    return $.ajax({
        type: "PUT",
        url: "/api/users/" + item.id,
        data: item,
        dataType: "json"
    });
},
deleteItem: function (item) {
    return $.ajax({
        type: "DELETE",
        url: "/api/users/" + item.id,
        dataType: "json"
    });
}
        },
fields: [
    { name: "name", type: "text", width: 150 },
    { name: "username", type: "text", width: 150 },
    { name: "email", type: "text", width: 200 },
    { name: "phone", type: "text", width: 100 },
    { name: "phone_verified", type: "select", items: [{ value: "Yes", Name: "Yes" }, { value: "No", Name: "No" }], valueField: "value", textField: "Name" },
    { name: "gender", type: "select", items: [{ value: "Male", Name: "Male" }, { value: "Female", Name: "Female" }, { value: "Other", Name: "Other" }], valueField: "value", textField: "Name" },
    { name: "role", type: "select", items: [{ value: "Admin", Name: "Admin" }, { value: "Costumer", Name: "Costumer" }, { value: "Seller", Name: "Seller" }], valueField: "value", textField: "Name" },
    { name: "created_at", type: "text", width: 150, readOnly: true },
    { type: "control" }
]
    });
$("#sorting-table").jsGrid({
    height: "400px",
    width: "100%",
    autoload: true,
    selecting: false,
    controller: db,
    fields: [
        { name: "Name", type: "text", width: 150 },
        { name: "Age", type: "number", width: 50 },
        { name: "Address", type: "text", width: 200 },
        { name: "Country", type: "select", items: db.countries, valueField: "Id", textField: "Name" },
        { name: "Married", type: "checkbox", title: "Is Married" }
    ]
});
$("#sort").click(function () {
    var field = $("#sortingField").val();
    $("#sorting-table").jsGrid("sort", field);
});
$("#batchDelete").jsGrid({
    width: "100%",
    filtering: true,
    editing: true,
    inserting: true,
    sorting: true,
    paging: true,
    autoload: true,
    pageSize: 15,
    pageButtonCount: 5,
    deleteConfirm: "Do you really want to delete the user?",
    controller: {
        loadData: function (filter) {
            return $.ajax({
                type: "GET",
                url: "/api/users",
                data: filter,
                dataType: "json"
            });
        },
        insertItem: function (item) {
            return $.ajax({
                type: "POST",
                url: "/api/users",
                data: item,
                dataType: "json"
            });
        },
        updateItem: function (item) {
            return $.ajax({
                type: "PUT",
                url: "/api/users/" + item.id,
                data: item,
                dataType: "json"
            });
        },
        deleteItem: function (item) {
            return $.ajax({
                type: "DELETE",
                url: "/api/users/" + item.id,
                dataType: "json"
            });
        }
    },
    fields: [
        {
            headerTemplate: function () {
                return $("<button>").attr("type", "button").addClass("btn")
                    .css({ "width": "100%", "height": "100%" })
                    .append($("<i>").addClass("fa fa-trash")).click(function () {
                        deleteSelectedItems();
                    });
            },
            itemTemplate: function (_, item) {
                return $("<input>").attr("type", "checkbox")
                    .prop("checked", $.inArray(item, selectedItems) > -1)
                    .on("change", function () {
                        $(this).is(":checked") ? selectItem(item) : unselectItem(item);
                    });
            },
            align: "center",
            width: 50
        },
        { name: "name", type: "text", width: 150 },
        { name: "username", type: "text", width: 150 },
        { name: "email", type: "text", width: 200 },
        { name: "phone", type: "text", width: 100 },
        { name: "phone_verified", type: "select", items: [{ value: "Yes", Name: "Yes" }, { value: "No", Name: "No" }], valueField: "value", textField: "Name" },
        { name: "gender", type: "select", items: [{ value: "Male", Name: "Male" }, { value: "Female", Name: "Female" }], valueField: "value", textField: "Name" },
        { name: "role", type: "select", items: [{ value: "Admin", Name: "Admin" }, { value: "Costumer", Name: "Costumer" }, { value: "Seller", Name: "Seller" }], valueField: "value", textField: "Name" },
        { name: "image", type: "text", width: 200 },
        { name: "created_at", type: "text", width: 150, readOnly: true },
        { type: "control" }
    ]
});
var selectedItems = [];
var selectItem = function (item) {
    selectedItems.push(item);
};
var unselectItem = function (item) {
    selectedItems = $.grep(selectedItems, function (i) {
        return i !== item;
    });
};
var deleteSelectedItems = function () {
    if (!selectedItems.length || !confirm("Are you sure?"))
        return;
    deleteClientsFromDb(selectedItems);
    var $grid = $("#batchDelete");
    $grid.jsGrid("option", "pageIndex", 1);
    $grid.jsGrid("loadData");
    selectedItems = [];
};
var deleteClientsFromDb = function (deletingClients) {
    db.clients = $.map(db.clients, function (client) {
        return ($.inArray(client, deletingClients) > -1) ? null : client;
    });
};
}) (jQuery);