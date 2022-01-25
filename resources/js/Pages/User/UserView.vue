<template>
    <app-layout title="UserManagement">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                使用者管理
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div>
                    <SearchDataForm ref="sdfRef" @SearchPage="SearchPage" @getTargets="getTargets"/>
                    <jet-section-border/>
                </div>

                <div v-if="dataTableConfig.route != undefined">
                    <SideDataTable ref="dtRef" :dataTableConfig="dataTableConfig"/>
                </div>

                <SubmitModal ref="smRef" :submitBtnTitle="submitBtnTitle" :cancelBtnTitle="cancelBtnTitle"
                             @submitModalFunc="submitModalFunc" @initSubmitModalData="initSubmitModalData">
                    <template v-slot:content>
                        <div class="md:flex md:items-center">
                            <div class="md:w-1/3">
                                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4"
                                       for="nameInput">
                                    name
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <input v-model="submitModalData.name"
                                       class="border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                       id="nameInput" type="text">
                            </div>
                        </div>
                        <br>
                        <div class="md:flex md:items-center">
                            <div class="md:w-1/3">
                                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4"
                                       for="emailInput">
                                    email
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <input v-model="submitModalData.email"
                                       class="border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                       id="emailInput" type="text">
                            </div>
                        </div>
                        <br>
                        <div class="md:flex md:items-center">
                            <div class="md:w-1/3">
                                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4"
                                       for="roleInput">
                                    role
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <select v-model="submitModalData.role"
                                       class="border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                       id="roleInput">
                                    <option value="manager">Manager</option>
                                    <option value="subscriber">Subscriber</option>
                                    <option value="viewer">Viewer</option>
                                </select>
                            </div>
                        </div>
                    </template>
                </SubmitModal>



            </div>
        </div>
    </app-layout>
</template>

<script>
    import {defineComponent} from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue';
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue';
    import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
    import SearchDataForm from '@/Pages/User/Partials/SearchDataForm.vue';
    import SideDataTable from '@/components/plugins/SideDataTable';
    import SubmitModal from '@/components/plugins/SubmitModal';

    export default defineComponent({
        props: ['sessions', 'tableCol'],

        components: {
            AppLayout,
            JetSectionBorder,
            UpdatePasswordForm,
            SearchDataForm,
            SideDataTable,
            SubmitModal
        },

        data() {
            return {
                dataTableConfig: {},

                //Modal 用的部分
                submitModalRoute: '',
                submitModalData: {
                    id: '',
                    name: '',
                    email: '',
                    role: '',
                },
                submitBtnTitle: '',
                cancelBtnTitle: '',
            }
        },

        methods: {
            getTargets: function (value) {
                this.targetNumber = value;
                this.dataTableConfig = {
                    searching: false,
                    ColsData: this.tableCol,
                    tableId: "datatable_" + this.$gloFuncs.genRandomString(16),

                    //各功能route
                    route: '/user/query',
                    route_modify: '/user/update',
                    //各功能route----

                    queryData: this.$refs.sdfRef.finalQuery,
                    dataCallBack: this.dataCallBack,
                    modalUpdate: this.modalUpdate,
                    columnDefs: [],
                };
            },
            SearchPage: function (value) {
                this.dataTableConfig.queryData = value;
                this.$refs.dtRef.reloadTable();
            },
            dataCallBack(json) {
                this.$gloFuncs.createDatatableButton(json);
                this.modalUpdate();
                return json.data;
            },

            //點下按鈕後判斷
            modalUpdate() {
                var mythis = this;
                $('#' + this.dataTableConfig.tableId + ' tbody').unbind().on('click', 'button', {
                }, function (event) {
                    if (this.textContent == "修改") {
                        mythis.modifyAction(mythis,this);
                    }
                });
            },

            //按鈕後續行為設定
            modifyAction(mythis,e) {
                var table = $('#' + this.dataTableConfig.tableId).DataTable();
                var rowData = table.row($(e).parents('tr')).data();
                var route_modify = this.dataTableConfig.route_modify;

                mythis.setModalConfig({
                    submitBtnTitle: '儲存修改',
                    cancelBtnTitle: '取消修改',
                    submitModalRoute: route_modify
                });

                //modal帶入原本的資料
                mythis.setSubmitModalData({
                    id: rowData.id,
                    name: rowData.name,
                    email: rowData.email,
                    role: rowData.role,
                });

                mythis.$refs.smRef.showModal();
            },
            changeAction(e) {
                if (confirm('是否刪除此筆資料？')) {
                    var table = $('#' + this.dataTableConfig.tableId).DataTable();
                    var rowData = table.row($(e).parents('tr')).data();

                    var route_delete = this.dataTableConfig.route_delete;
                    var postData = {
                        "id": rowData.id,
                    };

                    axios.post(route_delete, postData).then(response => {
                        table.ajax.reload(null, false);
                    })
                        .catch(function (error) { // 請求失敗處理
                            console.log(error);
                        });
                }
            },
            //Modal 相關
            initSubmitModalData() {
                this.submitModalData = {
                    id: '',
                    name: '',
                    email: '',
                    role: '',
                }
            },
            addModal() {
                var route_create = this.dataTableConfig.route_create;

                this.setModalConfig({
                    submitBtnTitle: '新增',
                    cancelBtnTitle: '取消',
                    submitModalRoute: route_create,
                });
                this.$refs.smRef.showModal();
            },
            submitModalFunc() {
                var table = $('#' + this.dataTableConfig.tableId).DataTable();
                axios.post(this.submitModalRoute, this.submitModalData).then(response => {
                    //console.log(response.data);
                    if (response.data.code == 200) {
                        alert('執行成功 !');
                        this.$refs.smRef.hideModal();
                        table.ajax.reload(null, false);
                    } else {
                        alert(response.data.msg);
                    }
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            setModalConfig(configObj) {
                this.submitBtnTitle = configObj.submitBtnTitle;
                this.cancelBtnTitle = configObj.cancelBtnTitle;
                this.submitModalRoute = configObj.submitModalRoute;
            },
            setSubmitModalData(submitModalData) {
                this.submitModalData = submitModalData;
            },
        },

        mounted() {
            //console.log(this.tableCol);
        }
    })
</script>
