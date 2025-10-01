<template>


    <!-- Modale pour modifier les informations du véhicule -->
    <Modale
        title="Nouvelle dépense"
        :show="showNewDepense"
        @close="showNewDepense=false"
    >
        <form @submit.prevent="submitNewDepense">

            <select name="categorie" id="categorie" class="form-select" v-model="categorie_selected">

                <option value="">Sélectionnez une categorie</option>

                <option 
                    v-for="categorie in categories"
                    :key="categorie"
                    :value="categorie">
                    {{ categorie }}
                </option>

            </select>


            <!-- véhicule -->
            <select v-if="['entretien', 'reparation', 'assurance', 'taxe', 'controle_technique'].includes(categorie_selected)" name="vehicule_id" id="vehicule_id"  class="form-select mt-2">
                <option value="" disabled>Sélectionnez un vehicule</option>

                <option
                    v-for="vehicule in vehicules"
                    :key="vehicule?.immatriculation"
                    :value="vehicule?.id">

                    {{ vehicule?.immatriculation }}
                </option>

                <option v-if="!vehicules" value="">Aucun vehicule présent</option>

            </select>


            <!-- employé -->
            <select v-if="['salaire', 'prime'].includes(categorie_selected)" name="employe_id" id="employe_id" class="form-select mt-2">
                <option value="" disabled>Sélectionnez un employé</option>

                <option 
                    v-for="employe in mecaniciens"
                    :key="employe.id"
                    :value="employe.id">


                    <!-- On affiche le nom de l'employé -->
                    {{ employe?.mecaniciens?.nom }}

                </option>

            </select>


            <!-- montant -->
            <input type="text" class="form-control mt-2" placeholder="Montant..." name="montant">


            <!-- soumettre -->
            <button type="submit" class="btn btn-primary mt-2">
                Envoyer
            </button>


        </form>
    </Modale>


    
    
    
    <div class="container">

        <!-- bouton pour ajouter une nouvelle dépense -->
        <button class="btn btn-primary" @click="showNewDepense=true">
            <i class="bi bi-plus-circle-fill"></i>
            Nouvelle dépense
        </button>



        <!-- Total des dépenses du jour -->
        <div class="total-today mt-3 p-3 rounded shadow-sm bg-light">
        <p class="text-dark fs-5 mb-0">
            <strong>Total du jour :</strong> {{ formatCFA(total_depenses?.total_depenses) }}
        </p>
        </div>
        
        <hr>
        
        <!-- Dépenses du jour -->
        <div class="listing-depenses">

            <h4 class="text-dark text-center mt-1">
                Aujourd'hui
            </h4>

            <hr>

            <div v-if="today_depenses?.length" class="text-center mt-5">

                <DepenseCard
                    v-for="depense in today_depenses"
                    :key="depense.created_at"
                    :depense="depense"
                />
            </div>

            <div v-else class="text-center mt-5">
                <h3 class="text-gray">
                    <i class="bi bi-folder-x fs-2"></i>
                    <br>
                    Aucune dépense aujourd'hui
                </h3>

            </div>
        </div>


        <!-- Dépenses du mois -->
        <div class="listing-depenses">

            <h4 class="text-dark text-center mt-1">
                Ce mois-ci
            </h4>

            <hr>

            <div v-if="thisMonthDepenses?.length" class="text-center mt-5">

                <DepenseCard
                    v-for="depense in thisMonthDepenses"
                    :key="depense.created_at"
                    :depense="depense"
                />
            </div>

            <div v-else class="text-center mt-5">
                <h3 class="text-gray">
                    <i class="bi bi-folder-x fs-2"></i>
                    <br>
                    Aucune dépense pour ce mois
                </h3>

            </div>
        </div>



        <!-- depenses par catégorie -->
        <div class="depenses-by-categorie">

            <div class="stats-depense">
                <div class="filters mb-3">

                    <h3 class="text-dark">
                        Dépenses par catégorie
                    </h3>

                    <div class="d-flex">

                        <!-- Sélection du mois -->
                        <select v-model="selectedMonth" class="form-select me-2">
                            <option value="">Mois</option>
                            <option v-for="(m, index) in months" :key="index" :value="index + 1">
                            {{ m }}
                            </option>
                        </select>
    
                        <!-- Sélection de l'année -->
                        <select v-model="selectedYear" class="form-select">
                            <option value="">Année</option>
                            <option v-for="y in years" :key="y" :value="y">
                            {{ y }}
                            </option>
                        </select>

                    </div>

                    <button class="btn btn-primary mt-2" @click="fetchStats"><i class="bi bi-eye-fill"></i> Voir les stats</button>

                </div>



                <div v-if="total_by_month_and_category?.length">
                    
                    <DepenseCategory
                        v-for="depense_by_category in total_by_month_and_category"
                        :key="depense_by_category?.categorie"
                        :categorie="depense_by_category?.categorie"
                        :total="depense_by_category?.total"
                    />


                    <br><br>

                </div>


                <div v-else>

                    <h2 class="text-dark">
                        Aucune dépense
                    </h2>

                    <br><br><br>
                    

                </div>

            </div>

        </div>


        <!-- Comparaison entre l'année n et n-1 -->
        <div class="comparaison-annuelle mb-3">
            <div class="card p-3">
                
                <h4 class="text-dark">
                    Comparaison avec l'année dernière
                </h4>
                <div class="card">
                    <Chart type="bar" :data="chartData_comparaison" :options="chartOptions_comparaison" class="h-[30rem]"  />
                </div>

            </div>

        </div>


        <!-- total annuel des dépenses -->
        <div class="total-annuel-depenses card p-3">

            <h4 class="text-dark">
                Total annuel par catégorie
            </h4>

            <div class="card">
                <Chart type="bar" :data="chartData_total_annuel" :options="chartOptions_annuel" />
            </div>

        </div>


    </div>

    <br>



</template>



<script setup>


import DepenseCard from '@/components/DepenseCard.vue';
import DepenseCategory from '@/components/DepenseCategory.vue';
import Modale from '@/components/Modale.vue';
import { useDepensesStore } from '@/store/depenses';
import { useEmployesStore } from '@/store/employes';
import { useVehiculesStore } from '@/store/vehicules';
import { computed, ref, onMounted, watch } from 'vue';
import { formatCFA } from '@/utils/format';


import Chart from 'primevue/chart';
import { useMecaniciensStore } from '@/store/mecaniciens';



// =============================
// STORES PINIA
// =============================
const vehiculesStore = useVehiculesStore()
const depensesStore = useDepensesStore()
const employesStore = useEmployesStore()
const mecaniciensStore = useMecaniciensStore()




// =============================
// ENTITÉS
// =============================
const vehicules = computed(()=>vehiculesStore.items)
const employes = computed(()=>employesStore.items)
const mecaniciens = computed(()=>mecaniciensStore.items)


// total des dépenses du jour
const total_depenses = computed(()=>depensesStore.total_today)
const today_depenses = computed(()=>depensesStore.today_depenses)
const total_by_month_and_category = computed(()=>depensesStore.total_by_month_and_category)


// mois et années pour les selects
const months = [
    'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
];

const years = Array.from({ length: 5 }, (_, i) => new Date().getFullYear() - i);



// =============================
// VARIABLES POUR LES MODALES
// =============================
const showNewDepense = ref(false)


// CATEGORIE | MOIS
const categories = ref([
    "entretien",
    "assurance",
    "taxe", 
    "controle_technique",
    "amende",
    "salaire",
    "prime",
    "divers"
])


// categorie sélectionnée dans le formulaire
const categorie_selected = ref(null)


// soumettre le formulaire (ajouter une nouvelle dépense)
const submitNewDepense = async (event) => {


    // instancier le formulaire
    const form = event.target
    const formData = new FormData(form)


    try {

        depensesStore.depenser(formData)
        await depensesStore.fetchToday()
        await depensesStore.fetchTodaysDepenses()
        showNewDepense.value = false


    }catch (exception) {

        console.log ("Erreur lors de la dépense : ", exception)

    }
    

}



// état réactif
const now = new Date()
const selectedMonth = ref(now.getMonth()+1);
const selectedYear = ref(now.getFullYear());



// Référence au canvas
const chartRef = ref(null)
let chartInstance = null



// Données du store (fetchées via l’API)

const comparaison_annuelle = computed(()=>depensesStore.chartMonthlyComparisonData)

const chartData_comparaison = ref();
const chartOptions_comparaison = ref();

const setChartData_comparaison = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: comparaison_annuelle.value?.labels,
        datasets: [
            {
                label: 'Année en cours',
                backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                data: comparaison_annuelle.value?.thisYear
            },
            {
                label: 'Année dernière',
                backgroundColor: documentStyle.getPropertyValue('--p-gray-500'),
                borderColor: documentStyle.getPropertyValue('--p-gray-500'),
                data: comparaison_annuelle.value?.lastYear
            }
        ]
    };
};


const setChartOptions_comparaison = () => {
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







// fonction pour récupérer les stats
const fetchStats = async () => {
    if (!selectedMonth.value) {
        alert("Veuillez sélectionner un mois !");
        return;
    }


    try {
        
        depensesStore.fetchTotalByCategoryAndMonth(selectedYear.value, selectedMonth.value)

    } catch (exception) {

        console.error(exception);
    }
};




// ==================================
// DÉPENSES DU MOIS COURANT
// ==================================

const total_depenses_annuelles_par_categorie = computed(()=>depensesStore.total_annuel_par_categorie)


const chartData_total_annuel = ref();
const chartOptions_total_annuel = ref();

const setChartData_total_annuel = () => {
    return {
        labels: total_depenses_annuelles_par_categorie.value?.map(item => item?.categorie),
        datasets: [
            {
                label: 'Total annuel des dépenses par catégorie',
                data: total_depenses_annuelles_par_categorie.value?.map(item => item?.total),
                backgroundColor: ['rgba(249, 115, 22, 0.2)', 'rgba(6, 182, 212, 0.2)', 'rgb(107, 114, 128, 0.2)', 'rgba(139, 92, 246 0.2)'],
                borderColor: ['rgb(249, 115, 22)', 'rgb(6, 182, 212)', 'rgb(107, 114, 128)', 'rgb(139, 92, 246)'],
                borderWidth: 1
            }
        ]
    };
};
const setChartOptions_total_annuel = () => {
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












// ==================================
// DÉPENSES DU MOIS COURANT
// ==================================

const thisMonthDepenses = computed(()=>depensesStore.thisMonth)


// au montage du composant
onMounted(async ()=> {

    await employesStore.fetchAll()
    await depensesStore.fetchToday()
    await depensesStore.fetchTodaysDepenses()
    await fetchStats(selectedMonth.value, selectedYear.value)
    await depensesStore.fetchMonthlyComparison()

    await depensesStore.fetchThisMonth()
    await depensesStore.fetchTotalAnnuelParCategorie()

    // graphique 1 : total annuel par catégorie
    chartData_total_annuel.value = setChartData_total_annuel();
    chartOptions_total_annuel.value = setChartOptions_total_annuel();

    // graphique 2 : comparaison annuelle
    chartData_comparaison.value = setChartData_comparaison();
    chartOptions_comparaison.value = setChartOptions_comparaison();

})


</script>




<style scoped>

.listing-depenses {

    height: 400px;
    max-width: 500px;
    padding: 10px;
    border-radius: 10px;
    overflow: auto;
    margin-bottom: 20px;
    background-color: white;

}

</style>