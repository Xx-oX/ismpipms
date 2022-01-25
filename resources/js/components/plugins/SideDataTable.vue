<template>
    <table :id="this.tableId" class="display" style="width: 100%">
        <slot name="tableContent">
            <thead>
            <tr align="left">
                <th v-for="row in this.colIndex">
                    {{ row }}
                </th>
            </tr>
            </thead>
        </slot>
    </table>
</template>

<script>
    export default {
        props: ["dataTableConfig", "outRefs"],
        data() {
            return {
                tableId: this.dataTableConfig.tableId,
                colIndex: [],
                defs: [],
            };
        },
        watch: {},
        methods: {
            loadTable() {
                const vm = this;
                var res = this.dataTableConfig.ColsData;

                if (res.data != undefined) {
                    this.colIndex = Object.keys(res.data); //取得資料列的所有index
                }

                let tableCols = this.genDataTableCols(); //datatable需要的欄位對應參數
                if (tableCols.length <= 0) {
                    alert("表格資料讀取失敗 !");
                    return false;
                }

                $("#" + this.tableId).DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching: this.dataTableConfig.searching == undefined ?
                        true : this.dataTableConfig.searching,
                    select: this.dataTableConfig.select == undefined ?
                        true : this.dataTableConfig.select,
                    ajax: {
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        url: this.dataTableConfig.route,
                        type: "POST",
                        dataSrc: this.dataTableConfig.dataCallBack == undefined ?
                            this.dataCallBack : this.dataTableConfig.dataCallBack,
                        data: function (d) {
                            //console.log(vm.dataTableConfig.queryData);
                            return $.extend({}, d, JSON.parse(JSON.stringify(vm.dataTableConfig.queryData)));
                        }
                    },
                    columns: this.dataTableConfig.columns == undefined ?
                        tableCols : this.dataTableConfig.columns,
                    columnDefs: this.dataTableConfig.columnDefs == undefined ?
                        this.genColumnDefs(res.data) : this.dataTableConfig.columnDefs,
                    language: {
                        paginate: {
                            previous: "<",
                            next: ">",
                        },
                        sInfo: "第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
                        lengthMenu: "顯示 _MENU_ 筆資料",
                        emptyTable: "查無資料",
                    },
                    order: [
                        [0, "asc"]
                    ],
                    initComplete: this.initComplete,
                });

                setTimeout(() => {
                    var table = $("#" + this.tableId).DataTable();
                }, 1000);
            },
            dataCallBack(json) {
                //console.log("Default DataTable call back function response : ", json);
                return json.data;
            },
            genDataTableCols() {
                let tableCols = [];
                if (this.colIndex.length > 0) {
                    this.colIndex.forEach((colIdx) => {
                        tableCols.push({
                            data: colIdx,
                        });
                    });
                }
                return tableCols;
            },
            reloadTable() {
                $("#" + this.tableId).DataTable().ajax.reload(null, false);
            },
            initComplete() {
                if (this.dataTableConfig.initComplete != undefined) {
                    this.dataTableConfig.initComplete();
                }

                if (this.dataTableConfig.modalUpdate == undefined) {
                    //console.log('沒有設計 this.dataTableConfig.modalUpdate 使用');
                } else {
                    this.dataTableConfig.modalUpdate();
                }

            },
            genColumnDefs(data) {

            },
            editAttr(id) {
                //console.log(id);
            },
            showModal() {
                // We want the modal to open when the Open button is clicked
                let modal = document.getElementById("my-modal");
                modal.style.display = "block";
            },
            hideModal() {
                // We want the modal to close when the OK button is clicked
                let modal = document.getElementById("my-modal");
                modal.style.display = "none";

            },
        },
        created() {
        },
        mounted() {
            this.loadTable();
        },
    };
</script>

<style>
</style>
