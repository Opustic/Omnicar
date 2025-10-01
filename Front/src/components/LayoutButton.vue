<template>

    <RouterLink class="btn layout-btn" :to="props.link">
        <!-- Slot pour mettre l'icône -->
        <i :class="props.iconeLabel"></i>
        {{ props.label }}
    </RouterLink>

</template>


<script setup>

import { RouterLink } from 'vue-router';


const props = defineProps({
    label : String,
    iconeLabel : String,
    link : {type : String, default:"/"}
})


</script>



<style scoped>

.layout-btn {
    margin-top: 10px;
    font-weight: bold;
    width: 100%;
    text-align: start;
    transition: all 0.2s ease-in;
}


.layout-btn:hover {
    background-color: #8D7FFC;
    color: white;
}

a {
    text-decoration: none;
}



</style>