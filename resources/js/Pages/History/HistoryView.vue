<template>
    <app-layout title="History">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                歷史紀錄
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

            </div>
        </div>
    </app-layout>
</template>

<script>
    import {defineComponent} from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue';
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue';
    import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
    import SearchDataForm from '@/Pages/History/Partials/SearchDataForm.vue';
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
                    route: '/subscribe/query',
                    //各功能route----

                    queryData: this.$refs.sdfRef.finalQuery,
                    dataCallBack: this.dataCallBack,
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
        },

        mounted() {
            //console.log(this.tableCol);
        }
    })
</script>
