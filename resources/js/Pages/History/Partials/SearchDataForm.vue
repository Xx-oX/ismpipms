<template>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="mt-5 md:mt-0 md:col-span-6">
            <form @submit.prevent="searchData">
                <div
                        class="flex items-center justify-start px-4 py-3 bg-gray-50 text-left sm:px-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    <h3 class="text-lg font-medium text-gray-900">
                        查詢條件
                    </h3>
                </div>
                <div class="px-4 py-5 bg-white sm:p-6 shadow">
                    <div class="grid grid-cols-2 gap-6">
                        <!--依據個別頁面的需求手動新增， v-model 的內容來自 data() => { return ...}  -->
                        <!-- <div>
                            使用者
                            <input type="text" v-model="subscriber"
                                   class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                        </div> -->

                        <!--此處無需改動，給予相對應的參數即會自動產生-->
                        <div v-for="array in SearchArray">
                            使用者
                            {{array.name}}
                            <select :id="'option_' + array.id"
                                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option v-for="row in array.data">{{row}}</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div
                        class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                    <jet-button>
                        篩選
                    </jet-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import {
        defineComponent
    } from 'vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'

    export default defineComponent({
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
        },

        props: ['user'],

        data() {
            return {
                //此處用來依據不同的需求存放靜態變數
                subscriber: '',


                //-----------
                SearchArray: [],
                finalQuery: [],
            }
        },

        methods: {
            searchData() {
                //此處無需改動
                var query = this.getQuery();
                this.finalQuery = query;
                this.$emit("SearchPage", this.finalQuery);
            },
            getOption() {
                var get_route = '/subscribe/SearchDynamic';

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
                        //     "key": "subscriber",
                        //     "value": this.subscriber
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
