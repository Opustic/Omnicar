<template>

    <td>
        <img :src="props.photo_chauffeur" alt="photo du chauffeur" class="image">
        <img :src="props.photo_controleur" alt="photo du controleur" class="image">
    </td>

    <td>
        {{ props.chauffeur_name }}
    </td>

    <td>
        {{ props.controleur_name }}
    </td>

</template>



<script setup>

import { computed } from 'vue';

const props = defineProps ({
    id : Number,
    photo_chauffeur : String,
    photo_controleur : String,
    chauffeur_name : String,
    controleur_name : String,
})



</script>




<style scoped>
.image {
    height: 100px;
    width: auto;
}


.image:nth-child(1) {
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
}

.image:nth-child(2) {
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
}



</style>