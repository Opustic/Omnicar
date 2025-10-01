<template>
    <div class="login-container">
        <div class="login-background">
            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
            <div class="circle circle-3"></div>
        </div>

        <div class="login-card">
            <div class="card-header">

                <div class="d-flex justify-content-center mb-4">
                    <div class="logo-wrapper">
                    </div>
                </div>

                <h2 class="title">Bienvenue</h2>
                <p class="subtitle">Connectez-vous à votre compte</p>
            </div>

            <form class="login-form">
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope-fill"></i>
                        Adresse email
                    </label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            id="email"
                            v-model="email"
                            placeholder="exemple@email.com" 
                            class="form-control custom-input" 
                            required
                        >
                        <i class="bi bi-at input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock-fill"></i>
                        Mot de passe
                    </label>
                    <div class="input-wrapper">
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            id="password"
                            v-model="password"
                            placeholder="••••••••" 
                            class="form-control custom-input" 
                            required
                        >
                        <i 
                            :class="showPassword ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'" 
                            class="input-icon toggle-password"
                            @click="togglePassword"
                        ></i>
                    </div>
                </div>

                <!-- <div class="form-options">
                    <a href="#" class="forgot-password">Mot de passe oublié?</a>
                </div> -->

                <button type="submit" class="btn-login p-2 fs-5 fw-bold" @click.prevent="fake_login">
                    <span>Se connecter </span>
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>

                
            </form>
        </div>
    </div>
</template>


<script setup>
import { useRouter } from 'vue-router';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';
import { useUsersStore } from '@/store/users';

const usersStore = useUsersStore();

const toast = useToast();
const router = useRouter();

const email = ref('');
const password = ref('');
const showPassword = ref(false);

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

// fake informations for testing
const proprio_user_informations = ref({
    email : "fgaboumba@gmail.com",
    password : "Proprietaire2025##;"
}) 

const admin_user_informations = ref({
    email : "bino20fr@gmail.com",
    password : "Admin()2025#"
})

// fake login function
const fake_login = async () => {
    
    if (email.value === proprio_user_informations.value.email && password.value === proprio_user_informations.value.password) {

        toast.success("Connexion réussie");
        usersStore.setUserName('Proprio');
        usersStore.setUserMail(email.value);
        router.push('/menu');

    } else if (email.value === admin_user_informations.value.email && password.value === admin_user_informations.value.password) {

        toast.success("Connexion réussie");
        usersStore.setUserName('Admin');
        usersStore.setUserMail(email.value);
        router.push('/menu');

    } else {
        toast.error("Connexion échouée");

    }


}

</script>



<style scoped>
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
    position: fixed;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    z-index: 9999;
    overflow: hidden;
}


.login-background {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}


.logo-wrapper {
    width: 75px;
    height: 75px;
    background-image: url("/public/omnicar-logo.png");
    background-position: center;
    background-size: contain;
    border-radius: 50%;
}



.login-card {
    background: white;
    border-radius: 24px;
    padding: 48px;
    width: 100%;
    max-width: 480px;
    position: relative;
    z-index: 10;
    animation: slideIn 0.6s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-header {
    text-align: center;
    margin-bottom: 40px;
}

.title {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 8px;
    letter-spacing: -0.5px;
}

.subtitle {
    color: #6b7280;
    font-size: 15px;
    margin: 0;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.form-label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-label i {
    font-size: 16px;
}

.input-wrapper {
    position: relative;
}

.custom-input {
    width: 100%;
    padding: 14px 44px 14px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.custom-input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.input-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 18px;
}

.toggle-password {
    cursor: pointer;
    transition: color 0.2s ease;
}

.toggle-password:hover {
    color: #667eea;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: -8px;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-check-input {
    width: 18px;
    height: 18px;
    cursor: pointer;
    border: 2px solid #d1d5db;
}



.forgot-password {
    font-size: 14px;
    color:#1EC5A0;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
}

.forgot-password:hover {
    text-decoration: underline;
}

.btn-login {
    width: 100%;
    padding: 0;
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    margin-top: 8px;
    background: linear-gradient(135deg, #21486B, #1EC5A0);
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(102, 126, 234, 0.4);
}

.btn-login:active {
    transform: translateY(0);
}

.login-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px;
    text-decoration: none;
    color: white;
    font-weight: 600;
    font-size: 16px;
    width: 100%;
}

.login-link i {
    font-size: 20px;
    transition: transform 0.3s ease;
}

.btn-login:hover .login-link i {
    transform: translateX(4px);
}

/* Responsive */
@media (max-width: 576px) {
    .login-card {
        padding: 32px 24px;
    }

    .title {
        font-size: 28px;
    }

}
</style>