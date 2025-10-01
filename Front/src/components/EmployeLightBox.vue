<template>
    
    <!-- Photo -->
    <img :src="props.employe.photo" alt="Photo de l'employé" class="photo">


    <!-- Informations -->
    <div class="container mt-3">

        
        <h3 class="text-dark">
            Informations
        </h3>
    

        <hr>
    
        <!-- Nom de l'employé -->
        <p>
            Nom : {{ props.employe.nom }}
        </p>
    
    
        <!-- Téléphone de l'employé -->
        <p>
            Téléphone : {{ props.employe.telephone }}
        </p>
    
    
        <!-- Adresse de l'employé -->
        <p>
            Adresse : {{ props.employe.adresse }}
        </p>
    
    
        <!-- Mail de l'employé -->
        <p>
            Mail : {{ props.employe.email }}
        </p>


        <!-- Numero du permis de l'employés (au cas où c'est un chauffeur) -->
        <p v-if="props.employe.numero_permis!==undefined">Numero du permis : {{ props.employe.numero_permis }}</p>

    </div>    


</template>





<script setup>
import { ref } from 'vue';



const props = defineProps ({
    employe : Object
})


</script>




<style scoped>

.photo {
    max-width: 100%;
    max-height: 300px;
    border-radius: 20px;
}

</style>