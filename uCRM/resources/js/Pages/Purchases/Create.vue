<script setup>
import { getToday } from '@/common' // 別ファイルをインポート
import { onMounted, reactive } from 'vue'
import { ref } from 'vue'
import { computed } from 'vue'
import { Inertia } from '@inertiajs/inertia'

// ページ読み込み後 即座に実行
onMounted(() => {
    form.date = getToday()
})

const props = defineProps({
    'customers': Array,
    'items': Array
})

const form = reactive({
    date: null,
    customer_id: null,
    status: true,
    items: []
})

// リアクティブな配列を準備
const itemList = ref([])

onMounted(() => {
    props.items.forEach( item => { // 配列を1つずつ処理
        itemList.value.push({ // 配列に1つずつ追加
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: 0
        })
    })
})

// option用
const quantity = [ "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"]

const totalPrice = computed(() => {
    let total = 0
    itemList.value.forEach( item => {
        total += item.price * item.quantity
    })
    return total
})

const storePurchase = () => {
    itemList.value.forEach( item => {
         // 0より大きいものだけ追加
        if( item.quantity > 0 ){
            form.items.push({
                id : item.id,
                quantity: item.quantity
            })
        }
    })
    Inertia.post(route('purchases.store'), form)
} 
</script> 

<template>
<form @submit.prevent="storePurchase">
    日付<br>
    <input type="date" name="date" v-model="form.date">

    会員名<br>
    <select name="customer" v-model="form.customer_id">
    <option v-for="customer in customers" :value="customer.id" :key="customer.id">
        {{ customer.id }} : {{ customer.name }}
    </option>
    </select>
    <table>
        <tr>
            <th>Id</th>
            <th>商品名</th>
            <th>金額</th>
            <th>数量</th>
            <th>小計</th>
        </tr>
    </table>
    <tbody>
        <tr v-for="item in itemList" :key="item">
            <td>{{ item.id }}</td>
            <td>{{ item.name }}</td>
            <td>{{ item.price }}</td>
            <td>
                <select name="quantity" v-model="item.quantity">
                    <option v-for="q in quantity" :value="q" :key="q">{{ q }}</option>
                </select>
            </td>
            <td>{{ item.price * item.quantity }}</td>
        </tr>
    </tbody>
    <br>
    合計 {{ totalPrice }} 円
    <button>登録する</button> 
</form>
</template>