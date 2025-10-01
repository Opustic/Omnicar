<template>
    <fieldset>
        <legend>
            Informations du chauffeur
        </legend>

        <img :src="props.team?.chauffeurs?.[0]?.photo || '#'" alt="Photo du chauffeur" width="300" class="rounded-4">

        <p>Nom : {{ props.team?.chauffeurs?.[0]?.nom || 'Non renseigné' }}</p>
        <p>Téléphone : {{ props.team?.chauffeurs?.[0]?.telephone || 'Non renseigné' }}</p>
        <p>Adresse : {{ props.team?.chauffeurs?.[0]?.adresse || 'Non renseignée' }}</p>
        <p v-if="props.team?.chauffeurs?.[0]?.email">
            Email : {{ props.team.chauffeurs[0].email }}
        </p>
        <p>Numéro permis : {{ props.team?.chauffeurs?.[0]?.numero_permis || 'Non renseigné' }}</p>
        <p class="fst-italic">
            Ce chauffeur est enregistré depuis :
            {{ props.team?.chauffeurs?.[0]?.created_at ? props.team.chauffeurs[0].created_at.slice(0, 10) : 'Date inconnue' }}
        </p>
    </fieldset>

    <hr>

    <fieldset>
        <legend>
            Informations du controleur
        </legend>

        <img :src="props.team?.controleurs?.[0]?.photo || '#'" alt="Photo du controleur" width="300" class="rounded-4">

        <p>Nom : {{ props.team?.controleurs?.[0]?.nom || 'Non renseigné' }}</p>
        <p>Téléphone : {{ props.team?.controleurs?.[0]?.telephone || 'Non renseigné' }}</p>
        <p>Adresse : {{ props.team?.controleurs?.[0]?.adresse || 'Non renseignée' }}</p>
        <p v-if="props.team?.controleurs?.[0]?.email">
            Email : {{ props.team.controleurs[0].email }}
        </p>
        <p class="fst-italic">
            Ce controleur est enregistré depuis :
            {{ props.team?.controleurs?.[0]?.created_at ? props.team.controleurs[0].created_at.slice(0, 10) : 'Date inconnue' }}
        </p>
    </fieldset>
</template>

<script setup>
const props = defineProps({
    team: {
        type: Object,
        default: () => ({})
    }
})
</script>
