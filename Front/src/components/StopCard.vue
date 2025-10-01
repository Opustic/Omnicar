<template>
    <div class="stop-card">
        <!-- Motif du stop -->
        <div class="stop-header">
            <div class="stop-label">{{ props.stopLabel }}</div>
        </div>

        <!-- Date du stop -->
        <div class="stop-details">
            <div class="detail-item">
                <span class="dot"></span>
                <span>Date : {{ props.stopDate }} </span>
            </div>
        </div>
    </div>
</template>



<script setup>


const props = defineProps({
    stopLabel: String,
    stopDate: String
});
</script>



<style scoped>
.stop-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 15px;
    margin: 10px;
    width: 95%;
    border: 1px solid var(--color-accent-red);
}

.stop-header {
    margin-bottom: 10px;
}

.stop-label {
    font-weight: bold;
    font-size: 16px;
}

.stop-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
}


.detail-item {
    display: flex;
    align-items: center;
    font-size: 14px;
}


.dot {
    width: 10px;
    height: 10px;
    background: var(--color-accent-red);
    border: 1px solid black;
    border-radius: 50%;
    margin-right: 10px;
}
</style>