<script setup>
import { useDebounceFn } from "@vueuse/core";
import { data, fn } from "../utils/data";
import SettingItem from './parts/SettingItem.vue';

const changeSetting = () => {
	updateSetting();
};

const saveWrittenMessage = useDebounceFn( () => {
	updateSetting();
}, 1000 );

const updateSetting = () => {
	const res = fn.fetchAdminAjax( accesswise.admin_ajax, "post", {
		action: "accesswise_update_settings",
		settings: data.settings,
		nonce: accesswise.nonce,
	} );

	let msg = wp.i18n.__( 'Settings have been successfully updated.', 'accesswise' );

	res.then( ( response ) => {
		if ( response.status ) {
			ElNotification( {
				title: wp.i18n.__( 'Success', 'accesswise' ),
				message: msg,
				type: "success",
				offset: 50,
			} );
		} else {
			ElNotification( {
				title: wp.i18n.__( 'Error', 'accesswise' ),
				message: response.msg,
				type: "error",
				offset: 50,
			} );
		}
	} );
};
</script>
<template>
	<div v-if="data.settings != null && data.pages != null" class="settings-wrap">
		<div class="feature-wrap">
			<div class="feature-content">
				<h2 class="title">{{ __('General', 'accesswise') }}</h2>
				<p class="description">{{ __('Configure toolbar visibility, login and logout redirects, and user last-login tracking.', 'accesswise') }}</p>
				<div class="items-wrap">
					<SettingItem :title="__('Toolbar', 'accesswise')">
						<el-checkbox-group class="setting-input" v-model="data.settings.generals.toolbar" @change="changeSetting($event)">
							<div>
								<el-checkbox :label="__('Show the Toolbar for logged-in admins', 'accesswise')" value="show_for_admins" />
							</div>
							<div>
								<el-checkbox :label="__('Show the Toolbar for logged-in members (non-admins)', 'accesswise')" value="show_for_non_admins" />
							</div>
							<div>
								<el-checkbox :label="__('Show the Toolbar for logged out users', 'accesswise')" value="show_for_public" />
							</div>
						</el-checkbox-group>
					</SettingItem>
					<SettingItem :title="__('Redirection (After Login)', 'accesswise')">
						<el-select class="m-2" placeholder="Select" filterable size="large" v-model="data.settings.generals.redirection_after_login" @change="changeSetting($event)">
							<el-option v-for="(item, key) in data.pages" :key="key" :label="item" :value="key" />
						</el-select>
					</SettingItem>
					<SettingItem :title="__('Redirection (After Logout)', 'accesswise')">
						<el-select class="m-2" placeholder="Select" filterable size="large" v-model="data.settings.generals.redirection_after_logout" @change="changeSetting($event)">
							<el-option v-for="(item, key) in data.pages" :key="key" :label="item" :value="key" />
						</el-select>
					</SettingItem>
					<SettingItem :title="__('When Last Login', 'accesswise')">
						<el-checkbox-group class="setting-input" v-model="data.settings.generals.when_last_login" @change="changeSetting($event)">
							<div>
								<el-checkbox :label="__('Show Last login in users', 'accesswise')" value="show_last_login" />
							</div>
						</el-checkbox-group>
					</SettingItem>
				</div>
			</div>
		</div>
	</div>
</template>
<style scoped lang="scss">
.feature-wrap {
	&:not(:first-child) {
		margin-top: 50px;
	}

	.feature-header {
		@include flex();
		margin-bottom: 30px;

		.btn-wrap {
			@include flex();
		}
	}

	.feature-content {
		.title {
			font-size: 24px;
			font-weight: 600;
			margin-bottom: 10px;
		}

		.description {
			font-size: 16px;
			color: #666;
			margin-bottom: 20px;
		}

		.items-wrap {
			@include flex(column, flex-start, flex-start);
			gap: 20px;
		}
	}
}
</style>
