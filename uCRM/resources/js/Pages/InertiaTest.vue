<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
Inertiaテストです。<br>
<!-- ページ内全ての情報を再読み込みする HTML -->
<a href="/">aタグでWelcomeに移動</a><br>
<!-- 部分的に読み込む JSON -->
<Link href="/">LinkでWelcomeに移動</Link><br>
<Link :href="route('inertia.index')">名前付きルートの確認です</Link><br>
<Link :href="route('inertia.show', { id : 1 })">パラメータの確認です</Link><br> 
</template>