<template>

    <td>
        <img :src="props.photo" :alt="props.nom" class="photo">
    </td>


    <td>
        {{ props.nom }}
    </td>

    <td>
        {{ props.telephone }}
    </td>

    <td>
        {{ props.adresse }}
    </td>


    <td>
        {{ props.email }}
    </td>
    

</template>



<script setup>


const props = defineProps({
    photo:String,
    nom : String,
    telephone: String,
    adresse : String,
    email : String,
    photo : String,
})
</script>



<style scoped>

    .photo {
        width: auto;
        height: 100px;
        border-radius: 20px;
    }

</style>