<template>

    <i :class="[props.label]"></i>


</template>


<script setup>

const props = defineProps({
    label :  String
})

</script>