import { createApp } from 'vue';
import './main.scss';
import AccesswiseApp from './App.vue';
import router from './router/router.js';
import menuFix from './utils/admin-menu-fix.js';

const { __, _x, _n, _nx } = wp.i18n;

const app = createApp( AccesswiseApp );

// localize data bind
app.config.globalProperties.accesswise = accesswise;

//translator function bind
app.config.globalProperties.__ = __;
app.config.globalProperties._x = _x;
app.config.globalProperties._n = _n;
app.config.globalProperties._nx = _nx;

// app mount
app.use( router ).mount( '#accesswise-dashboard-app' );

// fix the admin menu for the slug "accesswise-settings"
menuFix( 'accesswise-settings' );