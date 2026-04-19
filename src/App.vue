<script setup>
import { data, fn, icons } from "./utils/data";
import Header from "./components/parts/Header.vue";
import { onMounted } from "vue";

const getSettings = async () => {
	const res = fn.fetchAdminAjax( accesswise.admin_ajax, "get", {
		action: "accesswise_get_settings",
		nonce: accesswise.nonce,
	} );

	res.then( ( response ) => {
		if ( response.status ) {
			if ( Array.isArray( response.data ) ) {
				data.settings = {};
			} else {
				data.settings = response.data || {};
			}

			// generals defaults
			data.settings.generals = data.settings.generals || {};
			data.settings.generals.toolbar = data.settings.generals.toolbar ?? [ 'show_for_admins', 'show_for_non_admins' ];
			data.settings.generals.redirection_after_login = data.settings.generals.redirection_after_login ?? 'default';
			data.settings.generals.redirection_after_logout = data.settings.generals.redirection_after_logout ?? 'default';
			data.settings.generals.when_last_login = data.settings.generals.when_last_login ?? [];

			// protections defaults
			data.settings.protections = data.settings.protections || {};
			data.settings.protections.copy_protection = data.settings.protections.copy_protection ?? false;
			data.settings.protections.cp_msg = data.settings.protections.cp_msg ?? 'Cut/Copy/Paste is disabled!';
			data.settings.protections.cp_exclude_posts = data.settings.protections.cp_exclude_posts ?? [];
			data.settings.protections.cp_exclude_roles = data.settings.protections.cp_exclude_roles ?? [ 'administrator' ];

			data.settings.protections.right_click = data.settings.protections.right_click ?? false;
			data.settings.protections.rc_disable_keys = data.settings.protections.rc_disable_keys ?? [];
			data.settings.protections.rc_disable_msg = data.settings.protections.rc_disable_msg ?? 'Right click is disabled!';
			data.settings.protections.rc_exclude_posts = data.settings.protections.rc_exclude_posts ?? [];
			data.settings.protections.rc_exclude_roles = data.settings.protections.rc_exclude_roles ?? [ 'administrator' ];

			// restrictions defaults
			data.settings.restrictions = data.settings.restrictions || {};
			data.settings.restrictions.private_website = data.settings.restrictions.private_website ?? [];
			data.settings.restrictions.public_website_contents = data.settings.restrictions.public_website_contents ?? '';
		}
	} );
};

const getWpPages = async () => {
	const res = fn.fetchPublicUrl( accesswise.rest_url + "wp/v2/pages", 'get' );
	res.then( response => {
		let formatPages = { default: 'Default' };
		response.forEach( element => {
			formatPages[ element.id ] = element.title.rendered;
		} );
		data.pages = formatPages;
	} );
};

const getWpPostTypes = async () => {
	const res = fn.fetchAdminAjax( accesswise.admin_ajax, 'get', {
		action: 'accesswise_get_post_types',
		nonce: accesswise.nonce,
	} );
	res.then( response => {
		data.postTypes = response.data;
	} );
};

const getUserRoles = async () => {
	const res = fn.fetchAdminAjax( accesswise.admin_ajax, 'get', {
		action: 'accesswise_get_user_roles',
		nonce: accesswise.nonce,
	} );
	res.then( response => {
		data.userRoles = response.data;
	} );
};

const initializeApp = async () => {
	const loading = ElLoading.service( {
		fullscreen: true,
		lock: true,
		text: "Loading",
		background: "rgba(0, 0, 0, 0.7)",
	} );

	await Promise.all( [
		getSettings(),
		getWpPages(),
		getWpPostTypes(),
		getUserRoles()
	] );

	loading.close();
};

onMounted( () => {
	initializeApp();
} );
</script>

<template>
	<div class="accesswise-layout" :class="data.currentTab">
		<Header></Header>
		<div class="accesswise-content">
			<div class="content">
				<router-view></router-view>
			</div>
		</div>
	</div>
</template>

<style scoped lang="scss">
.accesswise-layout {
	@include flex(column, space-between, center);
	height: 100%;
	gap: 48px;
}

.accesswise-header,
.accesswise-footer,
.accesswise-content {
	width: 100%;
}

.accesswise-content {
	.content {
		padding: 0 48px;
	}
}
</style>