<script setup>
import { ref } from "vue"
import Dialog from "primevue/dialog"
import Button from "primevue/button"

const visible = ref(false)
</script>

<template>
    <Button label="Ouvrir le message" @click="visible = true" />
    <Dialog v-model:visible="visible" header="Information" modal>
        <p>Votre session va bientôt expirer.</p>
    </Dialog>
</template>
