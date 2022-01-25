<template>
    <app-layout title="Manage">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                管理頁面
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

                <div style="text-align:center;">
                    <button class="mx-16 flex-auto green-btn-bg text-white font-bold py-2 px-4 rounded" style="width:15%;"
                            @click="addModal">
                        新增資料
                    </button>
                    <button class="mx-16 flex-auto green-btn-bg text-white font-bold py-2 px-4 rounded" style="width:15%;"
                            @click="ping">
                        Ping測試
                    </button>
                </div>


                <SubmitModal ref="smRef" :submitBtnTitle="submitBtnTitle" :cancelBtnTitle="cancelBtnTitle"
                             @submitModalFunc="submitModalFunc" @initSubmitModalData="initSubmitModalData">
                    <template v-slot:content>
                        <div class="md:flex md:items-center">
                            <div class="md:w-1/3">
                                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4"
                                       for="subscriberInput">
                                    使用者
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <input v-model="submitModalData.subscriber"
                                       class="border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                       id="subscriberInput" type="text" required>
                            </div>
                        </div>
                        <br>
                        <div class="md:flex md:items-center">
                            <div class="md:w-1/3">
                                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4"
                                       for="usageInput">
                                    用途
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <input v-model="submitModalData.usage"
                                       class="border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                       id="usageInput" type="text">
                            </div>
                        </div>
                        <br>
                        <div class="md:flex md:items-center">
                            <div class="md:w-1/3">
                                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4"
                                       for="ipInput">
                                    IP
                                </label>
                            </div>
                            <div class="md:w-2/3" v-for="array in SearchArray">
                                <select id="ipSelect" required
                                    v-model="submitModalData.ip"
                                    @change="autoping"
                                    class="border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500">
                                    <option v-for="row in array.data">{{row}}</option>
                                </select>
                            </div>
                            <div class="ml-4">
                                <div id="ylight" class="mx-4 bg-yellow-400 w-4 h-4 rounded-full"></div>
                                <div id="glight" hidden=true class="mx-4 bg-green-400 w-4 h-4 rounded-full"></div>
                                <div id="rlight" hidden=true class="mx-4 bg-red-400 w-4 h-4 rounded-full"></div>
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
    import SearchDataForm from '@/Pages/IpManage/Partials/SearchDataForm.vue';
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
            SubmitModal,
        },

        data() {
            return {
                dataTableConfig: {},

                //Modal 用的部分
                submitModalRoute: '',
                submitModalData: {
                    id: '',
                    subscriber: '',
                    usage: '',
                    ip: ''
                },
                submitBtnTitle: '',
                cancelBtnTitle: '',
                SearchArray: [],
                finalQuery: [],
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
                    route: '/ip/query',
                    route_create: '/subscribe/create',
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
                return json.data;
            },
            // ping
            ping(){
                var route_ping = '/ip/ping';
                var ip_ret = prompt("Enter the ip address for pinging: ", "140.116.82.193");
                if(ip_ret === null){
                    return;
                }
                alert("ping: " + ip_ret);
                axios.post(route_ping, {
                        ip: ip_ret,
                    })
                    .then( (response) => {
                        console.log(response);
                        var str = "";
                        response.data.res.forEach(e => {str+=(e+'\n')});
                        alert(str);
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
            autoping(){
                var route_ping = '/ip/ping';
                var ip = document.getElementById("ipSelect").value;
                var isActive;
                var yl = document.getElementById("ylight");
                var gl = document.getElementById("glight");
                var rl = document.getElementById("rlight");
                yl.hidden = false;
                gl.hidden = true;
                rl.hidden = true;
                alert('Press to start pinging 140.116.82.' + ip);
                axios.post(route_ping, {
                        ip: '140.116.82.' + ip,
                    })
                    .then( (response) => {
                        // active/inactive
                        isActive = (response.data.res.length == 11)?true:false;
                        yl.hidden = true;
                        if(isActive){
                            rl.hidden = false;
                        }
                        else{
                            gl.hidden = false;
                        }
                        var str = "";
                        response.data.res.forEach(e => {str+=(e+'\n')});
                        console.log(str);
                        alert('Finished!');
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
            //Modal 相關
            initSubmitModalData() {
                this.submitModalData = {
                    id: '',
                    subscriber: '',
                    usage: '',
                    ip: ''
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
                //console.log(this.submitModalData);
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
            searchData() {
                //此處無需改動
                var query = this.getQuery();
                this.finalQuery = query;
                this.$emit("SearchPage", this.finalQuery);
            },
            getOption() {
                var get_route = '/ip/SearchDynamic';

                //填入 get_route 設定對應的 route 位址，web.php 以及 controller 需先設定，參考對應寫法，此外無需改動
                axios.get(get_route).then((response) => {
                    var Res = response.data;
                    this.SearchArray = Res.data;
                    //console.log(Res);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getQuery() {
                //query 中的 key 值需填入 table 的需查詢的對應表格名稱。
                var queryData = {
                    query: [
                        // {
                        //     "key": "name",
                        //     "value": this.name
                        // }
                    ]
                };

                //由下拉選單動態增加，無需改動。
                this.SearchArray.forEach(function (item) {
                    var tmpObject = {
                        key: item.id,
                        value: document.getElementById('option_' + item.id).value
                    };
                    queryData.query.push(tmpObject);
                });
                //console.log(queryData);
                return queryData;
            }
        },

        mounted() {
            //console.log(this.tableCol);
            //如果有需要動態下拉選單才使用
            this.getOption();

            //此處無需改動
            setTimeout(() => {
                var query = this.getQuery();
                this.finalQuery.query = query;
                var targets = query.length;
                this.$emit("getTargets", targets, this.finalQuery);
            }, 250);

        }
    })
</script>
