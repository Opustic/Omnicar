<template>
    <div class="stat-card" :class="cardClass">
        <div class="card-content">
            <div class="icon-wrapper">
                <i :class="iconClass"></i>
            </div>
            <div class="card-body-content">
                <h6 class="card-label">
                    <i :class="smallIconClass"></i>
                    Recette {{ props.label }}
                </h6>
                <h3 class="text-dark montant">
                    {{ formatCFA(props.montant) }}
                </h3>
            </div>
        </div>
        <div class="card-decoration"></div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { formatCFA } from '@/utils/format';

const props = defineProps({
    label: String,
    montant: { type: Number, default: 0 }
});

const cardClass = computed(() => {
    const label = props.label?.toLowerCase();
    if (label === 'chauffeur') return 'card-chauffeur';
    if (label === 'controleur') return 'card-controleur';
    if (label === 'total') return 'card-total';
    return 'card-default';
});

const iconClass = computed(() => {
    const label = props.label?.toLowerCase();
    if (label === 'chauffeur') return 'bi bi-person-circle';
    if (label === 'controleur') return 'bi bi-clipboard-check';
    if (label === 'total') return 'bi bi-calculator';
    return 'bi bi-cash-coin';
});

const smallIconClass = computed(() => {
    const label = props.label?.toLowerCase();
    if (label === 'chauffeur') return 'bi bi-steering-wheel';
    if (label === 'controleur') return 'bi bi-ticket-perforated';
    if (label === 'total') return 'bi bi-graph-up-arrow';
    return 'bi bi-wallet2';
});
</script>

<style scoped>
.stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border: none;
    min-height: 140px;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.card-content {
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
    z-index: 2;
}

.icon-wrapper {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.stat-card:hover .icon-wrapper {
    transform: scale(1.1) rotate(5deg);
}

.icon-wrapper i {
    font-size: 32px;
    color: white;
}

.card-body-content {
    flex: 1;
}

.card-label {
    font-size: 13px;
    font-family: var(--font-title-small), -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-weight: 600;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.card-label i {
    font-size: 14px;
}

.montant {
    font-size: 28px;
    font-family: var(--font-title-small), -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-weight: 700;
    margin: 0;
    letter-spacing: -0.5px;
}

.card-decoration {
    position: absolute;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    opacity: 0.1;
    top: -50px;
    right: -50px;
    transition: all 0.3s ease;
}

.stat-card:hover .card-decoration {
    transform: scale(1.2);
    opacity: 0.15;
}

/* Styles spécifiques pour Chauffeur */
.card-chauffeur {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
    border-left: 5px solid #4F46E5;
}

.card-chauffeur .icon-wrapper {
    background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}

.card-chauffeur .card-label {
    color: #4F46E5;
}

.card-chauffeur .montant {
    color: #312E81;
}

.card-chauffeur .card-decoration {
    background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
}

/* Styles spécifiques pour Controleur */
.card-controleur {
    background: linear-gradient(135deg, #ffffff 0%, #fff8f0 100%);
    border-left: 5px solid #F59E0B;
}

.card-controleur .icon-wrapper {
    background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.card-controleur .card-label {
    color: #D97706;
}

.card-controleur .montant {
    color: #92400E;
}

.card-controleur .card-decoration {
    background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
}

/* Styles spécifiques pour Total */
.card-total {
    background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
    border-left: 5px solid #10B981;
}

.card-total .icon-wrapper {
    background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.card-total .card-label {
    color: #059669;
}

.card-total .montant {
    color: #064E3B;
    font-size: 32px;
}

.card-total .card-decoration {
    background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
}

.card-total {
    border-left-width: 6px;
}

/* Style par défaut */
.card-default {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-left: 5px solid #6B7280;
}

.card-default .icon-wrapper {
    background: linear-gradient(135deg, #6B7280 0%, #9CA3AF 100%);
    box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
}

.card-default .card-label {
    color: #6B7280;
}

.card-default .montant {
    color: #374151;
}

.card-default .card-decoration {
    background: linear-gradient(135deg, #6B7280 0%, #9CA3AF 100%);
}

/* Animation d'entrée */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card {
    animation: slideInUp 0.5s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .stat-card {
        padding: 20px;
        min-height: 120px;
    }

    .icon-wrapper {
        width: 60px;
        height: 60px;
    }

    .icon-wrapper i {
        font-size: 28px;
    }

    .montant {
        font-size: 24px;
    }

    .card-total .montant {
        font-size: 28px;
    }
}
</style>