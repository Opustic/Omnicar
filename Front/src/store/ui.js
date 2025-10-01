import { defineStore } from "pinia";
import {ref} from "vue"


export const useUiStore = defineStore("ui", ()=>{
    const globalLoading = ref(false)

    const setloading = (value) => {
        globalLoading.value = value
    }


    return {
        globalLoading,
        setloading
    }
})

