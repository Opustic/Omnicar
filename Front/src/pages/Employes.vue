
<template>

    <!-- Ajouter un nouveau chauffeur -->
    <Modale
        v-if="showAddNewChauffeur"
        :show="showAddNewChauffeur"
        title="Ajouter un nouveau chauffeur"
        @close="showAddNewChauffeur=false"
    >
        <form @submit.prevent="submitFormChauffeur" method="POST" enctype="multipart/form-data">
            <input type="text" name="nom_chauffeur" class="form-control mt-3" placeholder="Nom..." required>
            <input type="text" name="telephone_chauffeur" class="form-control mt-3" placeholder="Téléphone..." required>
            <input type="text" name="adresse_chauffeur" class="form-control mt-3" placeholder="Adresse..." required>
            <input type="email" name="email_chauffeur" class="form-control mt-3" placeholder="Email...">
            <input type="text" name="numero_permis_chauffeur" class="form-control mt-3" placeholder="Numero du permis">
            <input type="file" name="photo_chauffeur" accept="image/*" class="form-control mt-3" required>
            <button class="btn btn-primary mt-3" type="submit">Envoyer</button>
        </form>
    </Modale>

    

    <!-- Ajouter un nouveau controleur -->
    <Modale
        v-if="showAddNewControleur"
        :show="showAddNewControleur"
        title="Ajouter un nouveau controleur"
        @close="showAddNewControleur=false"
    >
        <form @submit.prevent="submitFormControleur" method="POST" enctype="multipart/form-data">
            <input type="text" name="nom_controleur" class="form-control mt-3" placeholder="Nom..." required>
            <input type="text" name="telephone_controleur" class="form-control mt-3" placeholder="Téléphone..." required>
            <input type="text" name="adresse_controleur" class="form-control mt-3" placeholder="Adresse..." required>
            <input type="email" name="email_controleur" class="form-control mt-3" placeholder="Email...">
            <input type="file" name="photo_controleur" accept="image/*" class="form-control mt-3" required>
            <button class="btn btn-primary mt-3" type="submit">Envoyer</button>
        </form>
    </Modale>





    <!-- Ajouter un nouveau mécanicien -->
    <Modale
        v-if="showAddNewMecanicien"
        :show="showAddNewMecanicien"
        title="Ajouter un nouveau mécanicien"
        @close="showAddNewMecanicien=false"
    >

        <form @submit.prevent="submitFormMecanicien" method="POST" enctype="multipart/form-data">
            <input type="text" name="nom_mecanicien" class="form-control mt-3" placeholder="Nom..." required>
            <input type="text" name="telephone_mecanicien" class="form-control mt-3" placeholder="Téléphone..." required>
            <input type="text" name="adresse_mecanicien" class="form-control mt-3" placeholder="Adresse..." required>
            <input type="email" name="email_mecanicien" class="form-control mt-3" placeholder="Email...">
            <input type="file" name="photo_mecanicien" accept="image/*" class="form-control mt-3" required>
            <button class="btn btn-primary mt-3" type="submit">Envoyer</button>
        </form>
    </Modale>


    <!-- Modale pour éditer un mécanicien -->
    <Modale
        v-if="showEditMecanicien"
        :show="showEditMecanicien"
        title="Modifier un employé"
        @close="showEditMecanicien=false"
    >
        <form enctype="multipart/form-data" @submit.prevent="submitUpdateMecanicien">
            <p class="fw-bold">Photo actuelle</p>
            <img v-if="employeToModify.photo" :src="employeToModify.photo" alt="Photo" width="300" class="img mb-3 rounded">
            <input type="text" class="form-control mb-3" name="nom" id="nom" :value="employeToModify.nom">
            <input type="text" class="form-control mb-3" name="telephone" id="telephone" :value="employeToModify.telephone">
            <input type="text" class="form-control mb-3" name="adresse" id="adresse" :value="employeToModify.adresse">
            <input type="text" class="form-control mb-3" name="email" id="email" :value="employeToModify.email">
            <input v-if="employeToModify.numero_permis !== undefined" type="text" class="form-control mb-3" name="numero_permis" id="numero_permis" :value="employeToModify.numero_permis">
            <input type="file" class="form-control mb-3" name="photo" id="photo">
            <button class="btn btn-primary" type="submit">Envoyer</button>

        </form>



        <!-- Virer l'employé -->
        <button class="mt-3 btn w-100 btn-outline-danger" @click="()=>submitDeleteMecanicien(employeToModify?.id)">
            Virer cet employé
        </button>


    </Modale>



    <!-- Ajouter une nouvelle équipe -->
    <Modale
        v-if="showAddNewEquipe"
        :show="showAddNewEquipe"
        title="Ajouter une équipe"
        @close="showAddNewEquipe=false"
    >
        <form @submit.prevent="submitFormEquipe" method="POST" enctype="multipart/form-data">
            <select name="chauffeur_id" id="chauffeur_id" class="form-select" required>
                <option value="" disabled selected>Choisissez un chauffeur</option>
                <option v-for="chauffeur in chauffeurs_dispos" :value="chauffeur.id" :key="chauffeur.telephone">
                    {{ chauffeur.nom }}
                </option>
            </select>
            <select name="controleur_id" id="controleur_id" class="form-select mt-3" required>
                <option value="" disabled selected>Choisissez un controleur</option>
                <option v-for="controleur in controleurs_dispos" :value="controleur.id" :key="controleur.telephone">
                    {{ controleur.nom }}
                </option>
            </select>
            <button class="btn btn-primary mt-3" type="submit">Envoyer</button>
        </form>
    </Modale>




    <!-- Afficher les infos détaillées de l'employé -->
    <Modale
        v-if="showEmployeModale"
        :show="showEmployeModale"
        title="Détails de l'employé"
        @close="showEmployeModale=false"
    >
        <EmployeLightBox
            :employe="employeToShow"
        />
    </Modale>



    <!-- Modale pour éditer un employé (chauffeur, controleur) -->
    <Modale
        v-if="showEditModale"
        :show="showEditModale"
        title="Modifier un employé"
        @close="showEditModale=false"
    >
        <form enctype="multipart/form-data" @submit.prevent="submitUpdateEmploye">
            <p class="fw-bold">Photo actuelle</p>
            <img v-if="employeToModify.photo" :src="employeToModify.photo" alt="Photo" width="100%" class="img mb-3 rounded">
            <input type="text" class="form-control mb-3" name="nom" id="nom" :value="employeToModify.nom">
            <input type="text" class="form-control mb-3" name="telephone" id="telephone" :value="employeToModify.telephone">
            <input type="text" class="form-control mb-3" name="adresse" id="adresse" :value="employeToModify.adresse">
            <input type="text" class="form-control mb-3" name="email" id="email" :value="employeToModify.email">
            <input v-if="employeToModify.numero_permis" type="text" class="form-control mb-3" name="numero_permis" id="numero_permis" :value="employeToModify.numero_permis">
            <input type="file" class="form-control mb-3" name="photo" id="photo">
            <button class="btn btn-primary" type="submit">Envoyer</button>
        </form>


        <!-- Virer l'employé -->
        <button v-if="employeToModify.numero_permis" class="mt-3 btn w-100 btn-outline-danger" @click="()=>submitDeleteChauffeur(employeToModify?.id)">
            Virer cet employé
        </button>


        <button v-else class="mt-3 w-100 btn btn-outline-danger" @click="()=>submitDeleteControleur(employeToModify?.id)">
            Virer cet employé
        </button>


    </Modale>




    <!-- Modale pour voir les informations de l'équipe -->
    <Modale
        v-if="showTeamInfo"
        :show="showTeamInfo"
        title="Informations de l'équipe"
        @close="showTeamInfo=false"
    >
        <TeamLightBox
            :team="teamToShow"
        />
    </Modale>



    <!-- Modale pour éditer les informations de l'équipe -->
    <Modale
        v-if="showEditTeam"
        :show="showEditTeam"
        title="Modifier l'équipe"
        @close="showEditTeam=false"
    >
        <form @submit.prevent="submitUpdateTeam">
            <select name="chauffeur_id" id="chauffeur" class="form-select mb-3" required>
                <option v-if="teamToModify.chauffeurs?.length > 0" :value="teamToModify.chauffeurs[0].id">
                    {{ teamToModify.chauffeurs[0]?.nom }}
                </option>

                <option v-else value="" disabled>Aucun chauffeur</option>

                <option v-for="chauffeur in chauffeurs_dispos" :key="chauffeur.telephone" :value="chauffeur.id">
                    {{ chauffeur.nom }}
                </option>
                
                <option v-if="chauffeurs_dispos.length === 0" disabled>Aucun chauffeur disponible</option>
            </select>


            <select name="controleur_id" id="controleur" class="form-select" required>
                <option v-if="teamToModify.controleurs?.length > 0" :value="teamToModify.controleurs[0].id">
                    {{ teamToModify.controleurs[0]?.nom }}
                </option>
                <option v-else value="" disabled>Aucun controleur</option>


                <option v-for="controleur in controleurs_dispos" :key="controleur.telephone" :value="controleur.id">
                    {{ controleur.nom }}
                </option>
                <option v-if="controleurs_dispos.length === 0" disabled>Aucun controleur disponible</option>
            </select>



            <button type="submit" class="btn btn-primary mt-2">Envoyer</button>
        </form>
        
    </Modale>




    <!-- Chauffeurs, Contrôleurs, Équipes -->
    <div class="container">

        <h1 class="mb-3 ms-3">Liste des employés</h1>

        <!-- Tab -->
        <ul class="nav nav-tabs custom-tabs ms-3">
            <li class="nav-item">
                <button class="nav-link active" id="chauffeurs-tab" data-bs-toggle="tab" data-bs-target="#chauffeurs" type="button" role="tab">
                    Chauffeurs
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" id="controleurs-tab" data-bs-toggle="tab" data-bs-target="#controleurs" type="button" role="tab">
                    Contrôleurs
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" id="equipes-tab" data-bs-toggle="tab" data-bs-target="#equipes" type="button" role="tab">
                    Équipes
                </button>
            </li>


            <li class="nav-item">
                <button class="nav-link" id="mecaniciens-tab" data-bs-toggle="tab" data-bs-target="#mecaniciens" type="button" role="tab">
                    Mécaniciens 
                </button>
            </li>
        </ul>

        <!-- Contenu -->
        <div class="tab-content mt-3">

            <!-- Affichage des chauffeurs -->
            <div class="tab-pane fade show active" id="chauffeurs" role="tabpanel">
                <button class="btn btn-primary mb-3 ms-2" @click="showAddNewChauffeur=true">
                    <i class="bi bi-plus-circle-fill"></i> Ajouter un chauffeur
                </button>

                <div v-if="chauffeurs?.length > 0" class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="width: 1000px;">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Nom</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                                <th>Email</th>
                                <th>Numero permis</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="chauffeur in chauffeurs" :key="chauffeur.telephone">
                                <ChauffeurPresentation
                                    :photo="chauffeur.photo"
                                    :nom="chauffeur.nom"
                                    :telephone="chauffeur.telephone"
                                    :adresse="chauffeur.adresse"
                                    :email="chauffeur.email"
                                    :numero_permis="chauffeur.numero_permis"
                                />
                                <td>
                                    <div class="d-flex">
                                        <button @click="voirEmploye(chauffeur)" class="btn btn-action-view"><i class="bi bi-eye-fill fs-3"></i></button>
                                        <button @click="modifierEmploye(chauffeur)" class="ms-2 btn btn-action-modify"><i class="bi bi-pencil-square fs-3"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else>
                    <h2 class="text-dark">Aucun chauffeur à afficher pour l'instant</h2>
                </div>
            </div>


            <!-- Affichage des contrôleurs -->
            <div class="tab-pane fade" id="controleurs" role="tabpanel">
                <button class="btn btn-primary mb-3 ms-2" @click="showAddNewControleur=true">
                    <i class="bi bi-plus-circle-fill"></i> Ajouter un controleur
                </button>

                <div v-if="controleurs?.length > 0" class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Nom</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="controleur in controleurs" :key="controleur.telephone">
                                <ControleurPresentation
                                    :nom="controleur.nom"
                                    :telephone="controleur.telephone"
                                    :adresse="controleur.adresse"
                                    :email="controleur.email"
                                    :photo="controleur.photo"
                                />
                                <td>
                                    <div class="d-flex">
                                        <button @click="voirEmploye(controleur)" class="btn btn-action-view"><i class="bi bi-eye-fill fs-3"></i></button>
                                        <button @click="modifierEmploye(controleur)" class="ms-2 btn btn-action-modify"><i class="bi bi-pencil-square fs-3"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else>
                    <h2 class="text-dark">Aucun controleur à afficher pour l'instant</h2>
                </div>
            </div>


            <!-- Affichage des équipes -->
            <div class="tab-pane fade" id="equipes" role="tabpanel">
                <button class="btn btn-primary mb-3 ms-2" @click="showAddNewEquipe=true">
                    <i class="bi bi-plus-circle-fill"></i> Ajouter une équipe
                </button>

                <div v-if="equipes?.length > 0" class="table-responsive">
                    <table class="table table-striped table-bordered" style="width: 1000px;">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Nom du chauffeur</th>
                                <th>Nom du Contrôleur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
    
                        <tbody>
                            <tr v-for="equipe in equipes" :key="equipe.id">
                                <TeamPresentTable
                                    :id="equipe.id"
                                    :chauffeur_name="equipe.chauffeurs?.[0]?.nom || 'Aucun chauffeur'"
                                    :controleur_name="equipe.controleurs?.[0]?.nom || 'Aucun contrôleur'"
                                    :photo_chauffeur="equipe.chauffeurs?.[0]?.photo"
                                    :photo_controleur="equipe.controleurs?.[0]?.photo"
                                />
                                <td>
                                    <div class="d-flex">
                                        <button @click="afficherEquipe(equipe)" class="btn btn-action-view"><i class="bi bi-eye-fill fs-3"></i></button>
                                        <button @click="editerEquipe(equipe)" class="ms-2 btn btn-action-modify"><i class="bi bi-pencil-square fs-3"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else>
                    <h2 class="text-dark">Aucune équipe à afficher pour l'instant</h2>
                </div>
            </div>


            <!-- Affichage des mécaniciens -->
            <div class="tab-pane fade" id="mecaniciens" role="tabpanel">
                <button class="btn btn-primary mb-3 ms-2" @click="showAddNewMecanicien=true">
                    <i class="bi bi-plus-circle-fill"></i> Ajouter un mécanicien
                </button>


                <table v-if="mecaniciens?.length > 0" class="table table-striped table-bordered table-responsive table-hover" style="width: 1000px;">

                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>    


                    <tbody>
                        <tr v-for="mecanicien in mecaniciens" :key="mecanicien.telephone">
                            <MecanicienPresentation
                            :photo="mecanicien.photo"
                            :nom="mecanicien.nom"
                            :telephone="mecanicien.telephone"
                            :adresse="mecanicien.adresse"
                            :email="mecanicien.email"
                            />
                            <td>
                            <div class="d-flex">
                                <button @click="voirEmploye(mecanicien)" class="btn btn-action-view">
                                <i class="bi bi-eye-fill fs-3"></i>
                                </button>
                                <button @click="modifierMecanicien(mecanicien)" class="ms-2 btn btn-action-modify">
                                <i class="bi bi-pencil-square fs-3"></i>
                                </button>
                            </div>
                            </td>
                        </tr>
                    </tbody>

                </table>
                
                <div v-else>
                    <h2 class="text-dark">
                        Aucun mécanicien à afficher pour l'instant
                    </h2>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script setup>
import ChauffeurPresentation from '@/components/ChauffeurPresentation.vue';
import ControleurPresentation from '@/components/ControleurPresentation.vue';
import MecanicienPresentation from '@/components/MecanicienPresentation.vue';
import TeamPresentTable from '../components/TeamPresentTable.vue';
import Modale from '@/components/Modale.vue';
import { ref, computed } from 'vue';
import EmployeLightBox from '@/components/EmployeLightBox.vue';
import TeamLightBox from '@/components/TeamLightBox.vue';
import { useToast } from 'vue-toastification';
import { useChauffeursStore } from '@/store/chauffeurs';
import { useControleursStore } from '@/store/controleurs';
import { useEquipeStore } from '@/store/equipes';
import { useMecaniciensStore } from '@/store/mecaniciens';



// Toaster
const toast = useToast();


// ======================================================
// Variable réactive pour afficher ou cacher les modales
// ======================================================
const showEditMecanicien = ref(false);
const showAddNewMecanicien = ref(false);
const showAddNewChauffeur = ref(false);
const showAddNewControleur = ref(false);
const showAddNewEquipe = ref(false);
const showEditModale = ref(false);
const showEmployeModale = ref(false);
const showTeamInfo = ref(false);
const showEditTeam = ref(false);




// =============================
// STORES PINIA
// =============================
const chauffeursStore = useChauffeursStore()
const controleursStore = useControleursStore()
const equipesStore = useEquipeStore()
const mecaniciensStore = useMecaniciensStore()


// chauffeurs
const chauffeurs = computed(()=>chauffeursStore.items)
const chauffeurs_dispos = computed(()=>chauffeursStore.disponibles)


// controleurs
const controleurs = computed(()=> controleursStore.items)
const controleurs_dispos = computed(()=> controleursStore.disponibles)

// equipes
const equipes = computed(()=> equipesStore.items)


// mecaniciens
const mecaniciens = computed(()=> mecaniciensStore.items)


// Équipe par défaut
const defaultTeam = {
    id: null,
    created_at: null,
    updated_at: null,
    chauffeurs: [{
        id: null,
        equipe_id: null,
        nom: '',
        telephone: '',
        adresse: '',
        email: '',
        photo: null,
        numero_permis: '',
        created_at: null,
        updated_at: null
    }],
    controleurs: [{
        id: null,
        equipe_id: null,
        nom: '',
        telephone: '',
        adresse: '',
        email: '',
        photo: null,
        created_at: null,
        updated_at: null
    }]
};



// Constante pour l'employé à afficher
const employeToShow = ref({});



// Constante pour l'employé à modifier
const employeToModify = ref({});



// Constante pour l'équipe à afficher
const teamToShow = ref({});


// Équipe à modifier
const teamToModify = ref(defaultTeam);



// Fonctions pour afficher et modifier
const voirEmploye = (employe) => {
    employeToShow.value = employe;
    showEmployeModale.value = true;
};


const modifierEmploye = (employe) => {
    employeToModify.value = employe;
    showEditModale.value = true;
};


const modifierMecanicien = (employe) => {
    employeToModify.value = employe;
    showEditMecanicien.value = true;
};


const afficherEquipe = (equipe) => {
    teamToShow.value = equipe;
    showTeamInfo.value = true;
};

const editerEquipe = (equipe) => {
    showEditTeam.value = true;
    teamToModify.value = equipe;
};



// ==========================================
// Fonctions pour soumettre les formulaires
// ==========================================


// ajouter un chauffeur
const submitFormChauffeur = async (event) => {
    const form = event.target;
    const formData = new FormData(form);
    
    try {
        chauffeursStore.addChauffeur(formData)
        form.reset()
        showAddNewChauffeur.value = false

    }catch (exception) {
        alert ("Une erreur est survenue")

    }

};

// ajouter un controleur
const submitFormControleur = async (event) => {
    const form = event.target;
    const formData = new FormData(form);

    try {

        controleursStore.addControleur(formData)
        form.reset();
        showAddNewControleur.value = false;

    } catch (exception) {
        console.log("Erreur lors de l'ajout d'un nouveau controleur  : ", exception)

    }

};


// ajouter un mécanicien
const submitFormMecanicien = async (event) => {
    const form = event.target;
    const formData = new FormData(form);
    
    

    try {
        mecaniciensStore.addMecanicien(formData)
        form.reset()
        showAddNewMecanicien.value = false
    } catch (exception) {
        console.log("Erreur lors de l'ajout d'un nouveau mécanicien : ", exception)
    }
}


// ajouter une équipe
const submitFormEquipe = async (event) => {
    const form = event.target;
    const formData = new FormData(form);
    try {

        equipesStore.addEquipe(formData)
        showAddNewEquipe.value = false

    } catch (error) {

        console.log("Erreur lors de l'ajout de l'équipe : ", error)

    }
};


// modifier un employé (chauffeur | controleur)
const submitUpdateEmploye = async (event) => {


    const form = event.target;
    const formData = new FormData(form);


    // override du post en put
    formData.append('_method', 'PUT');



    // verification de l'employé pour envoyer à la bonne URL
    if (!formData.get("numero_permis")) {

        try {

            controleursStore.updateControleur(formData, employeToModify.value.id)
            showEditModale.value = false

        }catch (exception) {

            console.log("Une erreur est survenue ", exception)

        }
        
    } else {

        try {

            chauffeursStore.updateChauffeur(formData, employeToModify.value.id)
            showEditModale.value = false

        }catch (exception){
            
            console.log("Une erreur est survenue : ", exception)

        }

        
    }

};



// mettre à jour les informations du mécanicien
const submitUpdateMecanicien = async (event) => {
    const form = event.target;
    const formData = new FormData(form);

    // override du post en put
    formData.append("_method", "PUT");

    try {

        mecaniciensStore.updateMecanicien(formData, employeToModify.value.id)
        showEditMecanicien.value = false

    } catch (exception) {

        console.log("Une erreur est survenue : ", exception)

    } finally {

        showEditMecanicien.value = false

    }
}


// modifier une équipe
const submitUpdateTeam = async (event) => {
    const form = event.target;
    const formData = new FormData(form);


    // overide du post en put
    formData.append("_method", "PUT");

    try {

        equipesStore.updateEquipe(formData, teamToModify.value.id)
        showEditTeam.value = false
    
    }catch (exception) {

        console.log("Un Erreur lors de la modification de l'équipe : ", exception)

    }

    
};


// supprimer un chauffeur
const submitDeleteChauffeur = async (id) => {

    try {

        await chauffeursStore.deleteChauffeur(id)
        await chauffeursStore.fetchAll()
        showEditModale.value = false


    }catch (exception) {

        console.error ("Erreur : ", exception)
        
    }

}


// supprimer un controleur
const submitDeleteControleur = async(id) => {

    try {

        await controleursStore.deleteControleur(id)
        await controleursStore.fetchAll()
        showEditModale.value = false

    }catch (exception) {

        console.error("Erreur : ", exception)

    }

}

// supprimer un mecanicien
const submitDeleteMecanicien = async (id) => {

    try {

        await mecaniciensStore.destroyMecanicien(id)
        await mecaniciensStore.fetchAll()

    }catch (exception) {

        console.error("Erreur : ", exception)
        
    }

}

</script>

<style scoped>
h1 {
    font-family: var(--font-title-small);
    color: black;
}

.btn-add-new-team {
    position: absolute;
    bottom: 20px;
    right: 20px;
    font-size: 24px;
}

.btn-action-view {
    background-color: var(--color-accent-green);
    color: white;
    transition: all 0.2s ease;
}

.btn-action-view:hover {
    color: var(--color-accent-green);
    background-color: white;
    border: 1px solid var(--color-accent-green);
}

.btn-action-modify {
    color: white;
    background-color: var(--color-accent-red);
    transition: all 0.2s ease;
}

.btn-action-modify:hover {
    background-color: white;
    color: var(--color-accent-red);
    border: 1px solid var(--color-accent-red);
}
</style>
