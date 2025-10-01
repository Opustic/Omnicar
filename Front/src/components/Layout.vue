<template>
  <div class="layout">
    <!-- Section Menu -->
    <div class="container mt-3 menu-section">
      <!-- Logo -->
      <div class="logo">
        <img src="/omnicar-logo.png" class="img-fluid" alt="Logo Omnicar" />
      </div>

      <LayoutButton
        v-for="button in boutons_container"
        :key="button.link"
        :label="button.label"
        :iconeLabel="button.icon"
        :link="button.link"
        :class="{ 'btn-active': button.link === activeRoute }"
        @click="setActive(button.link)"
      />
      
    </div>

    <hr>

    <!-- Section Profil -->
    <div class="container profile-section">


      <!-- User infos -->
      <div class="user-infos row mt-2 mb-2">
        <div class="user-photo d-flex justify-content-center align-items-center col-1 ms-2">
          <i class="bi bi-person-circle fs-2 text-light"></i>
        </div>
        <div class="user-name-mail col-8">
          <p>
            <span class="fw-bold">{{ username }}</span><br />
            {{ usermail }}
          </p>
        </div>
      </div>
      

      <!-- Déconnexion -->
      <div class="logout-button">
          <RouterLink to="/">
            <button class="btn btn-log-out">
              <i class="bi bi-box-arrow-left me-1 fs-5"></i>
              Déconnexion
            </button>
          </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useUsersStore } from "@/store/users";
import LayoutButton from "./LayoutButton.vue";
import { computed, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

// Props
const props = defineProps({
  username: String,
  usermail: String,
});


const usersStore = useUsersStore();
// Récupération des infos utilisateur depuis le store
const username = computed(() => usersStore.user_name);
const usermail = computed(() => usersStore.user_mail);

// Liste des boutons
const boutons_container = [
    { label: "Menu", icon: "bi bi-house-fill me-1 fs-5", link: "/menu" },
    { label: "Mes Bus", icon: "bi bi-bus-front me-1 fs-5", link: "/mesbus" },
    { label: "Vue Globale", icon: "bi bi-bar-chart-line me-1 fs-5", link: "/vueglobale" },
    { label: "Dépenses", icon: "bi bi-cash me-1 fs-5", link: "/depenses" },
    { label: "Recettes", icon: "bi bi-piggy-bank-fill me-1 fs-5", link: "/recettes" },
    { label: "Employés", icon: "bi bi-people me-1 fs-5", link: "/employes" },

];


// Gestion de l'URL active
const route = useRoute();
const router = useRouter();

// route active (synchro avec vue-router)
const activeRoute = computed(() => route.path);

// Fonction pour changer la route active au clic
function setActive(link) {
  router.push(link);
}
</script>



<style scoped>
/* Layout principal */
.layout {
  position: fixed;
  width: 300px;
  height: 100vh;
  overflow-y: auto;
  background-color: #fff;
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
  z-index: 999;
  display: flex;
  flex-direction: column;
  padding-bottom: 10px;
}


/* Titres */
.section-title {
  font-size: 1.5rem;
  color: #333;
}

/* Logo */
.logo {
  width: 65px;
  height: 65px;
  background-color: #00ffcc;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 10px;
}

.logo img {
  border-radius: 50%;
  width: 60px;
  height: 60px;
}

/* Infos utilisateur */
.user-infos {
  font-size: 0.9rem;
}

.user-photo {
  width: 50px;
  height: 50px;
  background-color: chocolate;
  border-radius: 50%;
}

.user-name-mail p {
  margin: 0;
}

/* Bouton Déconnexion */
.logout-button .btn-log-out {
  border: 1px solid #f44336;
  color: #f44336;
  border-radius: 6px;
  padding: 0.5rem 0;
  transition: all 0.2s ease;
  padding-left: 10px;

}

.logout-button .btn-log-out:hover {
  background-color: #d32f2f;
  color: white;
}

/* Bouton actif LayoutButton */
.btn-active {
  background-color: #00bfa5;
  color: white;
}

/* Responsive */
@media (max-width: 990px) {
  .layout {
    display: none;
  }
}
</style>
