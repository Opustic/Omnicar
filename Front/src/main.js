// Importation de Bootstrap
import 'bootstrap-icons/font/bootstrap-icons.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import "bootstrap"

// Importation du toastifier
import Toast from 'vue-toastification'
import "vue-toastification/dist/index.css";


// importation de pinia
import { createPinia } from 'pinia';
import { useUiStore } from './store/ui';



// importation du router
import router from '@/router'


// CSS perso
import "@/assets/main.css"

// Ce qu'il faut pour démarrer la page
import { createApp, ref } from 'vue'
import App from './App.vue'



// utilisation de primevue
import PrimeVue from "primevue/config"
import Aura from "@primeuix/themes/aura"





// configs toaster
const toastOptions = {  position: "bottom-right",
    timeout: 5000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: true,
    closeButton: "button",
    icon: true,
    rtl: false
}




// création de l'app
const app = createApp(App)




// Pinia
const pinia = createPinia()
app.use(pinia)




// store global pour les loadings
const ui = useUiStore(pinia)




router.beforeEach((to, from, next) => {
    ui.globalLoading = true
    next()
})



router.afterEach(() => {
    setTimeout(() => {
        ui.globalLoading = false
    }, 500)
})




// l'app utilise router, pour ses routes
app.use(router)

// l'app utilise les toaster
app.use(Toast, toastOptions)


// utilisation de primevue
app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
        darkModeSelector: 'none' // Désactive complètement le mode sombre
        }
    }, 
    zIndex: {
        modal: 1100,
        overlay: 2000,
        menu: 2100,
        tooltip: 2200
    }
})

// montage de l'app
app.mount('#app')
