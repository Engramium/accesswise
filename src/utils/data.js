import { reactive } from "vue";
import fn from "./functions";
import icons from "./icons";

const data = reactive( {
	pages: null,
	postTypes: null,
	individualPosts: null,
	userRoles: null,
	rules: null,
	settings: null,
	currentRouteName: 'welcome',
} );

export { data, fn, icons };
