<template>
    <nav class="mobile-navbar fixed-top">

        <!-- Bouton menu hamburger -->
        <div class="ms-3 py-2">
            <button class="menu-toggle" @click="isOpen = !isOpen">
                <i class="bi bi-list" v-if="!isOpen"></i>
            </button>
        </div>

        
        <!-- Menu déroulant -->
        <div class="menu-content" :class="{ open: isOpen }">
            <!-- Bouton fermer -->
            <button class="btn-close" @click="isOpen = false"></button>

            <!-- Section Menu -->
            <div class="menu-section">
                <h3>Menu</h3>
                <LayoutButton
                v-for="(button, index) in boutons_container"
                :key="index"
                :label="button.label"
                :iconeLabel="button.icon"
                :link="button.link"
                :class="{ 'btn-active': index === activeButtonIndex }"
                @click="setActiveButton(index)"
                />
            </div>

            <hr>

            <!-- Section Profil -->
            <div class="menu-section">

                <!-- Infos utilisateur -->
                <div class="user-infos">
                <div class="user-photo d-flex justify-content-center align-items-center col-1 ms-2">
                    <i class="bi bi-person-circle fs-2 text-light"></i>
                </div>
                <div class="user-name-mail">
                    <p>
                    <span class="fw-bold">{{ username }}</span><br />
                    {{ usermail }}
                    </p>
                </div>
                </div>

                <!-- Bouton déconnexion -->
                <div class="logout-button">
                    <RouterLink to="/">
                        <button class="btn btn-log-out" @click="logout">
                            <i class="bi bi-box-arrow-left me-1 fs-5"></i> Déconnexion
                        </button>
                    </RouterLink>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import LayoutButton from "./LayoutButton.vue";

import { useUsersStore } from '@/store/users';




const usersStore = useUsersStore();
const username = computed(()=> usersStore.user_name);
const usermail = computed(()=> usersStore.user_mail);


// Props
const props = defineProps({
    username: {type:String, default: "Opustic"},
    usermail: {type:String, default: "opustic.ph@gmail.com"},
});

// Liste des boutons
const boutons_container = [
    { label: "Menu", icon: "bi bi-house-fill me-1 fs-5", link: "/menu" },
    { label: "Mes Bus", icon: "bi bi-bus-front me-1 fs-5", link: "/mesbus" },
    { label: "Vue Globale", icon: "bi bi-bar-chart-line me-1 fs-5", link: "/vueglobale" },
    { label: "Dépenses", icon: "bi bi-cash me-1 fs-5", link: "/depenses" },
    { label: "Recettes", icon: "bi bi-piggy-bank-fill me-1 fs-5", link: "/recettes" },
    { label: "Employés", icon: "bi bi-people me-1 fs-5", link: "/employes" },

];


// État
const isOpen = ref(false);
const activeButtonIndex = ref(0);

// Récupération route
const route = useRoute();

// Mettre à jour bouton actif selon l'URL
const setActiveFromRoute = () => {
    const foundIndex = boutons_container.findIndex(
        (btn) => btn.link === route.path
    );
    activeButtonIndex.value = foundIndex !== -1 ? foundIndex : 0;
};

// Méthode click bouton
const setActiveButton = (index) => {
    activeButtonIndex.value = index;
    isOpen.value = false;
};

// Méthode logout
const logout = () => {
    console.log("Déconnexion");
    isOpen.value = false;
};

// Init
onMounted(() => {
    setActiveFromRoute();
});
</script>



<style scoped>
.menu-content {
    position: fixed; /* occupe tout l'écran */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%; /* pleine hauteur */
    background: rgba(255, 255, 255, 0.7); /* léger voile blanc */
    backdrop-filter: blur(10px) grayscale(80%); /* flou + grayscale */
    -webkit-backdrop-filter: blur(10px) grayscale(80%); /* compatibilité Safari */
    
    display: flex;
    flex-direction: column;
    padding: 20px;

    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 999; /* au-dessus du contenu */
}

.menu-content.open {
    transform: translateX(0); /* animation slide-in */
}

.menu-section {
    margin-bottom: 10px;
}

.menu-section h3 {
    font-size: 20px;
    margin-bottom: 15px;
    color: #2c3e50;
    font-weight: bold;
}

.user-infos {
    display: flex;
    align-items: center;
    font-size: 14px;
    margin-top: 15px;
    color: #2c3e50;
}

.user-photo {
    height: 40px;
    width: 40px;
    background-color: chocolate;
    border-radius: 50%;
    margin-right: 12px;
}

.user-name-mail p {
    margin: 0;
}

.btn-log-out {
    width: 100%;
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 10px;
    margin-top: 20px;
    font-weight: bold;
    transition: 0.3s;
}

.btn-log-out:hover {
    background-color: #c0392b;
}

.btn-close {
    padding: 15px;
    border: 1px solid #2c3e50;
    margin-bottom: 15px;
    transition: all .2s ease-in;
}

.btn-close:hover {
    border: 1px solid black;
    background-color: #2c3e50;
}




/* Bouton hamburger */
.menu-toggle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;

    border: none;
    border-radius: 12px;
    cursor: pointer;


    font-size: 30px;

    background: rgba(255, 255, 255, 0.65);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);

    transition: transform 0.15s ease, box-shadow 0.2s ease, background 0.2s ease;
    color: #2c3e50;
}

.menu-toggle:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.menu-toggle i {
    font-size: 30px;
    line-height: 5;
}

/* Logo circulaire net */


@media (min-width: 990px) {
    .mobile-navbar {
        display: none;
    }
}
</style>