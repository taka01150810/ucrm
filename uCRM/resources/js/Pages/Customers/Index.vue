<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
import { onMounted } from 'vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    customers: Object
})

// //ページが表示されたタイミングで実行
// onMounted(() => {
//  console.log(props.customers);
//  //プロキシ型
//  console.log(props.customers.last_page)
//  //結果 20
// })
</script>

<template>
    <ul v-for="customer in customers.data" :key="customer.id">
    <li>{{ customer.id }}</li>
    <li>{{ customer.name }}</li>
    </ul>
    <Pagination class="mt-6" :links="customers.links"></Pagination>
</template>