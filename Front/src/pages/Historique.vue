<template>

    <div class="container">
        <!-- Bouton pour retourner en arrière -->
        <RouterLink :to="{name:'businfos', params:{id:props.id}}" class="btn btn-return mb-3" style="text-decoration: none;">
            <i class="bi bi-caret-left-fill"></i> Retour
        </RouterLink>

        <h2 class="text-dark">
            Historique détaillé
        </h2>


        <PayementItem
            v-for="versement in versements"
            :amount="versement?.total"
            :date="versement?.date"
        />





    </div>

</template>


<script setup>
import { ref, computed, onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import axios from 'axios';
import PayementItem from '@/components/PayementItem.vue';


const props = defineProps ({
    id: {type:Number, default:0}
})


const url_get_versements = computed(()=>`http://127.0.0.1:8000/api/versement/${props.id}/getVersementForVehicle`)


const versements = ref(null)


const fetchHistorique = async () => {


    const response = await axios.get(url_get_versements.value)
    versements.value = response.data

    console.log(versements.value)
    console.log("Versements récupérés avec succès")

}



onMounted(async ()=> {
    await fetchHistorique()
})


</script>