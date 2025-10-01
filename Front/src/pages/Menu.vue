<template>
    <div class="container dashboard">

        <!-- Mot de Bienvenue -->
        <h2 class="fw-bold mb-4 text-dark">
            Bienvenue, {{ username }} 👋
        </h2>


        <!-- Première ligne : Statistiques principales -->
        <div class="row g-3 mb-4">

            <div class="col-12 col-lg-3">
                <PresentCard label="Recettes du jour" :amount="formatCFA(today?.total_versements)" recette="true" color="green">
                    <i class="bi bi-coin fs-1"></i>
                </PresentCard>
            </div>

            <div class="col-12 col-lg-3">
                <PresentCard label="Dépenses du jour" :amount="formatCFA(depenses_today?.total_depenses)" recette="true" color="#650D1B">
                    <i class="bi bi-cash fs-1"></i>
                </PresentCard>
            </div>

            <div class="col-12 col-lg-3">
                <PresentCard label="Bus actifs" :amount="total_bus_actifs" color="blue">
                    <i class="bi bi-bus-front fs-1"></i>
                </PresentCard>
            </div>

            <div class="col-12 col-lg-3">
                <PresentCard label="Bus inactifs" :amount="total_bus_inactifs" color="red">
                    <i class="bi bi-exclamation-circle-fill fs-1"></i>
                </PresentCard>
            </div>
        </div>


        <!-- Graphique -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="card-title text-dark mb-3">
                    Recettes et Dépenses sur les 7 derniers jours
                </h4>
                <Chart
                    type="line"
                    :data="chartData"
                    :options="chartOptions"
                    class="chart-custom"
                />
            </div>
        </div>


        <!-- Deuxième ligne : Classements -->
        <div>
            <h2 class="fw-bold text-secondary mt-4">Classements de la semaine</h2>
            <hr>

            <!-- Chauffeurs -->
            <div class="ranking-section">
                <h3 class="fw-bold text-dark mb-3">Chauffeurs</h3>
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="card stat-card shadow-sm">
                            <div class="card-header bg-light fw-bold">🚀 Les plus rentables</div>
                            <div class="card-body">
                                <TeamStats
                                    v-for="c in total_versements_par_chauffeurs"
                                    :key="c?.telephone"
                                    :photo="c?.photo"
                                    :nom="c?.nom"
                                    :total="c?.total_versements"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="card stat-card shadow-sm">
                            <div class="card-header bg-light fw-bold">⚠️ Les moins rentables</div>
                            <div class="card-body">
                                <TeamStats
                                    v-for="c in top_last_chauffeurs"
                                    :key="c?.telephone"
                                    :photo="c?.photo"
                                    :nom="c?.nom"
                                    :total="c?.total_versements"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <RouterLink to="/meilleurschauffeurs" class="d-block text-center mt-3">
                    <Button label="Voir plus" severity="secondary" />
                </RouterLink>
            </div>


            <!-- Contrôleurs -->
            <div class="ranking-section mt-4">
                <h3 class="fw-bold text-dark mb-3">Contrôleurs</h3>
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="card stat-card shadow-sm">
                            <div class="card-header bg-light fw-bold">🚀 Les plus rentables</div>
                            <div class="card-body">
                                <TeamStats
                                    v-for="ctrl in total_versements_par_controleurs"
                                    :key="ctrl?.telephone"
                                    :photo="ctrl?.photo"
                                    :nom="ctrl?.nom"
                                    :total="ctrl?.total_versements"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="card stat-card shadow-sm">
                            <div class="card-header bg-light fw-bold">⚠️ Les moins rentables</div>
                            <div class="card-body">
                                <TeamStats
                                    v-for="ctrl in top_last_controleurs"
                                    :key="ctrl?.telephone"
                                    :photo="ctrl?.photo"
                                    :nom="ctrl?.nom"
                                    :total="ctrl?.total_versements"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <RouterLink to="/meilleurscontroleurs" class="d-block text-center mt-3">
                    <Button label="Voir plus" severity="secondary" />
                </RouterLink>
            </div>


            <!-- Véhicules -->
            <div class="ranking-section mt-4">
                <h3 class="fw-bold text-dark mb-3">Véhicules</h3>
                <div class="row g-3">
                    <div class="col-12 col-lg-6">


                        <!-- Les plus rentables -->
                        <div class="card stat-card shadow-sm">
                            <div class="card-header bg-light fw-bold">🚍 Les plus rentables</div>
                            <div class="card-body">
                                <TeamStats
                                    v-for="v in top_vehicules"
                                    :key="v?.immatriculation"
                                    :photo="v?.photo"
                                    :nom="v?.immatriculation"
                                    :total="v?.total"
                                />
                            </div>
                        </div>
                    </div>



                    <!-- Les moins rentables -->
                    <div class="col-12 col-lg-6">
                        <div class="card stat-card shadow-sm">
                            <div class="card-header bg-light fw-bold">⚠️ Les moins rentables</div>
                            <div class="card-body">
                                <TeamStats
                                    v-for="v in top_last_vehicules"
                                    :key="v?.immatriculation"
                                    :photo="v?.photo"
                                    :nom="v?.immatriculation"
                                    :total="v?.total"
                                />
                            </div>
                        </div>
                    </div>


                    <RouterLink to="/meilleursvehicules" class="d-block text-center mt-3">
                        <Button label="Voir plus" severity="secondary" />
                    </RouterLink>

                </div>
            </div>

        </div>

    </div>
</template>


<script setup>

import PresentCard from '@/components/PresentCard.vue';
import TeamStats from '@/components/TeamStats.vue';
import { ref, onMounted, computed, inject } from 'vue';
import { useVehiculesStore } from '@/store/vehicules';
import { useVersementsStore } from '@/store/versements';
import { useDepensesStore } from '@/store/depenses';

import { formatCFA } from '@/utils/format';

import Chart from 'primevue/chart';
import Button from 'primevue/button';
import { useUsersStore } from '@/store/users';


// ===========================================
// PROPS
// ===========================================

const props = defineProps({
    username : String
})

const usersStore = useUsersStore();
const username = computed(()=> usersStore.user_name);
const useremail = computed(()=> usersStore.user_mail);

// ===========================================
// STORES PINIA
// ===========================================

const vehiculesStore = useVehiculesStore()
const versementsStore = useVersementsStore()
const depensesStore = useDepensesStore()


// bus actifs et inactifs
const total_bus_actifs = computed(()=>vehiculesStore.actifs?.length) 
const total_bus_inactifs = computed(()=> vehiculesStore.inactifs?.length)


// versements d'aujourd'hui
const today = computed(()=>versementsStore.today)
const depenses_today = computed(()=>depensesStore.total_today)


// totaux des versements par chauffeur et par controleur
const total_versements_par_chauffeurs = computed(()=>versementsStore.topchauffeurs)
const total_versements_par_controleurs = computed(()=>versementsStore.topcontroleurs)

const top_last_chauffeurs = computed(()=>versementsStore.top_last_chauffeurs)
const top_last_controleurs = computed(()=>versementsStore.top_last_controleurs)


// top 3 des vehicules
const top_vehicules = computed(()=>versementsStore.topvehicules)
const top_last_vehicules = computed(()=>versementsStore.top_last_vehicules)



// =============================================================
// Graphique : EVOLUTION DES VERSEMENTS SUR LES 7 DERNIERS JOURS
// =============================================================




// evolution des versements sur les derniers jours
const evolution_versements_derniers_jours = computed(()=>versementsStore.evolution_derniers_jours)
const evolution_depenses_derniers_jours = computed(()=>depensesStore.evolution_derniers_jours)


const chartData = ref();
const chartOptions = ref();

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: ['1', '2', '3', '4', '5', '6', '7'],
        datasets: [
            {
                label: 'Dépenses',
                data: evolution_depenses_derniers_jours.value.map(n=>n.total),
                fill: false,
                tension: 0.4,
                borderColor: documentStyle.getPropertyValue('--p-red-500')
            },
            {
                label: 'Recettes',
                data: evolution_versements_derniers_jours.value.map(n=>n.total),
                fill: true,
                borderColor: documentStyle.getPropertyValue('--p-green-500'),
                tension: 0.4,
                backgroundColor: 'rgba(107, 114, 128, 0.2)'
            }
        ]
    };
};


const setChartOptions = () => {
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


// au montage de ce composant
onMounted(async()=> {


    // totaux des versements et des dépenses du jour
    await versementsStore.fetchToday()
    await depensesStore.fetchToday()


    // top 3 des chauffeurs, controleurs et vehicules
    await versementsStore.fetchTopChauffeurs()
    await versementsStore.fetchTopControleurs()
    await versementsStore.fetchTopVehicules()
    await versementsStore.fetchTopLastChauffeurs()
    await versementsStore.fetchTopLastControleurs()
    await versementsStore.fetchTopLastVehicules()

    // versements et depenses sur les 7 derniers jours
    await versementsStore.fetchEvolutionDerniersJours()
    await depensesStore.fetchEvolutionDerniersJours()

    // Graphique
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();

})




</script>



<style scoped>
.dashboard h2 {
    font-weight: 700;
}

.chart-custom {
    height: 350px;
}

.stat-card {
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.card-header {
    font-size: 1rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.ranking-section {
    margin-bottom: 2rem;
}
</style>