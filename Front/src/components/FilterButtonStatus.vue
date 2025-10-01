<template>

    <div class="border btn btn-filter ms-2">
        <Icone :label="props.iconLabel" />
        {{ props.label }}
    </div>


</template>



<script setup>
import Icone from './Icone.vue';


const props = defineProps({
    label : String,
    iconLabel : String
})



</script>




<style scoped>
.border {
    background-color: #d9d9d9;
    color: black;
    font-weight: bold;
    font-size: 12px;
    padding: 5px;
    text-align: center;
    max-width: 120px;
    border-radius: 10px;
}


.btn-filter:hover {
    background-color: var();

}

</style>