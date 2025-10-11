<template>
    <div class="payment-item">
        <div class="dot"></div>
        <div class="payment-info">
        <p class="label">
            {{ props.label }} de :
            <strong class="amount">{{ formatCFA(amount) }}</strong>
        </p>
        <p class="date">📅 {{ date }}</p>
        </div>

        <!-- la modification du versement n'est disponible que si et seulement 
        si le versement est collectif et que la date du versement est récente
        -->
        <div v-if="props.label==='Versement' && recentDateStatement && solo" class="modifier">
            <button class="btn btn-outline-danger" @click="()=>modifier()">
                <i class="bi bi-pencil-square"></i>
            </button>
        </div>
    </div>
</template>

<script setup>

import { formatCFA } from '@/utils/format';
import { ref, onMounted } from 'vue';

const props = defineProps({
    label: String,
    amount: String,
    solo:{type:Boolean, default:false},
    modifier: Function,
    date: String
});

// true si la date est de cette semaine, false sinon
const recentDateStatement = ref(false);


// fonction de vérification
const verifyRecentDate = () => {
    if (!props.date) {
        recentDateStatement.value = false;
        return;
    }

    const inputDate = new Date(props.date);
    const today = new Date();

    // Calcule le premier jour (lundi) et le dernier jour (dimanche) de la semaine actuelle
    const firstDayOfWeek = new Date(today);
    firstDayOfWeek.setDate(today.getDate() - today.getDay() + 1); // lundi
    firstDayOfWeek.setHours(0, 0, 0, 0);

    const lastDayOfWeek = new Date(firstDayOfWeek);
    lastDayOfWeek.setDate(firstDayOfWeek.getDate() + 6); // dimanche
    lastDayOfWeek.setHours(23, 59, 59, 999);

    // Met à jour la valeur réactive
    recentDateStatement.value = inputDate >= firstDayOfWeek && inputDate <= lastDayOfWeek;
};


// au montage du composant
onMounted(()=> {

    // vérifier si la date est récente
    verifyRecentDate()

})

</script>

<style scoped>
.payment-item {
    display: flex;
    align-items: flex-start;
    padding: 12px 16px;
    margin: 8px 0;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.payment-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.dot {
    width: 12px;
    height: 12px;
    background: linear-gradient(135deg, #3498db, #2ecc71);
    border-radius: 50%;
    margin-top: 6px;
    margin-right: 12px;
}

.payment-info {
    flex: 1;
}

.payment-info p {
    margin: 0;
}

.label {
    font-size: 15px;
    color: #2c3e50;
}

.amount {
    color: #27ae60;
    font-weight: 600;
}

.date {
    font-size: 13px;
    color: #7f8c8d;
    margin-top: 4px;
}
</style>
