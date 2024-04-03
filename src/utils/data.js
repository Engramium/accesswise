import { reactive } from "vue";
import fn from "./functions";
import icons from "./icons";

const data = reactive({
    pages: null,
    rules: null,
    settings: {
        generals: {
            toolbar: [],
            redirection_after_login: 'default',
            redirection_after_logout: 'default',
            private_website: [],
            when_last_login: [],
            right_click: [],
        }
    },
    currentRoute: '/',
});

export { data, fn, icons };