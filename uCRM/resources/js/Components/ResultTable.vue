<script setup>
const props = defineProps({
    'data' : Object
})

</script>

<template>
    <div v-if="data.type === 'decile' "
    class="lg:w-2/3 w-full mx-auto overflow-auto">
        <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
            <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">グループ</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">平均</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">金額</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">構成比</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in data.data" :key="item.data">
                <td class="px-4 py-3">{{ item.decile }}</td>
                <td class="px-4 py-3">{{ item.average }}</td>
                <td class="px-4 py-3">{{ item.totalPerGroup }}</td>
                <td class="px-4 py-3">{{ item.totalRatio }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div v-if="data.type === 'rfm' " class="lg:w-2/3 w-full mx-auto overflow-auto">
    <h3 class="text-center text-2xl my-4 ">RFM分析結果</h3>
    <h3 class="text-center text-2xl my-4 ">合計 {{ data.totals }} 人</h3>
    <h3 class="text-center text-2xl my-4 ">RFMランク毎の人数</h3>
    <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
        <tr>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Rank</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">R</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">F</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">M</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="rfm in data.eachCount" :key="rfm.Rank">
            <td class="px-4 py-3">{{ rfm.rank }}</td>
            <td class="px-4 py-3">{{ rfm.r }}</td>
            <td class="px-4 py-3">{{ rfm.f }}</td>
            <td class="px-4 py-3">{{ rfm.m }}</td>
        </tr>
        </tbody>
    </table>
    <h3 class="text-center text-2xl my-4 ">RとFの集計表</h3>
    <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
        <tr>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">r_Rank</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">f_5</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">f_4</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">f_3</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">f_2</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">f_1</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="rf in data.data" :key="rf.Rank">
            <td class="px-4 py-3">{{ rf.Rank }}</td>
            <td class="px-4 py-3">{{ rf.f_5 }}</td>
            <td class="px-4 py-3">{{ rf.f_4 }}</td>
            <td class="px-4 py-3">{{ rf.f_3 }}</td>
            <td class="px-4 py-3">{{ rf.f_2 }}</td>
            <td class="px-4 py-3">{{ rf.f_1 }}</td>
        </tr>
        </tbody>
    </table>
    </div>
</template>