<script setup>
import { getToday } from '@/common' // 別ファイルをインポート
import { onMounted, reactive } from 'vue'

// ページ読み込み後 即座に実行
onMounted(() => {
    form.date = getToday()
})

const form = reactive({ date: null })

</script> 

<template>
    日付<br>
    <input type="date" name="date" v-model="form.date">
</template>