<template>
    <div v-show="props.show" class="modal-backdrop fade" :class="{ show: props.show }" @click.self="close">
        <div ref="modalRef" class="modal d-block" tabindex="-1" aria-modal="true" :aria-labelledby="titleId">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" :id="titleId">{{ props.title }}</h5>
                        <button type="button" class="btn-close" @click="close" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <slot></slot>
                    </div>                   
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    show: Boolean,
    title: {
        type: String,
        default: 'Titre de la modale'
    }
})

const emit = defineEmits(['close'])
const modalRef = ref(null)

const titleId = computed(() => `modal-title-${Math.random().toString(36).slice(2)}`)

function close() {
    emit('close')
}

function trapFocus(event) {
    if (event.key === 'Escape') {
        close()
        return
    }
    if (!modalRef.value) return
    const focusableElements = modalRef.value.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    )
    const firstElement = focusableElements[0]
    const lastElement = focusableElements[focusableElements.length - 1]

    if (event.key === 'Tab') {
        if (event.shiftKey && document.activeElement === firstElement) {
            event.preventDefault()
            lastElement.focus()
        } else if (!event.shiftKey && document.activeElement === lastElement) {
            event.preventDefault()
            firstElement.focus()
        }
    }
}

onMounted(() => {
    if (props.show && modalRef.value) {
        modalRef.value.focus()
        document.addEventListener('keydown', trapFocus)
    }
})

onUnmounted(() => {
    document.removeEventListener('keydown', trapFocus)
})
</script>

<style scoped>


.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.3); /* semi-transparent noir pour assombrir */
    backdrop-filter: blur(3px) grayscale(0.5); /* flou + gris 50% */
    -webkit-backdrop-filter: blur(8px) grayscale(0.5); /* compatibilité Safari */
    z-index: 1050;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: rgba(255, 255, 255, 0.95); /* légèrement transparent pour effet de profondeur */
    border-radius: 12px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.25);
    padding: 1.5rem;
    max-width: 800px;
    width: 90%;
    backdrop-filter: none; /* éviter flou sur le contenu lui-même */
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.modal.d-block {
    animation: fadeInModal 0.3s ease forwards;
}

.modal:not(.d-block) {
    animation: fadeOutModal 0.3s ease forwards;
}

.fade {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.fade.show {
    opacity: 1;
}

@keyframes fadeInModal {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOutModal {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}

</style>