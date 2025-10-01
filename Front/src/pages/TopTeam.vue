<template>

    <div class="container text-dark">
        <RouterLink to="/menu" class="btn btn-return mb-2" style="text-decoration: none;"><i class="bi bi-caret-left-fill"></i>Retour</RouterLink>
        <h1>Liste des meilleures équipes</h1>


        <!-- Listing des équipes -->
        <div class="mt-4 listing-teams">
            <TeamStats/>
            <TeamStats/>
            <TeamStats/>
            <TeamStats/>
            <TeamStats/>
            <TeamStats/>
            <TeamStats/>
            <TeamStats/>
            <TeamStats/>

        </div>
    </div>




</template>


<script setup>
import TeamStats from '@/components/TeamStats.vue';
import { RouterLink } from 'vue-router';



</script>



<style scoped>
    .container h1 {
        font-family: var(--font-title-small);
    }
    

    .listing-teams {
        background-color: white;
        padding: 30px;
        width: 90%;
        border-radius: 20px;
    }


    @media (max-width:780px) {
        .listing-teams {
            width: 100%;
        } 
        
    }

</style>