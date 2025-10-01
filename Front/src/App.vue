<template>
  <Loader v-if="ui.globalLoading" text="Veuillez patienter..." />

  <main>
    <!-- Layout principal uniquement si pas sur /login -->
    <template v-if="route.path !== '/'">
      <div class="sidebar">
        <Layout :username="user.username" :usermail="user.usermail" />
      </div>

      <div class="content">
        <MobileNavbar />
        <RouterView />
      </div>
    </template>

    <!-- Si sur /login, afficher juste le RouterView -->
    <template v-else>
      <RouterView />
    </template>
  </main>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import Layout from './components/Layout.vue'
import MobileNavbar from './components/MobileNavbar.vue'
import Loader from './components/Loader.vue'
import { useUiStore } from '@/store/ui'
import { useVehiculesStore } from './store/vehicules'
import { useChauffeursStore } from './store/chauffeurs'
import { useControleursStore } from './store/controleurs'
import { useEquipeStore } from './store/equipes'
import { useMecaniciensStore } from './store/mecaniciens'

const ui = useUiStore()
const route = useRoute()

// Informations de l'utilisateur
const user = ref({
  username: 'Opustic Phenix',
  usermail: 'Propriétaire'
})



// stores pinia
const vehiculesStore =  useVehiculesStore()
const chauffeursStore = useChauffeursStore()
const controleursStore = useControleursStore()
const mecaniciensStore = useMecaniciensStore()
const equipesStore = useEquipeStore()


onMounted(async ()=> {

  await vehiculesStore.fetchAll()
  await chauffeursStore.fetchAll()
  await controleursStore.fetchAll()
  await equipesStore.fetchAll()
  mecaniciensStore.fetchAll()

})

</script>
