<template>
    <div class="repair-card" :class="statusClass">
        <div class="repair-header">
            <img :src="userImage" alt="Mécanicien" class="user-avatar" />
            <div class="mechanic-info">
                <div class="mechanic-name">{{ mechanicName }}</div>
                <div class="mechanic-role">Mécanicien</div>
            </div>
            <span class="status">{{ status }}</span>
        </div>

        <!-- Détails de la réparation -->
        <div class="repair-details">
            <span class="repair-label">{{ props.label }}</span>

            <!-- Début de la réparation -->
            <div class="detail-item">
                <span class="dot"></span>
                <span>Date début : {{ startDate }}</span>
            </div>

            <!-- Fin de la réparation -->
            <div class="detail-item">
                <span class="dot"></span>
                <span>Date fin : {{ endDate }}</span>
            </div>

            <hr class="divider">

            <!-- Coût de la réparation -->
            <div class="detail-item">
                <span class="cost-label">Coût de réparation</span>
                <span>{{ repairCost }} FCFA</span>
            </div>

            <!-- Main d'œuvre de la réparation -->
            <div class="detail-item">
                <span class="cost-label">Main d'œuvre</span>
                <span>{{ main_doeuvre }} FCFA</span>
            </div>

            <div class="total-section">
                <div class="detail-item total">
                    <span class="cost-label">Total</span>
                    <span>{{ repairCost + main_doeuvre }} FCFA</span>
                </div>
            </div>

            <button
                v-if="props.status !== 'terminé' && props.status !== 'annulé'"
                @click="props.viewRepair"
                class="btn btn-action-modify"
            >
                <i class="bi bi-pencil-square"></i> Modifier
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    mechanicName: String,
    userImage: String,
    status: String,
    label: String,
    startDate: String,
    endDate: String,
    repairCost: Number,
    main_doeuvre: Number,
    viewRepair: Function
});

// Détermine la classe CSS selon le statut
const statusClass = computed(() => {
    switch (props.status) {
        case 'terminé': return 'status-completed';
        case 'en cours': return 'status-in-progress';
        case 'annulé': return 'status-cancelled';
        case 'en_attente': return 'status-pending';
        default: return '';
    }
});
</script>

<style scoped>
.repair-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    padding: 20px;
    margin-top: 10px;
    margin-left: 10px;
    width: 100%;
    max-width: 400px;
    border: 2px solid transparent;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.repair-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

/* Couleurs des bordures selon le statut */
.status-completed {
    border-color: #28a745; /* Vert pour terminé */
}

.status-in-progress {
    border-color: #007bff; /* Bleu pour en cours */
}

.status-cancelled {
    border-color: #6c757d; /* Gris pour annulé */
}

.status-pending {
    border-color: #ffc107; /* Jaune pour en attente */
}

/* En-tête de la carte */
.repair-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    margin-right: 12px;
    object-fit: cover;
    border: 2px solid #e9ecef;
}

.mechanic-info {
    flex-grow: 1;
}

.mechanic-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
}

.mechanic-role {
    font-size: 0.85rem;
    color: #6c757d;
}

.status {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Couleurs du badge de statut */
.status-completed .status {
    background: #28a745;
    color: white;
}

.status-in-progress .status {
    background: #007bff;
    color: white;
}

.status-cancelled .status {
    background: #6c757d;
    color: white;
}

.status-pending .status {
    background: #ffc107;
    color: #212529;
}

/* Détails de la réparation */
.repair-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.repair-label {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    border: 1px solid #2c3e50;
    padding: 10px;
    border-radius: 5px;
}


.detail-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    color: #495057;
}

.dot {
    width: 8px;
    height: 8px;
    background: #2c3e50;
    border-radius: 50%;
    margin-right: 10px;
}

.divider {
    border: 0;
    border-top: 1px solid #e9ecef;
    margin: 10px 0;
}

.cost-label {
    flex: 1;
    color: #6c757d;
}

.total-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    margin-top: 10px;
}

.total {
    font-weight: 600;
    color: #2c3e50;
}

.btn-action-modify {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    font-size: 0.9rem;
    color: white;
    background-color: #dc3545;
    border: none;
    border-radius: 8px;
    transition: all 0.2s ease;
    margin-top: 10px;
    align-self: flex-start;
}

.btn-action-modify:hover {
    background-color: white;
    color: #dc3545;
    border: 1px solid #dc3545;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-action-modify .bi {
    font-size: 1.2rem;
}
</style>