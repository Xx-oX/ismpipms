<template>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div :id="modalId" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <slot name="header"></slot>
                        </h3>
                        <div class="mt-2">
                            <slot name="content"></slot>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex justify-center">
                    <button type="button" :id="submitBtnId"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="submit()">
                        <template v-if="submitBtnTitle != undefined && submitBtnTitle != null && submitBtnTitle != ''">
                            {{ submitBtnTitle }}
                        </template>
                        <template v-else>
                            Submit
                        </template>
                    </button>
                    <button type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="hideModal()">
                        <template v-if="cancelBtnTitle != undefined && cancelBtnTitle != null && cancelBtnTitle != ''">
                            {{ cancelBtnTitle }}
                        </template>
                        <template v-else>
                            Cancel
                        </template>
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['title', 'content', 'submitBtnId', 'submitBtnTitle', 'cancelBtnTitle', 'isShow'],
        data() {
            return {
                modalId: '',
                parameter: null, //傳入參數
            }
        },
        methods: {
            showModal() {
                $(this.getModalId()).show();
            },
            hideModal() {
                $(this.getModalId()).hide();
				this.$emit('initSubmitModalData');
            },
            getModalId() {
                return '#' + this.modalId;
            },
            storeParameter(parameter) {
                this.parameter = parameter;
            },
            submit() {
                this.$emit('submitModalFunc'); //使用外部定義的sumit function
            },
        },
        created() {
            this.modalId = 'submitModal_' + this.$gloFuncs.genRandomString(16);
        },
        mounted() {
            if (this.isShow) {
                this.showModal();
            } else {
                this.hideModal();
            }
        }
    }
</script>