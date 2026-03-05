<script setup>
import { useDebounceFn } from "@vueuse/core";
import { data, fn } from "../utils/data";
import SettingSection from "./parts/SettingSection.vue";

const changeSetting = () => {
	updateSetting();
};

const saveWrittenMessage = useDebounceFn( () => {
	updateSetting();
}, 1000 );

const updateSetting = () => {
	const res = fn.fetchAdminAjax( accesswise.admin_ajax, "post", {
		action: "accesswise_update_settings",
		generals: data.settings.generals,
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
				<div class="grid">
					<div class="grid-item">
						<SettingSection title="Copy Protection & Disable Right Click" helpText="Regardless of this setting, this will not be impact for the Administrators." helpURL="#">
							<el-checkbox-group class="setting-input" v-model="data.settings.generals.right_click" @change="changeSetting($event)">
								<div>
									<el-checkbox :label="__('Disable Right Click', 'accesswise')" value="disable_right_click" />
								</div>
								<div>
									<el-checkbox :label="__('Disable Copy', 'accesswise')" value="disable_copy" />
								</div>
							</el-checkbox-group>
							<template v-if="data.settings.generals.right_click?.includes('disable_right_click') || data.settings.generals.right_click?.includes('disable_copy')">
								<el-text class="section-title" tag="p">{{ __('Disable Keys:', 'accesswise') }}</el-text>
								<el-checkbox-group class="setting-input" v-model="data.settings.generals.disable_keys" @change="changeSetting($event)">
									<el-checkbox :label="__('Disable Text Select', 'accesswise')" value="disable_select" />
									<el-checkbox :label="__('Disable Image Drag', 'accesswise')" value="disable_drag" />
									<el-checkbox :label="__('Disable Ctrl + A', 'accesswise')" value="disable_ctrl_a" />
									<el-checkbox :label="__('Disable Ctrl + C', 'accesswise')" value="disable_ctrl_c" />
									<el-checkbox :label="__('Disable Ctrl + V', 'accesswise')" value="disable_ctrl_v" />
									<el-checkbox :label="__('Disable Ctrl + X', 'accesswise')" value="disable_ctrl_x" />
									<el-checkbox :label="__('Disable Ctrl + U', 'accesswise')" value="disable_ctrl_u" />
									<el-checkbox :label="__('Disable Ctrl + P', 'accesswise')" value="disable_ctrl_p" />
									<el-checkbox :label="__('Disable Ctrl + S', 'accesswise')" value="disable_ctrl_s" />
									<el-checkbox :label="__('Disable F12', 'accesswise')" value="disable_f12" />
								</el-checkbox-group>
							</template>
							<template v-if="data.settings.generals.right_click?.includes('disable_right_click')">
								<el-text class="section-title" tag="p">{{ __('Disable Right Click Message:', 'accesswise') }}</el-text>
								<el-input v-model="data.settings.generals.disable_right_click_msg" @input="saveWrittenMessage" size="large" :placeholder="__('Disable Right Click Message', 'accesswise')"></el-input>
							</template>
							<template v-if="data.settings.generals.right_click?.includes('disable_copy')">
								<el-text class="section-title" tag="p">{{ __('Disable Copy Message:', 'accesswise') }}</el-text>
								<el-input v-model="data.settings.generals.disable_copy_msg" @input="saveWrittenMessage" size="large" :placeholder="__('Disable Copy Message', 'accesswise')"></el-input>
							</template>
							<template v-if="data.settings.generals.right_click?.includes('disable_right_click') || data.settings.generals.right_click?.includes('disable_copy')">
								<el-text class="section-title" tag="p">{{ __('Exclude Post Types:', 'accesswise') }}</el-text>
								<el-select multiple class="m-2" placeholder="Exclude post types" size="large" v-model="data.settings.generals.right_click_exclude_posts" @change="changeSetting($event)">
									<el-option v-for="(item, key) in data.postTypes" :key="key" :label="item.label" :value="key" />
								</el-select>
								<el-text class="section-title" tag="p">{{ __('Exclude User Roles:', 'accesswise') }}</el-text>
								<el-select multiple class="m-2" placeholder="Exclude user roles" size="large" v-model="data.settings.generals.right_click_exclude_roles" @change="changeSetting($event)">
									<el-option v-for="(item, key) in data.userRoles" :key="key" :label="item" :value="key" />
								</el-select>
							</template>
						</SettingSection>
					</div>
					<div class="grid-item">
						<SettingSection :title="__('Toolbar', 'accesswise')" :helpText="__('The admin Toolbar is a horizontal black bar at the top of the screen.', 'accesswise')" :helpURL="__('https://example.com/toolbar-help', 'accesswise')">
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
						</SettingSection>
					</div>
					<div class="grid-item">
						<SettingSection title="Redirection (After Login)" helpText="Forward to your preferred page or post type, depending on the user's logged-in state." helpURL="#">
							<el-select class="m-2" placeholder="Select" size="large" style="width: 240px" v-model="data.settings.generals.redirection_after_login" @change="changeSetting($event)">
								<el-option v-for="(item, key) in data.pages" :key="key" :label="item" :value="key" />
							</el-select>
						</SettingSection>
					</div>
					<div class="grid-item">
						<SettingSection title="Redirection (After Logout)" helpText="Forward to your preferred page or post type, depending on the user's logged-out state." helpURL="#">
							<el-select class="m-2" placeholder="Select" size="large" style="width: 240px" v-model="data.settings.generals.redirection_after_logout" @change="changeSetting($event)">
								<el-option v-for="(item, key) in data.pages" :key="key" :label="item" :value="key" />
							</el-select>
						</SettingSection>
					</div>
					<div class="grid-item">
						<SettingSection title="Private Website" helpText="Login and Registration content will remain publicly visible." helpURL="#">
							<el-checkbox-group class="setting-input" v-model="data.settings.generals.private_website" @change="changeSetting($event)">
								<div>
									<el-checkbox :label="__('Restrict site access to only logged-in members', 'accesswise')" value="logged_in_users" />
								</div>
							</el-checkbox-group>
							<div v-if="data.settings.generals.private_website?.includes('logged_in_users')" class="setting-input">
								<el-input v-model="data.settings.generals.public_website_contents" @input="saveWrittenMessage" :autosize="{ minRows: 4, maxRows: 10 }" type="textarea" placeholder="e.g. /groups/" />
								<label>{{ __('Enter URLs or URI fragments (e.g. /groups/) to remain publicly visible always. Enter one URL or URI per line.', 'accesswise') }}</label>
							</div>
						</SettingSection>
					</div>
					<div class="grid-item">
						<SettingSection title="When Last Login" helpText="When Last Login" helpURL="#">
							<el-checkbox-group class="setting-input" v-model="data.settings.generals.when_last_login" @change="changeSetting($event)">
								<div>
									<el-checkbox :label="__('Show Last login in users', 'accesswise')" value="show_last_login" />
								</div>
							</el-checkbox-group>
						</SettingSection>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<style scoped lang="scss">
@import "../scss/_variables";
@import "../scss/_mixins";

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
		.grid {
			.grid-item {
				@include card();
				padding: 20px;
				margin: 30px 0;
				max-width: 1124px;

				.section-title {
					align-self: flex-start;
					font-weight: bold;
				}
			}
		}
	}
}
</style>