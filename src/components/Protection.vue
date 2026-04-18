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
		protections: data.settings.protections,
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
				<h2 class="title">{{ __('Protection', 'accesswise') }}</h2>
				<p class="description">{{ __('Lorem ipsum dolor sit amet consectetur. Purus interdum mi pellentesque nulla viverra pellentesque nulla consectetur.', 'accesswise') }}</p>
				<div class="grid">
					<div class="grid-item">
						<SettingSection title="Copy Protection & Disable Right Click" helpText="Regardless of this setting, this will not be impact for the Administrators." helpURL="#">
							<el-checkbox-group class="setting-input" v-model="data.settings.protections.right_click" @change="changeSetting($event)">
								<div>
									<el-checkbox :label="__('Disable Right Click', 'accesswise')" value="disable_right_click" />
								</div>
								<div>
									<el-checkbox :label="__('Disable Copy', 'accesswise')" value="disable_copy" />
								</div>
							</el-checkbox-group>
							<template v-if="data.settings.protections.right_click?.includes('disable_right_click') || data.settings.protections.right_click?.includes('disable_copy')">
								<el-text class="section-title" tag="p">{{ __('Disable Keys:', 'accesswise') }}</el-text>
								<el-checkbox-group class="setting-input" v-model="data.settings.protections.disable_keys" @change="changeSetting($event)">
									<el-checkbox :label="__('Disable Text Select', 'accesswise')" value="disable_select" />
									<el-checkbox :label="__('Disable Image Drag', 'accesswise')" value="disable_drag" />
									<el-checkbox :label="__('Disable F12', 'accesswise')" value="disable_f12" />
									<el-checkbox :label="__('Disable Ctrl + C', 'accesswise')" value="disable_ctrl_c" />
									<el-checkbox :label="__('Disable Ctrl + V', 'accesswise')" value="disable_ctrl_v" />
									<el-checkbox :label="__('Disable Ctrl + S', 'accesswise')" value="disable_ctrl_s" />
									<el-checkbox :label="__('Disable Ctrl + A', 'accesswise')" value="disable_ctrl_a" />
									<el-checkbox :label="__('Disable Ctrl + X', 'accesswise')" value="disable_ctrl_x" />
									<el-checkbox :label="__('Disable Ctrl + U', 'accesswise')" value="disable_ctrl_u" />
									<el-checkbox :label="__('Disable Ctrl + P', 'accesswise')" value="disable_ctrl_p" />
									<el-checkbox :label="__('Disable Ctrl + H', 'accesswise')" value="disable_ctrl_h" />
									<el-checkbox :label="__('Disable Ctrl + L', 'accesswise')" value="disable_ctrl_l" />
									<el-checkbox :label="__('Disable Ctrl + K', 'accesswise')" value="disable_ctrl_k" />
									<el-checkbox :label="__('Disable Ctrl + O', 'accesswise')" value="disable_ctrl_o" />
									<el-checkbox :label="__('Disable F6', 'accesswise')" value="disable_f6" />
									<el-checkbox :label="__('Disable F3', 'accesswise')" value="disable_f3" />
									<el-checkbox :label="__('Disable F9', 'accesswise')" value="disable_f9" />
									<el-checkbox :label="__('Disable Alt + D', 'accesswise')" value="disable_alt_d" />
									<el-checkbox :label="__('Disable Ctrl + E', 'accesswise')" value="disable_ctrl_e" />
								</el-checkbox-group>
							</template>
							<template v-if="data.settings.protections.right_click?.includes('disable_right_click')">
								<el-text class="section-title" tag="p">{{ __('Disable Right Click Message:', 'accesswise') }}</el-text>
								<el-input v-model="data.settings.protections.disable_right_click_msg" @input="saveWrittenMessage" size="large" :placeholder="__('Disable Right Click Message', 'accesswise')"></el-input>
							</template>
							<template v-if="data.settings.protections.right_click?.includes('disable_copy')">
								<el-text class="section-title" tag="p">{{ __('Disable Copy Message:', 'accesswise') }}</el-text>
								<el-input v-model="data.settings.protections.disable_copy_msg" @input="saveWrittenMessage" size="large" :placeholder="__('Disable Copy Message', 'accesswise')"></el-input>
							</template>
							<template v-if="data.settings.protections.right_click?.includes('disable_right_click') || data.settings.protections.right_click?.includes('disable_copy')">
								<el-text class="section-title" tag="p">{{ __('Exclude Post Types:', 'accesswise') }}</el-text>
								<el-select multiple class="m-2" placeholder="Exclude post types" size="large" v-model="data.settings.protections.right_click_exclude_posts" @change="changeSetting($event)">
									<el-option v-for="(item, key) in data.postTypes" :key="key" :label="item.label" :value="key" />
								</el-select>
								<el-text class="section-title" tag="p">{{ __('Exclude User Roles:', 'accesswise') }}</el-text>
								<el-select multiple class="m-2" placeholder="Exclude user roles" size="large" v-model="data.settings.protections.right_click_exclude_roles" @change="changeSetting($event)">
									<el-option v-for="(item, key) in data.userRoles" :key="key" :label="item" :value="key" />
								</el-select>
							</template>
						</SettingSection>
					</div>
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

		.grid {
			.grid-item {
				padding: 20px 0;
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