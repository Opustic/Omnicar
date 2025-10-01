<template>


    <section class="versements-du-jour">
        <div class="card">

            <h4 class="text-dark m-3">
                Versements du jour
            </h4>

            <hr>

            <div v-if="todays_versements?.length">
                <VersementRow
                    v-for="versement in todays_versements"
                    :item="versement"
                />
            </div>

            <div v-else class="text-center">
                <h3 class="text-gray">
                    Aucun versement effectué aujourd'hui
                </h3>
            </div>

        </div>
    </section>


    
    <!-- totaux mensuels -->
    <section class="totaux-mensuels p-2">
        

        <div class="card">

            <h4 class="text-dark m-3">
                Total des versements (mois courant)
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

    <br>



    <!-- Comparaison -->
    <section class="comparaison p-2">
        

        <div class="card">

            <h4 class="text-dark m-3">
                Objectifs VS Réalisations (GLOBAL)
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
                                Ratio    : {{ (today_objectifs?.realise/today_objectifs?.objectif)*100 }} %
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
                                Ratio    : {{ ((hebdo_objectifs?.realise/hebdo_objectifs?.objectif)*100).toFixed(2) }}%
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
                                Ratio    : {{ ((mois_objectifs?.realise/mois_objectifs?.objectif) * 100).toFixed(2) }} %
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

    <br>



    <!-- Stats par véhicule -->
    <section class="stats-par-vehicule">

        <div class="card">

            <h4 class="text-dark m-3">
                Recettes par bus
            </h4>
            
            <!-- navigation -->
            <div class="ms-3">
    
                <Button
                    label="Aujourd'hui"
                    severity="secondary"
                    @click="value_stats_par_vehicule = '0'" 
                    :outlined="value_stats_par_vehicule !== '0'"
                />
    
                <Button
                    class="ms-1"
                    label="Cette semaine"
                    severity="secondary"
                    @click="value_stats_par_vehicule = '1'" 
                    :outlined="value_stats_par_vehicule !== '1'"
                />
    
                <Button
                    class="ms-1"
                    label="Ce mois-ci"
                    severity="secondary"
                    @click="value_stats_par_vehicule = '2'" 
                    :outlined="value_stats_par_vehicule !== '2'"
                />
            </div>
    
            <Tabs v-model:value="value_stats_par_vehicule">
            
                <TabPanels>
    
                    <!-- aujourd'hui -->
                    <TabPanel value="0">

                        <div v-if="stats_par_vehicule_jour?.length > 0">

                            <BusStats
                                v-for="vehicule in stats_par_vehicule_jour"
                                :immatriculation="vehicule?.immatriculation"
                                :photo="vehicule?.photo"
                                :total="vehicule?.total"
                            />

                        </div>

                        <div v-else>

                            <h5 class="text-dark">
                                Aucun versement effectué aujourd'hui
                            </h5>

                        </div>

                    </TabPanel>
    
    
                    <!-- cette semaine -->
                    <TabPanel value="1">
    
                        <div v-if="stats_par_vehicule_semaine?.length>0">
                            <BusStats
                                v-for="vehicule in stats_par_vehicule_semaine"
                                :immatriculation="vehicule?.immatriculation"
                                :photo="vehicule?.photo"
                                :total="vehicule?.total"
                            />
                        </div>

                        <div v-else>
                            <h5 class="text-dark">
                                Aucun versement effectué cette semaine
                            </h5>
                        </div>
    
                    </TabPanel>
    
    
                    <!-- Ce mois-ci -->
                    <TabPanel value="2">
                        <div v-if="stats_par_vehicule_mois?.length>0">
                            <BusStats
                                v-for="vehicule in stats_par_vehicule_mois"
                                :immatriculation="vehicule?.immatriculation"
                                :photo="vehicule?.photo"
                                :total="vehicule?.total"
                            />
                        </div>

                        <div v-else>
                            <h5 class="text-dark">
                                Aucun versement effectué cette semaine
                            </h5>
                        </div>
                    </TabPanel>
    
    
                </TabPanels>
            </Tabs>
            
        </div>
    </section>


    <br>



    <section class="evolution-versements">

        <div class="card">

            <h4 class="text-dark m-3">
                Evolution globale des versements mensuels
            </h4>

            <div class="card">

                <Chart
                    type="line"
                    :data="chartData_evolution"
                    :options="chartOptions_evolution"
                    class="h-[30rem]" 
                />



            </div>

        </div>
    </section>

    <br>

</template>




<script setup>
import { useVersementsStore } from '@/store/versements';
import { computed, onMounted, ref } from 'vue';

import Chart from 'primevue/chart';

import Tabs from 'primevue/tabs';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import Button from 'primevue/button';
import BusStats from '@/components/BusStats.vue';
import VersementRow from '@/components/VersementRow.vue';

import { formatCFA } from '@/utils/format';


// =============================
// STORES PINIA
// =============================
const versementsStore = useVersementsStore()



// tous les versements du jour
const todays_versements = computed(()=> versementsStore.todays_versements)





// =============================
// Graphique 1 : Totaux mensuels
// =============================


// tab
const value_totaux = ref('0');

// versements totaux par mois
const totaux_versements = computed(()=> versementsStore.totaux_mensuels)



const chartData_mensuel = ref(null)
const chartOptions_mensuel = ref(null)


const setChartData_mensuel = () => {
    return {

        labels : totaux_versements.value.map(item=>item.mois),
        datasets : [
            {
                label :"Totaux mensuels",
                data: totaux_versements.value.map(item=>item.total),
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



// =============================
// Graphique 2 : Totaux cumulés
// =============================

// tab
const value_comparaison = ref('0')


// totaux cumulés
const totaux_cumules = computed(()=>versementsStore.totaux_cumules)


const chartData_cumule = ref(null)
const chartOptions_cumule = ref(null)


const setChartData_cumule = () => {
    return {
        labels : totaux_cumules.value.map(item=>item.mois),
        datasets : [
            {
                label :"Totaux mensuels",
                data: totaux_cumules.value.map(item=>item.cumul),
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



// ==============================================
// OBJECTIFS
const objectifs = computed(()=>versementsStore.comparaison_objectifs)



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
                backgroundColor: documentStyle.getPropertyValue('--p-green-900'),
                borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                data: [today_objectifs.value.objectif] // ✅ mettre en tableau
            },
            {
                label: 'Réalisé',
                backgroundColor: documentStyle.getPropertyValue('--p-green-500'),
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
                backgroundColor: documentStyle.getPropertyValue('--p-green-900'),
                borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                data: [hebdo_objectifs.value.objectif] // ✅ mettre en tableau
            },
            {
                label: 'Réalisé',
                backgroundColor: documentStyle.getPropertyValue('--p-green-500'),
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
                backgroundColor: documentStyle.getPropertyValue('--p-green-900'),
                borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                data: [mois_objectifs.value.objectif] // ✅ mettre en tableau
            },
            {
                label: 'Réalisé',
                backgroundColor: documentStyle.getPropertyValue('--p-green-500'),
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




// =====================================
// STATS PAR VEHICULE
// =====================================

const value_stats_par_vehicule = ref('0')

const stats_par_vehicule = computed(()=>versementsStore.stats_par_vehicule)

const stats_par_vehicule_jour = computed(()=>stats_par_vehicule.value?.jour)

const stats_par_vehicule_semaine = computed(()=>stats_par_vehicule.value?.semaine)

const stats_par_vehicule_mois = computed(()=>stats_par_vehicule.value?.mois)







// =================================
// EVOLUTION DES VERSEMENTS
// =================================

const evolution_versements = computed(()=>versementsStore.evolution)

const chartData_evolution = ref()
const chartOptions_evolution = ref()

        
const setChartData_evolution = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        datasets: [
            
            {
                label: 'Évolution des versements sur l\'année',
                data: evolution_versements.value.evolution.map(n => n.total),
                fill: true,
                borderColor: documentStyle.getPropertyValue('--p-gray-500'),
                tension: 0.4,
                backgroundColor: 'rgba(107, 114, 128, 0.2)'
            }
        ]
    };
};

const setChartOptions_evolution = () => {
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


// au montage du composant
onMounted(async ()=> {


    await versementsStore.fetchTodaysVersements()


    await versementsStore.fetchTotauxVersementsMensuels()
    await versementsStore.fetchTotauxCumules()
    await versementsStore.fetchComparaisonObjectifs()
    await versementsStore.fetchStatsParVehicule()
    await versementsStore.fetchEvolutionVersements()


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



    // graphique 6 : evolution annuelle des versements
    chartData_evolution.value = setChartData_evolution()
    chartOptions_evolution.value = setChartOptions_evolution()


})



</script>




