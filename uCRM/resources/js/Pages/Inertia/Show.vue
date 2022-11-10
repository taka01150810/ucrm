<script setup>

defineProps({
    id: String
})

</script>
<template>
    <div>
        <!-- propsで渡ってきた変数 -->
        {{ id }} 
    </div>
</template>