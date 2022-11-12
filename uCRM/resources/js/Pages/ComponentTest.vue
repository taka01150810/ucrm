<script setup>
import Label from '@/components/InputLabel.vue'
import Input from '@/Components/TextInput.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
</script>

<template>
<GuestLayout>
    <Label>タイトル</Label>
    <Input modelValue="初期値です"></Input>
</GuestLayout>
</template>