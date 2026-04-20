<script setup>
import { data, fn, icons } from "./utils/data";
import Header from "./components/parts/Header.vue";
import { onMounted } from "vue";

const getSettings = async () => {
	const response = await fn.fetchAdminAjax( accesswise.admin_ajax, "get", {
		action: "accesswise_get_settings",
		nonce: accesswise.nonce,
	} );

	if ( ! response.status ) {
		return;
	}

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
	data.settings.protections.cp_exclude_posts = data.settings.protections.cp_exclude_posts ?? [];
	data.settings.protections.cp_exclude_individual_posts = data.settings.protections.cp_exclude_individual_posts ?? [];
	data.settings.protections.cp_exclude_roles = data.settings.protections.cp_exclude_roles ?? [ 'administrator' ];
	data.settings.protections.cp_text_selection = data.settings.protections.cp_text_selection ?? false;
	data.settings.protections.cp_exclude_inputs = data.settings.protections.cp_exclude_inputs ?? false;
	data.settings.protections.cp_exclude_css_selector = data.settings.protections.cp_exclude_css_selector ?? '';
	data.settings.protections.cp_msg = data.settings.protections.cp_msg ?? 'Cut/Copy/Paste is disabled!';
	data.settings.protections.cp_protect_no_js = data.settings.protections.cp_protect_no_js ?? false;
	data.settings.protections.cp_no_js_msg = data.settings.protections.cp_no_js_msg ?? 'Javascript is disabled!';

	data.settings.protections.right_click = data.settings.protections.right_click ?? false;
	data.settings.protections.rc_exclude_posts = data.settings.protections.rc_exclude_posts ?? [];
	data.settings.protections.rc_disable_images = data.settings.protections.rc_disable_images ?? false;
	data.settings.protections.rc_disable_links = data.settings.protections.rc_disable_links ?? false;
	data.settings.protections.rc_disable_dev_keys = data.settings.protections.rc_disable_dev_keys ?? false;
	data.settings.protections.rc_disable_drag_drop = data.settings.protections.rc_disable_drag_drop ?? false;
	data.settings.protections.rc_disable_keys = data.settings.protections.rc_disable_keys ?? [];
	data.settings.protections.rc_disable_left_click = data.settings.protections.rc_disable_left_click ?? false;
	data.settings.protections.rc_disable_scroll_img_mobile = data.settings.protections.rc_disable_scroll_img_mobile ?? false;
	data.settings.protections.rc_disable_msg = data.settings.protections.rc_disable_msg ?? 'Right click is disabled!';
	data.settings.protections.rc_protect_no_js = data.settings.protections.rc_protect_no_js ?? false;
	data.settings.protections.rc_no_js_msg = data.settings.protections.rc_no_js_msg ?? 'Javascript is disabled!';
	data.settings.protections.rc_enable_copyright = data.settings.protections.rc_enable_copyright ?? false;
	data.settings.protections.rc_copyright_msg = data.settings.protections.rc_copyright_msg ?? '';
	data.settings.protections.rc_enable_pasting = data.settings.protections.rc_enable_pasting ?? false;
	data.settings.protections.rc_pasting_msg = data.settings.protections.rc_pasting_msg ?? '';
	data.settings.protections.rc_exclude_roles = data.settings.protections.rc_exclude_roles ?? [ 'administrator' ];
	data.settings.protections.rc_protect_individual_posts = data.settings.protections.rc_protect_individual_posts ?? [];

	// restrictions defaults
	data.settings.restrictions = data.settings.restrictions || {};
	data.settings.restrictions.private_website = data.settings.restrictions.private_website ?? [];
	data.settings.restrictions.public_website_contents = data.settings.restrictions.public_website_contents ?? '';
};

const getWpPages = async () => {
	const response = await fn.fetchPublicUrl( accesswise.rest_url + "wp/v2/pages", 'get' );
	const formatPages = { default: 'Default' };

	response.forEach( element => {
		formatPages[ element.id ] = element.title.rendered;
	} );

	data.pages = formatPages;
};

const getWpPostTypes = async () => {
	const response = await fn.fetchAdminAjax( accesswise.admin_ajax, 'get', {
		action: 'accesswise_get_post_types',
		nonce: accesswise.nonce,
	} );

	data.postTypes = response.data || {};
};

const getWpPosts = async () => {
	const response = await fn.fetchAdminAjax( accesswise.admin_ajax, 'get', {
		action: 'accesswise_get_posts',
		nonce: accesswise.nonce,
	} );

	data.individualPosts = response.data || {};
};

const getUserRoles = async () => {
	const response = await fn.fetchAdminAjax( accesswise.admin_ajax, 'get', {
		action: 'accesswise_get_user_roles',
		nonce: accesswise.nonce,
	} );

	data.userRoles = response.data || {};
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
		getWpPosts(),
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
}

.accesswise-header,
.accesswise-footer,
.accesswise-content {
	width: 100%;
}

.accesswise-content {
	.content {
		padding: 48px;
	}
}
</style>
