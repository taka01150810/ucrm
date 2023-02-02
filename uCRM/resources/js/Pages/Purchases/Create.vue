<script setup>
import { getToday } from '@/common' // 別ファイルをインポート
import { onMounted, reactive } from 'vue'

// ページ読み込み後 即座に実行
onMounted(() => {
    form.date = getToday()
})

const props = defineProps({
    'customers': Array
})

const form = reactive({
    date: null,
    customer_id: null
})

</script> 

<template>
    日付<br>
    <input type="date" name="date" v-model="form.date">

    会員名<br>
    <select name="customer" v-model="form.customer_id">
    <option v-for="customer in customers" :value="customer.id" :key="customer.id">
        {{ customer.id }} : {{ customer.name }}
    </option>
    </select>

    
</template>