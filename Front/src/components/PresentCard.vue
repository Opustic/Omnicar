<template>
    <div class="card custom-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="card-label mb-0">{{ props.label }}</p>
        <div class="card-icon" :style="{ color: props.color }">
            <slot></slot>
        </div>
        </div>

        <div class="card-amount" :style="{ color: props.color }">
        {{ props.amount }} <span v-if="props.recette" class="currency">FCFA</span>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    label: String,
    amount: { type: Number, default: 0 },
    recette: { type: Boolean, default: false },
    color: { type: String, default: '#000' }
})
</script>

<style scoped>
.custom-card {
    background-color: #ffffff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    transition: box-shadow 0.3s ease;
    width: 100%;
}

.custom-card:hover {
  box-shadow: 0 0.75rem 1.5rem rgba(0,0,0,0.15);
}

.card-label {
  font-size: 1.2rem;
  color: #6c757d;
  font-weight: 500;
}

.card-icon {
  font-size: 2rem;
  font-weight: 700;
}

.card-amount {
  font-size: 1.75rem;
  font-weight: 800;
}

.currency {
  font-size: 0.9rem;
  font-weight: 500;
  margin-left: 0.25rem;
  color: #6c757d;
}
</style>
