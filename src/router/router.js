import { createRouter, createWebHashHistory } from 'vue-router';
import Welcome from "../components/Welcome.vue";
import Settings from "../components/Settings.vue";
import General from "../components/General.vue";
import SettingsLayout from "../components/SettingsLayout.vue";
import Protection from "../components/Protection.vue";
import Restriction from "../components/Restriction.vue";

const routes = [
	{
		path: '/',
		name: 'welcome',
		component: Welcome
	},
	{
		path: '/settings',
		component: SettingsLayout,
		redirect: '/settings/general',
		children: [
			{
				path: 'general',
				name: 'settings-general',
				component: General
			},
			{
				path: 'protection',
				name: 'settings-protection',
				component: Protection
			},
			{
				path: 'restriction',
				name: 'settings-restriction',
				component: Restriction
			}
		]
	},
];

const router = createRouter( {
	history: createWebHashHistory(),
	routes,
} );

export default router;