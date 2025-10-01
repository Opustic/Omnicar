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
    </div>
</template>

<script setup>

import { formatCFA } from '@/utils/format';

const props = defineProps({
    label: String,
    amount: String,
    date: String
});
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
