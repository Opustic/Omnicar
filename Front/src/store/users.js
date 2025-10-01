import { defineStore } from "pinia";
import { ref } from "vue";


export const useUsersStore = defineStore ("user", ()=> {

    const user_name = ref(null)
    const user_mail = ref(null)

    const setUserName = (name) => {
        user_name.value = name
    }

    const setUserMail = (mail) => {
        user_mail.value = mail
    }

    return { user_name, user_mail, setUserName, setUserMail }

})