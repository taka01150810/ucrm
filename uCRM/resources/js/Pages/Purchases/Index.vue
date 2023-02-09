<script setup>
import { onMounted, ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    orders : Object
})

onMounted(() => {
    console.log(props.orders.data) // 結果 Proxyのデータ
})

const search = ref('')
</script>

<template>
    <div>
        <Head title="購買履歴" />

        <AuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    購買履歴
                </h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <section class="text-gray-600 body-font">
                                <div class="container px-5 py-8 mx-auto">
                                    <FlashMessage />
                                    <div class="flex pl-4 mt-4 lg:w-2/3 w-full mx-auto">
                                        <div>
                                            <input type="text" name="search" v-model="search">
                                            <button class="bg-blue-300 text-white py-2 px-2" @click="searchCustomers">検索</button>
                                        </div>
                                        <button class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">Button</button>
                                    </div>
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">Id</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">氏名</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">合計金額</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ステータス</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">購入日</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="order in props.orders.data" :key="order.id">
                                            <td class="px-4 py-3">{{ order.id }}</td>
                                            <td class="px-4 py-3">{{ order.customer_name }}</td>
                                            <td class="px-4 py-3">{{ order.total }}</td>
                                            <td class="px-4 py-3">{{ order.status }}</td>
                                            <td class="px-4 py-3">{{ order.created_at }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <Pagination class="mt-6" :links="props.orders.links"></Pagination>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>
