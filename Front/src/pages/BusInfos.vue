<template>

    <!-- Modale pour modifier les informations du véhicule -->
    <Modale
        title="Modifier les informations du vehicule"
        :show="showUpdateVehicle"
        @close="showUpdateVehicle=false"
    >
        <form @submit.prevent="submitModifierVehicule">

            <input type="text" class="form-control mb-2" name="immatriculation" placeholder="Immatriculation ...">

            <input type="file" name="photo" id="photo" class="form-control">

            <button type="submit" class="btn btn-primary mt-2">Envoyer</button>

        </form>
    </Modale>


    <!-- Modale pour modifier les informations de l'équipe -->
    <Modale
        title="Modifier les informations de l'équipe"
        :show="showUpdateTeam"
        @close="showUpdateTeam=false"
    >
        <form @submit.prevent="submitUpdateTeam">
            <select name="chauffeur_id" id="chauffeur" class="form-select mb-3" required>
                <option v-if="equipe?.chauffeurs?.length > 0" :value="equipe.chauffeurs[0]?.id">
                    {{ equipe.chauffeurs[0]?.nom }} (Actuel)
                </option>
                <option v-else value="" disabled>Aucun chauffeur</option>

                <option v-for="chauffeur in chauffeurs_dispos" :key="chauffeur.telephone" :value="chauffeur.id">
                    {{ chauffeur.nom }}
                </option>
                
                <option v-if="chauffeurs_dispos?.length === 0" disabled>Aucun chauffeur disponible</option>
            </select>

            <select name="controleur_id" id="controleur" class="form-select" required>
                <option v-if="equipe?.controleurs?.length > 0" :value="equipe.controleurs[0]?.id">
                    {{ equipe.controleurs[0]?.nom }} (Actuel)
                </option>
                <option v-else value="" disabled>Aucun controleur</option>

                <option v-for="controleur in controleurs_dispos" :key="controleur.telephone" :value="controleur.id">
                    {{ controleur.nom }}
                </option>
                <option v-if="controleurs_dispos?.length === 0" disabled>Aucun controleur disponible</option>
            </select>

            <button type="submit" class="btn btn-primary mt-2">Envoyer</button>
        </form>

    </Modale>



    <!-- Modale pour faire un versement -->
    <Modale
        title="Nouveau versement"
        :show="showNewVersement"
        @close="showNewVersement=false"
    >
    
        <!-- Formulaire pour faire un versement -->
        <form @submit.prevent="submitVersement">

            <!-- Montant chauffeur -->
            <fieldset class="border p-3 rounded-1 mb-3">
                <legend class="fs-6 fw-bold">
                    Montant du chauffeur
                </legend>

                
                <input type="number" step="1000" placeholder="Montant versé par le chauffeur" class="form-control" name="montant_chauffeur" required>

            </fieldset>


            <!-- Montant controleur -->
            <fieldset class="border p-3 rounded-1 mb-3">
                <legend class="fs-6 fw-bold">
                    Montant du controleur
                </legend>

                <input type="number" step="1000"  placeholder="Montant versé par le controleur" class="form-control" name="montant_controleur" required>
                
            </fieldset>

            <!-- Date du versement -->
            <fieldset class="border p-3 rounded-1 mb-3">
                <legend class="fs-6 fw-bold">
                    Date du versement (optionnelle)
                </legend>
                <input type="date" class="form-control" name="date_versement">
            </fieldset>

            
            <button type="submit" class="btn btn-primary">
                Envoyer
            </button>
        
        </form>


    </Modale>


    <!-- Modale pour modifier un versement -->
    <Modale
        title="Modifier versement"
        :show="showUpdateVersement"
        @close="showUpdateVersement=false"
    >
    
        <!-- Formulaire pour faire un versement -->
        <form @submit.prevent="submitUpdateVersement">

            

            <fieldset class="border p-3 rounded-1 mb-3">

                <legend class="fs-6 fw-bold">
                    Modifier le montant
                </legend>

                <input type="number" step="1000"  placeholder="Montant ..." class="form-control" name="montant" required>
                
            </fieldset>


            <button type="submit" class="btn btn-primary">
                Envoyer
            </button>
        
        </form>



    </Modale>



    <!-- Modale pour assigner une équipe -->
    <Modale
        title="Assigner une équipe"
        :show="showAddTeam"
        @close="showAddTeam=false"
    >
        <form @submit.prevent="submitAddTeam">



            <select name="equipe_id" id="equipe_id" class="form-select"> 
                <option value="">Sélectionnez une équipe</option>
                <option
                    v-for="equipe in equipes_dispo"
                    :key="equipe?.id"
                    :value="equipe?.id">
                    {{ equipe?.chauffeurs[0]?.nom }} & {{ equipe?.controleurs[0]?.nom }}
                </option>

                <option v-if="!equipes_dispo?.length" disabled>Aucune équipe disponible</option>
            </select>


            <!-- Soumettre le formulaire -->
            <button class="btn btn-primary mt-3" type="submit" :disabled="!equipes_dispo?.length">Envoyer</button>

        </form>
    </Modale>


    <!-- Modale pour changer le statut -->
    <Modale
        title="Changer le statut du vehicule"
        :show="showChangeStatus"
        @close="showChangeStatus=false"
    >
        <form @submit.prevent="submitChangeStatus">

            <select name="statut" id="statut" class="form-select"> 
                <option value="" disabled>Changer le statut</option>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
            </select>


            <!-- Soumettre le formulaire -->
            <button class="btn btn-primary mt-3" type="submit">Envoyer</button>

        </form>
    </Modale>


    <!-- Nouvelle réparation -->
    <Modale
        title="Nouvelle réparation"
        :show="showNewReparation"
        @close="showNewReparation=false"

    >

    <form @submit.prevent="submitAddPanne" class="mt-3">

        <!-- Cout -->
        <input 
            type="number" 
            step="1000" 
            name="cout" 
            class="form-control mt-2"
            placeholder="Coût de la réparation..." 
            required
        >

        <!-- Cout -->
        <input 
            type="number" 
            step="1000" 
            name="main_doeuvre" 
            class="form-control mt-2"
            placeholder="Main d'oeuvre" 
            required
        >


        <!-- Mécanicien -->
        <select 
            name="mecanicien_id" 
            id="mecanicien_id" 
            class="form-select mt-2" 
            required
        >
            <option value="" disabled selected>-- Sélectionnez un mécanicien --</option>
            <option 
                v-for="mecanicien in mecaniciens" 
                :key="mecanicien.telephone"
                :value="mecanicien.id"
            >
                {{ mecanicien.nom }}
            </option>
        </select>


        <!-- Motif -->
        <Dropdown
            name="description"
            v-model="selectedMotifReparation"
            :options="motifsReparation"
            optionLabel="name"
            optionValue="code"
            placeholder="Motif..."
            filter
            showClear
            appendTo="body"
            class="w-100 mt-2"
        />


        <!-- Champ caché pour que FormData inclue la valeur de la description -->
        <input type="hidden" name="description" :value="selectedMotifReparation" />


        <!-- Bouton -->
        <button type="submit" class="btn btn-primary mt-3 w-100">
            Envoyer
        </button>

        </form>


    </Modale>



    <!-- Nouvelle mise à l'arrêt -->
    <Modale
        title="Mise à l'arrêt"
        :show="showNewArret"
        @close="showNewArret=false"

    >

        <form @submit.prevent="submitAddArret">


            <select name="motif" id="motif" class="form-select">

                <option value="">Sélectionnez un motif</option>

                <option 
                    v-for="motif in motifs"
                    :value="motif.code"
                    >
                {{ motif.name }}
                </option>

            </select>



            <button type="submit" class="btn btn-primary mt-2">Envoyer</button>

        </form>


    </Modale>




    <!-- Changer le statut d'une réparation -->
    <Modale
        title="Réparation"
        :show="showRepairModale"
        @close="showRepairModale=false"

    >

        <RepairCard
            :mechanic-name="repairToView?.mecanicien?.nom"
            :user-image="repairToView?.mecanicien?.photo"
            :status="repairToView?.statut"
            :repair-cost="repairToView?.cout"
            :main_doeuvre="repairToView?.main_doeuvre"
            :start-date="repairToView?.created_at.slice(0, 10)"
        />

        <form class="mt-4" @submit.prevent="submitChangeRepairStatus">
            
            
            <h5 class="text-dark text-center">Changer statut</h5>

            <div class="d-flex p-2">

                <select name="statut" id="statut" class="form-select me-1">

                    <option value="annulé">Annulé</option>
                    <option value="en cours">En cours</option>
                    <option value="terminé">Terminé</option>
    
                </select>
    
                <Button
                    label="Envoyer"
                    severity="secondary"
                    type="submit"
                />
            </div>

        </form>

    </Modale>


    <!-- Messages d'erreur -->
    <Modale
        title="Alerte"
        :show="dialogVisible"
        @close="dialogVisible=false"

    >

        <h4 class="text-danger">
            {{ dialogMessage }}
        </h4>

        
    </Modale>



    <!-- Créer une nouvelle équipe -->
    <Modale
        title="Créer l'équipe"
        :show="showCreateTeam"
        @close="showCreateTeam=false"   
    >

        <form @submit.prevent="submitCreateTeamToAssign">

            <fieldset class="border p-3 rounded-1 mb-3">
                <legend class="fs-6 fw-bold">
                    Informations du chauffeur
                </legend>

                <input type="text" placeholder="Nom" class="form-control" name="nom_chauffeur" required>
                <input type="text" placeholder="Adresse" class="form-control mt-2" name="adresse_chauffeur" required>
                <input type="text" placeholder="Telephone" class="form-control mt-2" name="telephone_chauffeur" required>
                <input type="email" placeholder="Mail" class="form-control mt-2" name="email_chauffeur">
                <input type="text" placeholder="Permis de conduire" class="form-control mt-2" name="numero_permis_chauffeur">
                <input type="file" name="photo_chauffeur" accept="image/*" class="form-control mt-3" required>


            </fieldset>


            <fieldset class="border p-3 rounded-1 mb-3">
                <legend class="fs-6 fw-bold">
                    Informations du controleur
                </legend>

                <input type="text" placeholder="Nom" class="form-control" name="nom_controleur" required>
                <input type="text" placeholder="Adresse" class="form-control mt-2" name="adresse_controleur" required>
                <input type="text" placeholder="Telephone" class="form-control mt-2" name="telephone_controleur" required>
                <input type="email" placeholder="Mail" class="form-control mt-2" name="email_controleur">
                <input type="file" name="photo_controleur" accept="image/*" class="form-control mt-3" required>

            </fieldset>





            <button type="submit" class="btn btn-primary">
                Envoyer
            </button>
        
        </form>



    </Modale>

    

    <!-- Voir les performances d'un employé -->
    <Modale
        title="Performances"
        :show="showPerfsDetails"
        @close="showPerfsDetails=false"   
    >

        <div class="employeToSee">
            
            <img :src="employeToSeePerfs?.photo" alt="">
            <h3 class="text-dark">{{ employeToSeePerfs?.nom }}</h3>
            <h6>{{ employeToSeePerfsRole }}</h6>

        </div>


        <!-- total des versements -->
        <div class="d-flex justify-content-center align-items-center">
            <!-- total des versements du mois -->
            <div class="label-ca mt-2">
                Total du mois : {{ formatCFA(totalEmployeToSeePerfs) }}
            </div>
        </div>


        <!-- salaire actuel -->
        <div class="d-flex justify-content-center align-items-center">
            <div class="actual-salary">
                Salaire actuel : {{ formatCFA(employeToSeePerfsSalary) }}
            </div>

        </div>
        
        <div class="d-flex justify-content-center align-items-center mt-3">
            <button class="btn btn-primary" @click="()=>payer_employe_to_see_perfs()">
                Payer
            </button>
        </div>
        
        <!-- suivi des performances -->
        <div class="container mt-3">

            <div class="card">

                <h4 class="text-dark text-center">
                    Évolution versements
                </h4>

                <!-- graphique des performances -->
                <Chart type="bar" :data="chartData_perfs" :options="chartOptions_perfs" class="h-[30rem]" />

            </div>
        </div>
            



        <div class="container d-flex justify-content-center align-items-center mt-3">

            <div>

                <hr>

                <h4 class="text-dark text-center">Historique de versements</h4>

                <PayementItem
                    v-for="versement in employeToSeePerfsHistorique"
                    label="Versement"
                    :amount="versement?.montant"
                    :date="versement?.date_versement.slice(0, 10)"
                    :modifier="()=>modifier_versement(versement)"
                    solo="true"

                />

            </div>

        </div>



    </Modale>


    <!-- Voir les performances d'un employé -->
    <Modale
        title="Payer l'employé"
        :show="showPayer"
        @close="showPayer=false"   
    >


        <button class="btn btn-primary btn-return mb-3" @click="showPayer=false; showPerfsDetails=true">
            <i class="bi bi-caret-left-fill"></i>
        </button>    


        <form @submit.prevent="submitPayerEmployeToSeePerfs">

            <input type="number" name="montant" step="1000" class="form-control" :placeholder="`Maximum ${employeToSeePerfsSalary}`" required>

            <input type="hidden" name="categorie" value="salaire">

            <input type="hidden" name="employe_id" :value="employeToSeePerfs?.employe_id">

            <button type="submit" class="btn btn-primary mt-2">Envoyer</button>

        </form>

        <hr>

        <h1>Historique</h1>

        <PayementItem
            v-for="versement in historique_salaire"
            label="Salaire"
            :amount="versement?.montant"
            :date="versement?.created_at.slice(0, 10)"    
        />
    



    </Modale>

    


    <div class="container bus-infos">

        <!-- Contenu de la page -->
        <div class="mt-3">
    
            <!-- Présentation du vehicule avec possibilité de modifier ses infos -->
            <div class="">
    
                <!-- Bouton pour retourner en arrière -->
                <RouterLink to="/mesbus" class="btn btn-return mb-3" style="text-decoration: none;">
                    <i class="bi bi-caret-left-fill"></i> Retour
                </RouterLink>
    
    
                <!-- Présentation du vehicule -->
                <h2 class="text-dark"> 
                    Suivi du bus : {{ vehicule?.immatriculation }}
                </h2>
                
    
                <!-- Boutons pour modifier les infos du vehicule -->
                <button class="btn btn-modifier-infos" @click="showUpdateVehicle=true"><i class="bi bi-pencil-square"></i> Modifier informations</button>
    
    
                <!-- Bouton pour faire un nouveau versement -->
                <button class="btn btn-outline-primary ms-3" @click="showNewVersement=true"><i class="bi bi-cash-stack"></i> 
                    Nouveau  versement
                </button>
            </div>
    
    
    
    
            <!-- Image du vehicule et son équipe -->
            <div class="">
                <div class="row gx-5 gy-2">
                    <div class="col-md-12 col-lg-7"> 
        
                        <!-- Image du véhicule -->
                        <div class="vehicule-image mt-3 position-relative">
                            <img :src="vehicule?.photo" alt="Photo du vehicule" class="img-fluid">
        
                            <div class="black position-absolute top-0 start-0 z-3 p-4">
        
                                <div class="actions">
                                    <span class="btn text-light me-2" :class="statusClass">{{ statusText }}</span>
    
                                    <button class="btn btn-success" @click="showChangeStatus=true">Changer le statut</button>
                                </div>
    
    
                                <!-- total des versements du mois -->
                                <div class="label-ca mt-2">
                                    Total du mois : {{ formatCFA(ca_du_mois) }}
                                </div>
    
                            </div>
        
                        </div>
                    </div>
        
    
                    <!-- Equipe du vehicule -->
                    <div class="col-md-12 col-lg-4 mt-3">
    
    
                        <div v-if="equipe">
                            <button class="btn btn-primary ms-3" @click="showUpdateTeam=true"><i class="bi bi-pencil-square"></i> Modifier l'équipe</button>
            
    
                            <!-- affichage du chauffeur -->
                            <div v-if="equipe?.chauffeurs">
                                
                                <StaffCard
                                    :name="equipe?.chauffeurs[0]?.nom"
                                    :permit-number="equipe?.chauffeurs[0]?.numero_permis"
                                    :photo="equipe?.chauffeurs[0]?.photo"
                                    :onViewClick="()=>voirPerfsChauffeur()"
                                    role="Chauffeur"
                                />
    
                            </div>
    
                            <div v-else>
                                Aucun chauffeur
                            </div>
            
    
                            <!-- affichage du controleur -->
                            <div v-if="equipe?.controleurs">
    
                                <StaffCard
                                    :name="equipe?.controleurs[0]?.nom"
                                    :permit-number="equipe?.controleurs[0]?.numero_permis"
                                    :photo="equipe?.controleurs[0]?.photo"
                                    :onViewClick="()=>voirPerfsControleur()"
                                    role="Controleur"
                                />
    
                            </div>
                            <div v-else>
    
                                Aucun controleur
    
                            </div>
            
                        </div>
    
    
                        <p v-else class="text-danger fs-5">
                            Aucune équipe n'est assignée à ce bus
                            <br>
    
                            <!-- assigner ou créer l'équipe -->
                            <button @click="showAddTeam=true" class="btn btn-primary">Assigner une équipe</button>
                            <button @click="showCreateTeam=true" class="btn btn-primary ms-2">Créer l'équipe</button>
                        </p>
    
                    </div>
                </div>
            </div>
            
    
    
    
            <div class="row mt-4">
                <!-- Réparations, pannes, mise à l'arrêt -->
                <div class="col-md-12 col-lg-6">
    
    
                    <!-- Tab -->
                    <ul class="nav nav-tabs custom-tabs ms-3">
                        <li class="nav-item">
                            <button class="nav-link active" id="reparations-tab" data-bs-toggle="tab" data-bs-target="#reparations" type="button" role="tab">
                            Réparations
                            </button>
                        </li>
                        
                        <li class="nav-item">
                            <button class="nav-link" id="arret-tab" data-bs-toggle="tab" data-bs-target="#arret" type="button" role="tab">
                            Mise à l’arrêt
                            </button>
                        </li>
                    </ul>
    
    
                    <!-- Contenu -->
                    <div class="tab-content mt-3">
    
    
                        <!-- Affichage des réparations du Véhicule -->
                        <div class="tab-pane fade show active" id="reparations" role="tabpanel">
    
                            <button class="btn btn-success ms-3" @click="showNewReparation=true">Nouvelle réparation</button>
    
    
                            <!-- nbre de réparations -->
                            <div class="nbre-reparation success-outline ms-3 mt-2">
                                <i class="bi bi-tools"></i>
                                <span>Nombre des réparations effectuées : {{ nbre_reparations }}</span>
                            </div>
    
    
                            
                            
                            <div v-if="reparations?.length>0">
                                
                                
                                <!-- Piechart de toutes les mises à l'arrêt -->
                                <div class="card d-flex justify-content-center mt-2">
                                    <Chart type="pie" :data="chartData_reparations" :options="chartOptions_reparations" class="w-full md:w-[30rem]" />
                                </div>
                                
                                <!-- dérouler -->
                                <button v-if="showMoreRepairs===false" class="btn btn-primary mt-2 ms-3" @click="showMoreRepairs=true">
                                    <i class="bi bi-arrow-down-square-fill"></i> Dérouler
                                </button>
        
        
                                <!-- enrouler -->
                                <button v-if="showMoreRepairs===true" class="btn btn-primary mt-2 ms-3" @click="showMoreRepairs=false">
                                    <i class="bi bi-arrow-up-square-fill"></i> Enrouler
                                </button>
    
    
                                <div v-if="showMoreRepairs===true">
                                    
                                    <RepairCard
                                        v-for="reparation in reparations"
                                        :mechanic-name="reparation.mecanicien?.nom"
                                        :user-image="reparation.mecanicien?.photo"
                                        :status="reparation?.statut"
                                        :label="reparation?.description"
                                        :repair-cost="reparation?.cout"
                                        :main_doeuvre="reparation?.main_doeuvre"
                                        :start-date="reparation?.created_at.slice(0, 10)"
                                        :endDate = "reparation?.updated_at.slice(0, 10)"
                                        :view-repair="() => repairView(reparation)"
                                    />
    
                                </div>
    
    
    
                            </div>
    
    
                            <div class="container" v-else>
    
                                <p class="text-indisponible">
                                    <i class="bi bi-folder-x fs-2"></i>
                                    Aucune réparation
                                </p>
    
                            </div>
    
                        </div>
    
                        
                        <!-- Affichage des mises à l'arrêt -->
                        <div class="tab-pane fade" id="arret" role="tabpanel">
    
                            <button class="btn btn-success ms-2" @click="showNewArret=true">Nouvel arrêt</button>
    
                            
                            
                            <div v-if="arrets?.length">
                                
                                
                                <!-- nombre total des arrêts -->
                                <div class="total-arrets outline-danger mt-2 ms-2">
                                    <i class="bi bi-exclamation-octagon"></i>
                                    <span>Nombre total d'arrêts : {{ nbre_arrets }}</span>
                                </div>
    
        
                                <!-- Piechart de toutes les mises à l'arrêt -->
                                <div class="card d-flex justify-content-center mt-2">
                                    <Chart type="pie" :data="chartData_arrets" :options="chartOptions_arrets" class="w-full md:w-[30rem]" />
                                </div>
    
                                <!-- dérouler -->
                                <button v-if="showMoreStops===false" class="btn btn-primary mt-2" @click="showMoreStops=true">
                                    <i class="bi bi-arrow-down-square-fill"></i> Dérouler
                                </button>
    
                                <!-- enrouler -->
                                <button v-if="showMoreStops===true" class="btn btn-primary mt-2" @click="showMoreStops=false">
                                    <i class="bi bi-arrow-up-square-fill"></i> Enrouler
                                </button>
    
    
                                <!-- liste de toutes les mises à l'arrêt -->
                                <div v-if="showMoreStops===true">
                                    <StopCard
                                        v-for="arret in arrets"
                                        :key="arret.created_at"
                                        :stop-label="arret.motif"
                                        :stop-date="arret.created_at.slice(0, 10)"
                                    />
                                </div>
    
                            </div>
    
    
                            <div v-else>
    
                                <p class="text-indisponible">
                                    <i class="bi bi-folder-x fs-2"></i>
                                    Aucun arrêt
                                </p>
    
                            </div>
    
                            
                        </div>
                    </div>
                </div>
    
    
                <!-- Historique des versements -->
                <div class="col-md-12 col-lg-6">
                    <div class="p-3">
                        <h4 class="text-dark">Historique des versements</h4>
                        <p>Gardez un œil sur chaque montant versé pour ce bus. Cet historique vous
        permet de suivre vos revenus en toute clarté, à tout moment.</p>
                        
                        <div class="card historique">
    
                            <PayementItem
                                v-for="versement in versements"
                                    label="Versement"
                                    :key="versement?.date"
                                    :amount="versement?.total"
                                    :date="versement?.date_versement"
                            />
    
                            <p v-if="versements?.length===0" class="text-danger fs-5">
                                Aucun versement pour le moment
                            </p>
    
    
                            <RouterLink v-if="versements?.length>5" :to="{name:'historique', params:{id:props.id}}">
                                    <button class="btn btn-modifier-infos mt-3" style="max-width: 150px;">
                                    <i class="bi bi-caret-down-square-fill"></i> Voir plus
                                </button>
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>
    
    
            
        </div>
    
    
    
    
        <!-- totaux mensuels -->
        <section class="totaux-mensuels p-2">
            
    
            <div class="card">
    
                <h4 class="text-dark m-3">
                    Total des versements
                </h4>
    
    
                <!-- navigation -->
                <div class="ms-3">
                    <Button
                        label="Mensuel"
                        severity="secondary"
                        @click="value_totaux = '0'" 
                        :outlined="value_totaux !== '0'"
                    />
    
                    <Button
                        class="ms-1"
                        label="Cumulé"
                        severity="secondary"
                        @click="value_totaux = '1'" 
                        :outlined="value_totaux !== '1'"
                    />
                </div>
    
                <Tabs v-model:value="value_totaux">
                
                    <TabPanels>
    
                        <TabPanel value="0">
                            <div class="card">
                                <Chart
                                    type="bar"
                                    :data="chartData_mensuel"
                                    :options="chartOptions_mensuel"
                                />
                            </div>
                        </TabPanel>
    
                        <TabPanel value="1">
                            <div class="card">
                                <Chart
                                    type="bar"
                                    :data="chartData_cumule"
                                    :options="chartOptions_cumule"
                                />
                            </div>
    
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
    
        </section>
        
    
    
    
        <!-- Comparaison -->
        <section class="comparaison p-2">
            
    
            <div class="card">
    
                <h4 class="text-dark m-3">
                    Objectifs VS Réalisations
                </h4>
    
    
                <!-- navigation -->
                <div class="ms-3">
        
                    <Button
                        label="Aujourd'hui"
                        severity="secondary"
                        @click="value_comparaison = '0'" 
                        :outlined="value_comparaison !== '0'"
                    />
    
                    <Button
                        class="ms-1"
                        label="Cette semaine"
                        severity="secondary"
                        @click="value_comparaison = '1'" 
                        :outlined="value_comparaison !== '1'"
                    />
    
                    <Button
                        class="ms-1"
                        label="Ce mois-ci"
                        severity="secondary"
                        @click="value_comparaison = '2'" 
                        :outlined="value_comparaison !== '2'"
                    />
                </div>
    
                <Tabs v-model:value="value_comparaison">
                
                    <TabPanels>
    
                        <!-- aujourd'hui -->
                        <TabPanel value="0">
                            <div class="mb-3">
    
                                <span class="border p-2 rounded objectif">
                                    Objectif : {{ formatCFA(today_objectifs?.objectif) }}
                                </span>
    
                                <span class="border p-2 rounded ms-2 realise">
                                    Réalisé  : {{ formatCFA(today_objectifs?.realise) }}
                                </span>
    
                                <span class="border p-2 rounded ms-2 ecart">
                                    Ratio    : {{ today_objectifs?.taux }}%
                                </span>
    
    
                            </div>
                            <div class="card">
    
    
                                <Chart
                                    type="bar"
                                    :data="chartData_today"
                                    :options="chartOptions_today"
                                />
    
                            </div>
                        </TabPanel>
    
    
                        <!-- cette semaine -->
                        <TabPanel value="1">
    
                            <div class="mb-3">
    
                                <span class="border p-2 rounded objectif">
                                    Objectif : {{ formatCFA(hebdo_objectifs?.objectif) }}
                                </span>
    
                                <span class="border p-2 rounded ms-2 realise">
                                    Réalisé  : {{ formatCFA(hebdo_objectifs?.realise) }}
                                </span>
    
                                <span class="border p-2 rounded ms-2 ecart">
                                    Ratio    : {{ hebdo_objectifs?.taux }} %
                                </span>
    
    
                            </div>
    
                            <div class="card">
                                <Chart
                                    type="bar"
                                    :data="chartData_hebdo"
                                    :options="chartOptions_hebdo"
                                />
                            </div>
    
                        </TabPanel>
    
    
                        <!-- Ce mois-ci -->
                        <TabPanel value="2">
                            <div class="mb-3">
    
                                <span class="border p-2 rounded objectif">
                                    Objectif : {{ formatCFA(mois_objectifs?.objectif) }}
                                </span>
    
                                <span class="border p-2 rounded ms-2 realise">
                                    Réalisé  : {{ formatCFA(mois_objectifs?.realise) }}
                                </span>
    
                                <span class="border p-2 rounded ms-2 ecart">
                                    Ratio    : {{ mois_objectifs?.taux }} %
                                </span>
    
    
                            </div>
    
                            <div class="card">
                                <Chart
                                    type="bar"
                                    :data="chartData_mois"
                                    :options="chartOptions_mois"
                                />
                            </div>
                        </TabPanel>
    
    
                    </TabPanels>
                </Tabs>
            </div>
    
        </section>

    </div>    



    
</template>




<script setup>

import Modale from '@/components/Modale.vue';
import PayementItem from '@/components/PayementItem.vue';
import RepairCard from '@/components/RepairCard.vue';
import StaffCard from '@/components/StaffCard.vue';
import StopCard from '@/components/StopCard.vue';
import { useArretsStore } from '@/store/arrets';
import { useEquipeStore } from '@/store/equipes';
import { useMecaniciensStore } from '@/store/mecaniciens';
import { useReparationsStore } from '@/store/reparations';
import { useVehiculesStore } from '@/store/vehicules';
import { useVersementsStore } from '@/store/versements';
import { ref, computed, onMounted} from 'vue';
import { RouterLink } from 'vue-router';
import { useToast } from 'vue-toastification';



// composants primevue
import Chart from 'primevue/chart';
import { useChauffeursStore } from '@/store/chauffeurs';
import { useControleursStore } from '@/store/controleurs';
import Button  from "primevue/button"
import Tabs from 'primevue/tabs';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import Dropdown from 'primevue/dropdown'


import { formatCFA } from '@/utils/format';
import { useDepensesStore } from '@/store/depenses';

// toaster
const toast = useToast()


// pour les dialog
const dialogVisible = ref(false)
const dialogMessage = ref("")



// Props
const props = defineProps({

    id : {
        type:Number,
        default:null
    }

})





// ==============================
// STORES PINIA
// ==============================
const chauffeursStore = useChauffeursStore()
const controleursStore = useControleursStore()
const vehiculesStore = useVehiculesStore()
const equipesStore = useEquipeStore()
const mecaniciensStore = useMecaniciensStore()
const reparationsStore = useReparationsStore()
const arretsStore = useArretsStore()
const versementsStore =  useVersementsStore()
const depensesStore =  useDepensesStore()


// ==============================
// ENTITéS
// ==============================


// vehicule et équipe associée
const vehicule = computed(()=>vehiculesStore.item)
const equipe = computed(()=> vehiculesStore.item_equipe)
const equipes_dispo = computed(()=>equipesStore.disponibles)


// chauffeurs et controleurs
const chauffeurs_dispos = computed(()=>chauffeursStore.disponibles)
const controleurs_dispos = computed(()=>controleursStore.disponibles)


// mécaniciens
const mecaniciens = computed(()=> mecaniciensStore.items)

// réparations 
const reparations = computed(()=> reparationsStore.items)

// nombre total des réparations
const nbre_reparations = computed(()=> reparationsStore.items?.length)

// arrêts
const arrets = computed (()=> arretsStore.items)

// nbre d'arrêts
const nbre_arrets = computed(()=>arretsStore.items?.length)


// versements
const versements = computed(()=>versementsStore.items)


// statut du vehicule
const statut = computed(()=>vehicule.value.statut)



// Variables reactives pour modales
const showUpdateVehicle = ref(false)
const showUpdateTeam = ref(false)
const showNewVersement = ref(false)
const showAddTeam = ref(false)
const showChangeStatus = ref(false)
const showNewReparation = ref(false)
const showNewArret = ref(false)
const showCreateTeam = ref(false)
const showRepairModale = ref(false)
const showPerfsDetails = ref(false)
const showUpdateVersement = ref(false)


// voir plus de réparations
const showMoreStops = ref(false)
const showMoreRepairs =  ref(false)
const showPayer = ref(false)


const historique_salaire = computed(()=>depensesStore.historique_salaire)

const payer_employe_to_see_perfs = async () => {
    
    showPayer.value = true
    showPerfsDetails.value = false

    await depensesStore.fetchHistoriqueSalaire(employeToSeePerfs.value?.employe_id)

}


// id du chauffeur et du controleur
const chauffeur_id = computed(()=>equipe?.value?.chauffeurs[0].id)
const controleur_id = computed(()=>equipe?.value?.controleurs[0].id)



// la réparation à afficher
const repairToView = ref(null)



// fonction pour les réparations
const repairView = (reparation) => {

    showRepairModale.value = true
    repairToView.value = reparation

}


// chiffre d'affaire
const ca_du_mois = computed(()=>versementsStore.ca_du_mois)



// ==========================================
// MOTIFS D'INACTIVITÉ
// ==========================================

const motifs = ref([
    { name: 'Administration', code: 'Administration' },
    { name: 'Problème de circulation', code: 'Problème de circulation' },
    { name: 'Accident', code: 'Accident' },
    { name: 'Autre', code: 'Autre' }
]);


// motifs de réparation
const motifsReparation = ref([
    { name: 'Amortisseur', code: 'Amortisseur' },
    { name: 'Amortisseur arrière', code: 'Amortisseur arrière' },
    { name: 'Batterie', code: 'Batterie' },
    { name: 'Bloc de lame', code: 'Bloc de lame' },
    { name: 'Boite à fusible', code: 'Boite à fusible' },
    { name: 'Bouchon radiateur', code: 'Bouchon radiateur' },
    { name: 'Bout de cardan', code: 'Bout de cardan' },
    { name: 'Bride de lame', code: 'Bride de lame' },
    { name: 'Buté embrayage', code: 'Buté embrayage' },
    { name: 'Cable frein', code: 'Cable frein' },
    { name: 'Cable frein à main avant', code: 'Cable frein à main avant' },
    { name: 'Cache poussière', code: 'Cache poussière' },
    { name: 'Claxon', code: 'Claxon' },
    { name: 'Cloison de cardan', code: 'Cloison de cardan' },
    { name: 'Colier', code: 'Colier' },
    { name: 'Colle', code: 'Colle' },
    { name: 'Contacteur', code: 'Contacteur' },
    { name: 'Coupelle de frein', code: 'Coupelle de frein' },
    { name: 'Coupelle de frein arrière', code: 'Coupelle de frein arrière' },
    { name: 'Courroie alternateur', code: 'Courroie alternateur' },
    { name: 'Courroie hydraulique', code: 'Courroie hydraulique' },
    { name: 'Cremaillère', code: 'Cremaillère' },
    { name: 'Culasse complète', code: 'Culasse complète' },
    { name: 'Cylindres de roue', code: 'Cylindres de roue' },
    { name: 'Cylindrobloc de lame', code: 'Cylindrobloc de lame' },
    { name: 'Demi faisceaux', code: 'Demi faisceaux' },
    { name: 'Disque de frein', code: 'Disque de frein' },
    { name: 'Disque embrayage', code: 'Disque embrayage' },
    { name: 'Durite à air', code: 'Durite à air' },
    { name: 'Durite de radiateur', code: 'Durite de radiateur' },
    { name: 'Embrayage émetteur', code: 'Embrayage émetteur' },
    { name: 'Embrayage récepteur', code: 'Embrayage récepteur' },
    { name: 'Émetteur complet', code: 'Émetteur complet' },
    { name: 'Entretien des filtres', code: 'Entretien des filtres' },
    { name: 'Entretien huile', code: 'Entretien huile' },
    { name: 'Filtres', code: 'Filtres' },
    { name: 'Flexible alternateur', code: 'Flexible alternateur' },
    { name: 'Flexible frein', code: 'Flexible frein' },
    { name: 'Fusée', code: 'Fusée' },
    { name: 'Huile de frein', code: 'Huile de frein' },
    { name: 'Huile de pont', code: 'Huile de pont' },
    { name: 'Huile moteur', code: 'Huile moteur' },
    { name: 'Joint de culasse', code: 'Joint de culasse' },
    { name: 'Joint spi', code: 'Joint spi' },
    { name: 'Joint spi boîte', code: 'Joint spi boîte' },
    { name: 'Joint spi moyeu', code: 'Joint spi moyeu' },
    { name: 'Joint spi vilebrequin', code: 'Joint spi vilebrequin' },
    { name: 'Joint spi volant moteur', code: 'Joint spi volant moteur' },
    { name: 'Lame maîtresse', code: 'Lame maîtresse' },
    { name: 'Machoires', code: 'Machoires' },
    { name: 'Main d\'œuvre pompiste', code: 'Main d\'œuvre pompiste' },
    { name: 'Mémoire alimentation', code: 'Mémoire alimentation' },
    { name: 'Nettoyage des filtres', code: 'Nettoyage des filtres' },
    { name: 'New Int', code: 'New Int' },
    { name: 'Nez d\'injecteur', code: 'Nez d\'injecteur' },
    { name: 'Patin', code: 'Patin' },
    { name: 'Patin B15', code: 'Patin B15' },
    { name: 'Pipe d\'eau', code: 'Pipe d\'eau' },
    { name: 'Plateau embrayage', code: 'Plateau embrayage' },
    { name: 'Pochette', code: 'Pochette' },
    { name: 'Pompe à vide', code: 'Pompe à vide' },
    { name: 'Pot graisse', code: 'Pot graisse' },
    { name: 'Pot…', code: 'Pot…' },
    { name: 'Radiateur', code: 'Radiateur' },
    { name: 'Reniflard', code: 'Reniflard' },
    { name: 'Roclef', code: 'Roclef' },
    { name: 'Rondelles', code: 'Rondelles' },
    { name: 'Roue', code: 'Roue' },
    { name: 'Roulement 6201', code: 'Roulement 6201' },
    { name: 'Roulement de moyeu', code: 'Roulement de moyeu' },
    { name: 'Roulement volant moteur', code: 'Roulement volant moteur' },
    { name: 'Ruban adhésif', code: 'Ruban adhésif' },
    { name: 'Soudeur', code: 'Soudeur' },
    { name: 'Sous maîtresse', code: 'Sous maîtresse' },
    { name: 'Support boîte', code: 'Support boîte' },
    { name: 'Support moteur', code: 'Support moteur' },
    { name: 'Tambours', code: 'Tambours' },
    { name: 'Tête hydraulique', code: 'Tête hydraulique' },
    { name: 'Tige barre stabilisatrice', code: 'Tige barre stabilisatrice' },
    { name: 'Autre', code: 'Autre' }
]);



const selectedMotifReparation = ref(null);


const total_par_motifs = computed(()=>arretsStore.total_par_motif)


const chartData_arrets = ref();
const chartOptions_arrets = ref();


const setChartData_arrets = () => {
    const documentStyle = getComputedStyle(document.body);

    return {
        labels: total_par_motifs.value.map(n=>n.motif),
        datasets: [
            {
                data: total_par_motifs.value.map(n=>n.total),
                backgroundColor: [documentStyle.getPropertyValue('--p-cyan-500'), documentStyle.getPropertyValue('--p-orange-500'), documentStyle.getPropertyValue('--p-gray-500')],
                hoverBackgroundColor: [documentStyle.getPropertyValue('--p-cyan-400'), documentStyle.getPropertyValue('--p-orange-400'), documentStyle.getPropertyValue('--p-gray-400')]
            }
        ]
    };
};


const setChartOptions_arrets = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');

    return {
        plugins: {
            legend: {
                labels: {
                    usePointStyle: true,
                    color: textColor
                }
            }
        }
    };
};



// reparations
const total_par_motifs_reparation = computed(()=>reparationsStore.total_par_motif)


const chartData_reparations = ref();
const chartOptions_reparations = ref();


const setChartData_reparations = () => {
    const documentStyle = getComputedStyle(document.body);

    return {
        labels: total_par_motifs_reparation.value.map(n=>n.description),
        datasets: [
            {
                data: total_par_motifs_reparation.value.map(n=>n.total),
                backgroundColor: [documentStyle.getPropertyValue('--p-cyan-500'), documentStyle.getPropertyValue('--p-orange-500'), documentStyle.getPropertyValue('--p-gray-500')],
                hoverBackgroundColor: [documentStyle.getPropertyValue('--p-cyan-400'), documentStyle.getPropertyValue('--p-orange-400'), documentStyle.getPropertyValue('--p-gray-400')]
            }
        ]
    };
};

const setChartOptions_reparations = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');

    return {
        plugins: {
            legend: {
                labels: {
                    usePointStyle: true,
                    color: textColor
                }
            }
        }
    };
};




// ================================================
// SUIVI DES PERFORMANCES DES EMPLOYÉS
// ================================================


// l'employé à voir les perfs et son role (chauffeur | controleur)
const employeToSeePerfs = ref(null)
const employeToSeePerfsRole = ref(null)

// total des versements
const totalEmployeToSeePerfs = ref(null)

// salaire actuel
const employeToSeePerfsSalary = ref(null)


// historique paginé des versements
const employeToSeePerfsHistorique = ref(null)

// données pour le graphique
const employeToSeePerfsChartData = ref(null)


// voir les performances du chauffeur
const voirPerfsChauffeur = async () => {

    showPerfsDetails.value = true
    employeToSeePerfs.value = equipe?.value.chauffeurs[0]

    employeToSeePerfsRole.value = "Chauffeur"

    await versementsStore.fetchTotalVersementsParChauffeurMoisCourant(employeToSeePerfs.value.id)
    await versementsStore.fetchHistoriqueChauffeur(employeToSeePerfs.value.id)
    await versementsStore.fetchEvolutionVersementsChauffeur(employeToSeePerfs.value.id)


    // total des versements du mois
    totalEmployeToSeePerfs.value = versementsStore.total_versements_par_chauffeur_mois_courant

    // salaire actuel
    employeToSeePerfsSalary.value = (totalEmployeToSeePerfs.value * 40)/100

    // historique
    employeToSeePerfsHistorique.value = versementsStore.historique_chauffeur

    // chartData
    employeToSeePerfsChartData.value = versementsStore.evolution_versements_chauffeur


    // tracer le graphique
    chartData_perfs.value = setChartData_perfs();
    chartOptions_perfs.value = setChartOptions_perfs();



}


// voir les performances du controleur
const voirPerfsControleur = async () => {

    showPerfsDetails.value = true
    employeToSeePerfs.value = equipe?.value.controleurs[0]

    employeToSeePerfsRole.value = "Controleur"

    await versementsStore.fetchTotalVersementsParControleurMoisCourant(employeToSeePerfs.value.id)
    await versementsStore.fetchHistoriqueControleur(employeToSeePerfs.value.id)
    await versementsStore.fetchEvolutionVersementsControleur(employeToSeePerfs.value.id)

    totalEmployeToSeePerfs.value = versementsStore.total_versements_par_controleur_mois_courant
    employeToSeePerfsSalary.value = (totalEmployeToSeePerfs.value * 25)/100
    employeToSeePerfsHistorique.value = versementsStore.historique_controleur

    employeToSeePerfsChartData.value = versementsStore.evolution_versements_controleur


    // tracer le graphique
    chartData_perfs.value = setChartData_perfs();
    chartOptions_perfs.value = setChartOptions_perfs();



}


const chartData_perfs = ref();
const chartOptions_perfs = ref();


const setChartData_perfs = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: ['Jan', 'Fev', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil', 'Aout', 'Sept', 'Oct', 'Nov', 'Dec'],
        datasets: [
            {
                label: 'Evolution',
                type: 'line',
                borderColor: documentStyle.getPropertyValue('--p-gray-500'),
                borderWidth: 2,
                fill: false,
                tension: 0.4,
                data: employeToSeePerfsChartData.value.map(n=>n?.total)
            },
            {
                label: 'Total',
                type: 'bar',
                backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                data: employeToSeePerfsChartData.value.map(n=>n.total)
            }
        ]
    };
};


const setChartOptions_perfs = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
}





// ================================================
// SUIVI DES PERFORMANCES DU VEHICULES
// ================================================


// variable pour la tab
const value_totaux = ref('0');


const totaux_mensuels_vehicule = computed(()=> versementsStore.totaux_mensuels_vehicule)
const totaux_cumules_vehicule = computed(()=>versementsStore.totaux_cumules_vehicule)


const chartData_mensuel = ref(null)
const chartOptions_mensuel = ref(null)


// graphique des montants totaux
const setChartData_mensuel = () => {
    return {

        labels : ["Jan", "Fev", "Mars", "Avril", "Mai", "Juin", "Juil", "Aout", "Sept", "Oct", "Nov", "Déc"],
        datasets : [
            {
                label :"Totaux mensuels",
                data: totaux_mensuels_vehicule.value.map(item=>item.total),
                backgroundColor: [
                'rgba(249, 167, 115, 0.3)', // Janvier
                'rgba(102, 204, 255, 0.3)', // Février
                'rgba(160, 160, 160, 0.3)', // Mars
                'rgba(179, 123, 255, 0.3)', // Avril
                'rgba(46, 204, 113, 0.3)',  // Mai
                'rgba(255, 159, 64, 0.3)',  // Juin
                'rgba(255, 99, 132, 0.3)',  // Juillet
                'rgba(54, 162, 235, 0.3)',  // Août
                'rgba(255, 206, 86, 0.3)',  // Septembre
                'rgba(75, 192, 192, 0.3)',  // Octobre
                'rgba(153, 102, 255, 0.3)', // Novembre
                'rgba(255, 99, 71, 0.3)'    // Décembre
            ],
            borderColor: [
                'rgba(249, 115, 22, 0.8)',
                'rgba(6, 182, 212, 0.8)',
                'rgba(107, 114, 128, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(46, 204, 113, 0.8)',
                'rgba(255, 159, 64, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 99, 71, 0.8)'
            ],
                    borderWidth: 2
            },
        ]
    }
}


const setChartOptions_mensuel = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
}


// graphique du cumulés des versements

const chartData_cumule = ref(null)
const chartOptions_cumule = ref(null)


const setChartData_cumule = () => {
    return {
        labels : ["Jan", "Fev", "Mars", "Avril", "Mai", "Juin", "Juil", "Aout", "Sept", "Oct", "Nov", "Déc"],
        datasets : [
            {
                label :"Totaux mensuels",
                data: totaux_cumules_vehicule.value.map(item=>item.cumul),
                backgroundColor: [
                    'rgba(249, 167, 115, 0.3)', // Janvier
                    'rgba(102, 204, 255, 0.3)', // Février
                    'rgba(160, 160, 160, 0.3)', // Mars
                    'rgba(179, 123, 255, 0.3)', // Avril
                    'rgba(46, 204, 113, 0.3)',  // Mai
                    'rgba(255, 159, 64, 0.3)',  // Juin
                    'rgba(255, 99, 132, 0.3)',  // Juillet
                    'rgba(54, 162, 235, 0.3)',  // Août
                    'rgba(255, 206, 86, 0.3)',  // Septembre
                    'rgba(75, 192, 192, 0.3)',  // Octobre
                    'rgba(153, 102, 255, 0.3)', // Novembre
                    'rgba(255, 99, 71, 0.3)'    // Décembre
                ],
                borderColor: [
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(6, 182, 212, 0.8)',
                    'rgba(107, 114, 128, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(46, 204, 113, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 99, 71, 0.8)'
                ],
                borderWidth: 2
            }
        ]
    }
}


const setChartOptions_cumule = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
}



// comparaison des objectifs

// ==============================================
// OBJECTIFS


const value_comparaison = ref('0')


const objectifs = computed(()=>versementsStore.comparaison_objectifs_vehicule)



// =====================================
// Graphique 3 : Comparaison aujourd'hui
// =====================================

const chartData_today = ref();
const chartOptions_today = ref();


const today_objectifs = computed(()=>objectifs.value?.journalier)


const setChartData_today = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: ['Recette totale'],
        datasets: [
            {
                label: 'Objectif',
                backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                data: [today_objectifs.value.objectif] // ✅ mettre en tableau
            },
            {
                label: 'Réalisé',
                backgroundColor: documentStyle.getPropertyValue('--p-gray-500'),
                borderColor: documentStyle.getPropertyValue('--p-gray-500'),
                data: [today_objectifs.value.realise] // ✅ mettre en tableau
            }
        ]
    };
};



const setChartOptions_today = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary,
                    font: {
                        weight: 500
                    }
                },
                grid: {
                    display: false,
                    drawBorder: false
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder,
                    drawBorder: false
                }
            }
        }
    };
}



// =====================================
// Graphique 4 : Comparaison semaine
// =====================================

const chartData_hebdo = ref();
const chartOptions_hebdo = ref();


const hebdo_objectifs = computed(()=>objectifs.value?.hebdomadaire)


const setChartData_hebdo = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: ['Recette totale'],
        datasets: [
            {
                label: 'Objectif',
                backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                data: [hebdo_objectifs.value.objectif] // ✅ mettre en tableau
            },
            {
                label: 'Réalisé',
                backgroundColor: documentStyle.getPropertyValue('--p-gray-500'),
                borderColor: documentStyle.getPropertyValue('--p-gray-500'),
                data: [hebdo_objectifs.value.realise] // ✅ mettre en tableau
            }
        ]
    };
};



const setChartOptions_hebdo = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary,
                    font: {
                        weight: 500
                    }
                },
                grid: {
                    display: false,
                    drawBorder: false
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder,
                    drawBorder: false
                }
            }
        }
    };
}




// =====================================
// Graphique 5 : Comparaison mensuelle
// =====================================

const chartData_mois = ref();
const chartOptions_mois = ref();


const mois_objectifs = computed(()=>objectifs.value?.mensuel)


const setChartData_mois = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: ['Recette totale'],
        datasets: [
            {
                label: 'Objectif',
                backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                data: [mois_objectifs.value.objectif] // ✅ mettre en tableau
            },
            {
                label: 'Réalisé',
                backgroundColor: documentStyle.getPropertyValue('--p-gray-500'),
                borderColor: documentStyle.getPropertyValue('--p-gray-500'),
                data: [mois_objectifs.value.realise] // ✅ mettre en tableau
            }
        ]
    };
};



const setChartOptions_mois = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary,
                    font: {
                        weight: 500
                    }
                },
                grid: {
                    display: false,
                    drawBorder: false
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder,
                    drawBorder: false
                }
            }
        }
    };
}


// ==============================
// MODIFIER UN VERSEMENT
// ==============================
const id_versement_to_modify = ref(null)

const modifier_versement = (versement) => {

    showUpdateVersement.value = true
    showPerfsDetails.value = false
    id_versement_to_modify.value = versement?.id

}




// ==========================================
// SOUMISSIONS DE FORMULAIRE
// ==========================================

const submitModifierVehicule = async (event) => {


    const form = event.target;
    const formData = new FormData(form);
    formData.append("_method", "PUT");

    try {

        await vehiculesStore.updateVehicule(formData, props.id)
        await vehiculesStore.fetchVehicule(props.id)
        showUpdateVehicle.value = false

    }catch (exception) {

        console.log ("Erreur lors de la modification des informations du vehicule : ", exception)

    }
};



// assigner une équipe
const submitAddTeam = async(event) => {

    // données du formulaire
    const form = event.target
    const formData = new FormData(form)

    // id de l'équipe selectionnée
    const form_equipe_id = formData.get("equipe_id")

    // on overide la méthode post en put
    formData.append("_method", "PUT")

    // envoi des data
    try {

        await equipesStore.assignEquipe(formData, form_equipe_id, vehicule?.value.id)
        await vehiculesStore.fetchVehicule(props.id)
        showAddTeam.value = false

    }catch (exception) {

        console.log ("Erreur lors de l'assignation de l'équipe : ", exception)

    }

}


// changer le statut du vehicule
const submitChangeStatus = async (event)=>{
    const form = event.target
    const formData = new FormData(form)


    formData.append("_method", "PUT")

    try {


        await vehiculesStore.updateStatus(formData, props.id)
        showChangeStatus.value = false
        await vehiculesStore.fetchVehicule(props.id)

        
    }catch (exception) {

        toast.error("Une erreur est survenue")
        console.log("Erreur lors du changement de statut : ", exception)

    }
}


// nouveau versement
const submitVersement = async (event) => {


    const form = event.target

    const formData = new FormData(form)


    console.log(vehicule.value.equipes?.chauffeurs,
        vehicule.value.equipes?.controleurs)

    if (verifyTeam() && statut.value==="actif") {

        try {
    
            await versementsStore.verser(formData, props.id, chauffeur_id?.value, controleur_id?.value)
            showNewVersement.value = false
            await versementsStore.fetchAll(props.id)
    
        }catch (exception) {
            
            console.log ("Erreur lors du versement : ", exception)
    
        }

    }else {

        showNewVersement.value = false
        dialogVisible.value = true
        dialogMessage.value = "Impossible de faire cette action car ce vehicule n'a pas d'équipe valide ou de statut actif."

    }




}


// mettre à jour l'équipe
const submitUpdateTeam = async (event) => {
    const form = event.target;
    const formData = new FormData(form);


    // overide du post en put
    formData.append("_method", "PUT");



    console.log ("Chauffeur ID : ", equipe.value?.chauffeurs[0]?.id)
    console.log ("Controleur ID : ", equipe.value?.controleurs[0]?.id)


    // envoi de la requête
    try {

        await equipesStore.updateAssignedEquipe(formData, vehicule.value?.equipes?.id)
        await vehiculesStore.fetchVehicule(props.id)
        showUpdateTeam.value = false

    } catch (exception) {

        console.error("Erreur lors de la modification de l'équipe ", exception);

    }
};



// ajouter une nouvelle réparation
const submitAddPanne = async (event) => {

    const form = event.target
    const formData = new FormData(form)

    try {

        await reparationsStore.addNewReparation(formData, props.id)
        await reparationsStore.fetchAll(props.id)
        showNewReparation.value = false

    } catch (exception) {

        console.log ("Impossible d'ajouter cette réparation, Erreur : ", exception)

    }


}


// nouvel arrêt
const submitAddArret = async (event) => {

    const form = event.target
    const formData = new FormData(form)


    try {

        await arretsStore.addNewArret(formData, props.id)
        await arretsStore.fetchAll(props.id)
        showNewArret.value = false

    }catch (exception) {

        console.log ("Erreur lors de l'ajout de l'arrêt : ", exception)

    }

}



// créer une équipe et l'assigner
const submitCreateTeamToAssign = async (event) => {


    const form = event.target
    const formData = new FormData(form)

    try {

        await equipesStore.createToAssign(formData, props.id)    
        showCreateTeam.value = false
        await vehiculesStore.fetchVehicule(props.id)
        
    }catch (exception) {

        console.error ("Erreur : ", exception)

    }

}


// modifier le statut d'une réparation
const submitChangeRepairStatus = async (event) => {

    const form = event.target
    const formData = new FormData(form)
    
    
    // overide de la méthode post en put
    formData.append('_method', 'PUT')

    try {

        await reparationsStore.updateStatus(formData, repairToView.value.id)
        showRepairModale.value = false
        await reparationsStore.fetchAll(props.id)
    
    }catch (exception) {

        console.error ("Erreur : ", exception)

    }

}


// Payer un employé
const submitPayerEmployeToSeePerfs = async (event) => {

    const form = event.target
    const formData = new FormData(form)

    try {

        await depensesStore.depenser(formData, employeToSeePerfs.value.employe_id)

    }catch (exception) {

        console.error ("Erreur lors du paiement de l'employé : ", exception)
        toast.error("Une erreur est survenue lors du paiement de l'employé")

    }

}


// modifier un versement
const submitUpdateVersement = async (event) => {

    const form = event.target
    const formData =  new FormData(form)

    try {

        await versementsStore.update(formData, id_versement_to_modify.value)
        await versementsStore.fetchAll(props.id)

    }catch (exception) {

        console.error ("Erreur lors de la modification du versement : ", exception)

    }

}




// ==============================
// VERIFICATIONS
// ==============================



// on vérifie si le vehicule a une équipe conforme
const verifyTeam = () => {

    if (vehicule.value?.equipes &&
        vehicule.value.equipes?.chauffeurs && 
        vehicule.value.equipes?.controleurs) {

        return true

    }else {

        return false
    }

}



/// Détermine le texte en fonction du statut
const statusText = computed(() => {
    const status = vehicule.value?.statut?.toLowerCase()
    switch (status) {
        case 'actif': return 'Actif'
        case 'inactif': return 'Inactif'
        case 'repair': return 'En réparation'
        default: return 'inconnu'
    }
})



// Détermine la classe en fonction du statut
const statusClass = computed(() => {
    const status = vehicule.value?.statut?.toLowerCase()
    switch (status) {
        case 'actif': return 'status-active'
        case 'inactif': return 'status-stopped'
        case 'repair': return 'status-repair'
        default: return ''
    }
})





// au montage du composant
onMounted(async()=> {

    // informations du vehicule
    await vehiculesStore.fetchVehicule(props.id)

    
    // toutes les réparations du vehicule
    await reparationsStore.fetchAll(props.id)

    
    // tous les arrêts du vehicule
    await arretsStore.fetchAll(props.id)

    
    // tous les versements du vehicule
    await versementsStore.fetchAll(props.id)


    // chiffre d'affaire du mois
    await versementsStore.fetchTotalVersement(props.id)


    // total des arrêts par motifs
    await arretsStore.fetchTotalParMotif(props.id)


    // total des réparations par motifs
    await reparationsStore.fetchTotalParMotif(props.id)

    // totaux mensuels des versements
    await versementsStore.fetchTotauxMensuelsVehicule(props.id)

    // totaux cumulés des versements
    await versementsStore.fetchCumuleVehicule(props.id)


    // objectifs vs réalisation
    await versementsStore.fetchComparaisonObjectifsVehicule(props.id)


    // graphique pour les arrêts
    chartData_arrets.value = setChartData_arrets();
    chartOptions_arrets.value = setChartOptions_arrets();





    // graphique pour les réparations
    chartData_reparations.value = setChartData_reparations();
    chartOptions_reparations.value = setChartOptions_reparations();



    // graphique 1 : comparaison mensuelle
    chartData_mensuel.value = setChartData_mensuel();
    chartOptions_mensuel.value = setChartOptions_mensuel();


    // graphique 2 : comparaison cumulé
    chartData_cumule.value = setChartData_cumule()
    chartOptions_cumule.value = setChartOptions_cumule()


    // graphique 3 : comparaison journalière
    chartData_today.value = setChartData_today()
    chartOptions_today.value = setChartOptions_today()


    // graphique 4 : comparaison hebdomadaire
    chartData_hebdo.value = setChartData_hebdo()
    chartOptions_hebdo.value = setChartOptions_hebdo()


    // graphique 5 : comparaison mensuel
    chartData_mois.value = setChartData_mois()
    chartOptions_mois.value = setChartOptions_mois()

})



</script>




<style scoped>

.bus-infos {
    font-family: var(--font-title-small);
}

/* Bouton pour modifier les informations */
.btn-modifier-infos {
    background-color: var(--color-primary);
    color: white;
    font-family: var(--font-title-small);
    transition: all 0.2s ease-in;
}

.btn-modifier-infos:hover {
    transform: scale(1.01);
    box-shadow: 0px 0px 1px 1px var(--color-primary);
}



/* Image du vehicule */
.vehicule-image {
    height: auto;
    max-width: 100%;
    transition: all 0.2s ease-in;
}


.vehicule-image img {
    width: 100%;
    border-radius: 20px;
}




/* Couche au dessus de l'image */
.black {
    background-color: rgba(0, 0, 0, 0.553);
    height: 100%;
    width: 100%;
    border-radius: 20px;
}

.actions {
    border-bottom: 1px solid white;
    padding-bottom: 10px;
}

/* Affichage du statut du vehicule */
.status {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 10px;
    font-size: 15px;
    color: #fff;
}

.status-active {
    background: var(--color-accent-green);
}


.status-stopped {
    background: var(--color-accent-red);
}


.status-repair {
    background: var(--color-text-light);
}



/* Customisation de la tab réparation, pannes, mise à l'arrêt */
.custom-tabs {
    font-family: var(--font-title-small);
}


.custom-tabs .nav-link {
    color: #000;
    font-weight: 500;
    border: none;
    position: relative;
}


.custom-tabs .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    width: 100%;
    background-color: var(--color-accent-green); 
    border-radius: 2px;
}


.custom-tabs .nav-link:hover {
    color: #000;
}


ul {
    border: none;
}


.historique {
    width: 95%;
}



.label-ca {
    font-weight: 600;           /* texte un peu plus gras */
    font-size: 1.1rem;          /* taille légèrement plus grande */
    color: #e7f1ff;             /* couleur bleu bootstrap */
    background-color: #0d6efd;  /* léger fond bleu clair */
    padding: 8px 12px;          /* espace intérieur */
    border-radius: 8px;         /* coins arrondis */
    display: inline-block;       /* pour s'ajuster au contenu */
    box-shadow: 0 2px 6px rgba(0,0,0,0.1); /* ombre légère */
    transition: all 0.2s ease;
}

.label-ca:hover {
    background-color: #d0e4ff;  /* effet hover plus visible */
    color: black;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}



.employeToSee {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    padding: 20px;
    text-align: center;
    max-width: 250px;
    margin: 20px auto;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.employeToSee:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.employeToSee img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
    border: 3px solid #f1f3f5;
}

.employeToSee h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

.employeToSee h6 {
    font-size: 0.9rem;
    font-weight: 500;
    color: #6c757d;
}


.actual-salary {
    background: linear-gradient(135deg, #4cafef, #1976d2);
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
    padding: 12px 18px;
    border-radius: 12px;
    display: inline-block;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-top: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.actual-salary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.15);
}



.nbre-reparation.success-outline {
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #198754;   /* vert bootstrap */
    color: #198754;
    border-radius: 8px;
    padding: 10px 14px;
    font-weight: 500;
    background: transparent;     /* fond transparent */
    width: fit-content;
    transition: all 0.3s ease;
}

.nbre-reparation.success-outline i {
    font-size: 1.2rem;
    color: #198754; /* icône verte */
}


.total-arrets.outline-danger {
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #dc3545;  /* rouge bootstrap */
    color: #dc3545;
    border-radius: 8px;
    padding: 10px 14px;
    font-weight: 500;
    background: transparent;
    width: fit-content;
    transition: all 0.3s ease;
}

.total-arrets.outline-danger i {
    font-size: 1.2rem;
    color: #dc3545;
}


@media (max-width:750px) {
    
    .historique {
        width: 100%;
    }
    
}

</style>