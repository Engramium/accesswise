import { createRouter, createWebHashHistory } from 'vue-router';
import Welcome from "../components/Welcome.vue";
import Settings from "../components/Settings.vue";

const routes = [
	{
		path: '/',
		name: 'welcome',
		component: Welcome
	},
	{
		path: '/settings',
		name: 'settings',
		component: Settings
	},
];

const router = createRouter( {
	history: createWebHashHistory(),
	routes,
} );

export default router;