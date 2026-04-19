<script setup>
import { ref } from 'vue';
import { useDebounceFn } from "@vueuse/core";
import { data, fn } from "../utils/data";
import SettingItem from './parts/SettingItem.vue';

const currentTab = ref( 'copy_protection' );

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
				<h2 class="title">{{ __('Protection', 'accesswise') }}</h2>
				<p class="description">{{ __('Lorem ipsum dolor sit amet consectetur. Purus interdum mi pellentesque nulla viverra pellentesque nulla consectetur.', 'accesswise') }}</p>
				<el-tabs v-model="currentTab" class="demo-tabs">
					<el-tab-pane :label="__('Copy Protection', 'accesswise')" name="copy_protection">
						<div class="items-wrap">
							<SettingItem :title="__('Copy Protection', 'accesswise')" :description="__('Regardless of this setting, this will not be impact for the Administrators.', 'accesswise')" :isInline="true">
								<el-switch
									class="setting-input"
									v-model="data.settings.protections.copy_protection"
									size="large"
									@change="changeSetting($event)" />
							</SettingItem>
							<template v-if="data.settings.protections.copy_protection">
								<SettingItem :title="__('Exclude Post Types:', 'accesswise')">
									<el-select multiple class="m-2" placeholder="Exclude post types" size="large" v-model="data.settings.protections.cp_exclude_posts" @change="changeSetting($event)">
										<el-option v-for="(item, key) in data.postTypes" :key="key" :label="item.label" :value="key" />
									</el-select>
								</SettingItem>
								<SettingItem :title="__('Exclude Individual Posts:', 'accesswise')">
									<el-select multiple class="m-2" placeholder="Exclude individual posts" size="large" v-model="data.settings.protections.cp_exclude_individual_posts" @change="changeSetting($event)">
										<el-option v-for="(item, key) in data.postTypes" :key="key" :label="item.label" :value="key" />
									</el-select>
								</SettingItem>
								<SettingItem :title="__('Exclude User Roles:', 'accesswise')">
									<el-select multiple class="m-2" placeholder="Exclude user roles" size="large" v-model="data.settings.protections.cp_exclude_roles" @change="changeSetting($event)">
										<el-option v-for="(item, key) in data.userRoles" :key="key" :label="item" :value="key" />
									</el-select>
								</SettingItem>
								<SettingItem :title="__('Enable Text Selection', 'accesswise')">
									<el-checkbox class="setting-input" v-model="data.settings.protections.cp_text_selection" @change="changeSetting($event)" :label="__('Enable Text Selection', 'accesswise')" value="true" />
								</SettingItem>
								<SettingItem :title="__('Exclude Input and Textarea', 'accesswise')">
									<el-checkbox class="setting-input" v-model="data.settings.protections.cp_exclude_inputs" @change="changeSetting($event)" :label="__('Exclude Input and Textarea', 'accesswise')" value="true" />
								</SettingItem>
								<SettingItem :title="__('Exclude certain CSS selector', 'accesswise')">
									<el-input v-model="data.settings.protections.cp_exclude_css_selector" @input="saveWrittenMessage" size="large" :autosize="{ minRows: 4, maxRows: 10 }" type="textarea" :placeholder="__('e.g. .my-class', 'accesswise')"></el-input>
									<label>{{ __('Enter CSS selectors (e.g. .my-class) to exclude from copy protection. Enter one selector per line.', 'accesswise') }}</label>
								</SettingItem>
								<SettingItem :title="__('Disable Copy Message:', 'accesswise')">
									<el-input v-model="data.settings.protections.cp_msg" @input="saveWrittenMessage" size="large" :placeholder="__('Disable Copy Message', 'accesswise')"></el-input>
								</SettingItem>
								<SettingItem :title="__('Protect when Javascript is disabled', 'accesswise')">
									<el-checkbox class="setting-input" v-model="data.settings.protections.cp_protect_no_js" @change="changeSetting($event)" :label="__('Protect when Javascript is disabled', 'accesswise')" value="true" />
								</SettingItem>
								<SettingItem v-if="data.settings.protections.cp_protect_no_js" :title="__('Message while Javascript is disabled', 'accesswise')">
									<el-input v-model="data.settings.protections.cp_no_js_msg" @input="saveWrittenMessage" size="large" :placeholder="__('Message while Javascript is disabled', 'accesswise')"></el-input>
								</SettingItem>
							</template>
						</div>
					</el-tab-pane>
					<el-tab-pane label="Disable Right Click" name="disable_right_click">
						<div class="items-wrap">
							<SettingItem title="Disable Right Click" description="Regardless of this setting, this will not be impact for the Administrators." :isInline="true">
								<el-switch class="setting-input" v-model="data.settings.protections.right_click" size="large" @change="changeSetting($event)" />
							</SettingItem>
							<template v-if="data.settings.protections.right_click">
								<SettingItem :title="__('Exclude Post Types:', 'accesswise')">
									<el-select multiple class="m-2" placeholder="Exclude post types" size="large" v-model="data.settings.protections.rc_exclude_posts" @change="changeSetting($event)">
										<el-option v-for="(item, key) in data.postTypes" :key="key" :label="item.label" :value="key" />
									</el-select>
								</SettingItem>
								<SettingItem :title="__('Disable right click for images', 'accesswise')">
									<el-checkbox class="setting-input" v-model="data.settings.protections.rc_disable_images" @change="changeSetting($event)" :label="__('Yes, disable right click for images', 'accesswise')" value="true" />
								</SettingItem>
								<SettingItem :title="__('Disable right click for links', 'accesswise')">
									<el-checkbox class="setting-input" v-model="data.settings.protections.rc_disable_links" @change="changeSetting($event)" :label="__('Yes, disable right click for links', 'accesswise')" value="true" />
								</SettingItem>
								<SettingItem :title="__('Disable Developer Tools Hot-keys', 'accesswise')">
									<el-checkbox class="setting-input" v-model="data.settings.protections.rc_disable_dev_keys" @change="changeSetting($event)" :label="__('Yes, disable Developer Tools Hot-keys', 'accesswise')" value="true" />
								</SettingItem>
								<SettingItem :title="__('Disable Drag & Drop', 'accesswise')">
									<el-checkbox class="setting-input" v-model="data.settings.protections.rc_disable_drag_drop" @change="changeSetting($event)" :label="__('Yes, disable Drag & Drop', 'accesswise')" value="true" />
								</SettingItem>
								<SettingItem :title="__('Disable Individual Keys:', 'accesswise')">
									<el-checkbox-group class="setting-input" v-model="data.settings.protections.rc_disable_keys" @change="changeSetting($event)">
										<el-checkbox :label="__('F12 Key', 'accesswise')" value="disable_f12" />
										<el-checkbox :label="__('CTRL-C/CMD-C', 'accesswise')" value="disable_ctrl_c" />
										<el-checkbox :label="__('CTRL-V/CMD-V', 'accesswise')" value="disable_ctrl_v" />
										<el-checkbox :label="__('CTRL-S/CMD-S', 'accesswise')" value="disable_ctrl_s" />
										<el-checkbox :label="__('CTRL-A/CMD-A', 'accesswise')" value="disable_ctrl_a" />
										<el-checkbox :label="__('CTRL-X/CMD-X', 'accesswise')" value="disable_ctrl_x" />
										<el-checkbox :label="__('CTRL-U/CMD-U', 'accesswise')" value="disable_ctrl_u" />
										<el-checkbox :label="__('CTRL-P/CMD-P', 'accesswise')" value="disable_ctrl_p" />
										<el-checkbox :label="__('CTRL-H/CMD-H', 'accesswise')" value="disable_ctrl_h" />
										<el-checkbox :label="__('CTRL-L/CMD-L', 'accesswise')" value="disable_ctrl_l" />
										<el-checkbox :label="__('CTRL-K/CMD-K', 'accesswise')" value="disable_ctrl_k" />
										<el-checkbox :label="__('CTRL-O/CMD-O', 'accesswise')" value="disable_ctrl_o" />
										<el-checkbox :label="__('F6 Key', 'accesswise')" value="disable_f6" />
										<el-checkbox :label="__('F3 Key', 'accesswise')" value="disable_f3" />
										<el-checkbox :label="__('F9 Key', 'accesswise')" value="disable_f9" />
										<el-checkbox :label="__('ALT-D', 'accesswise')" value="disable_alt_d" />
										<el-checkbox :label="__('CTRL-E/CMD-E', 'accesswise')" value="disable_ctrl_e" />
									</el-checkbox-group>
								</SettingItem>
								<SettingItem :title="__('Disable left click ( not recommended )', 'accesswise')">
									<el-checkbox class="setting-input" v-model="data.settings.protections.rc_disable_left_click" @change="changeSetting($event)" :label="__('Yes, disable left click', 'accesswise')" value="true" />
								</SettingItem>
								<SettingItem :title="__('Disable scrolling over images (Mobile)', 'accesswise')">
									<el-checkbox class="setting-input" v-model="data.settings.protections.rc_disable_scroll_img_mobile" @change="changeSetting($event)" :label="__('Yes, disable scrolling over images (Mobile)', 'accesswise')" value="true" />
								</SettingItem>
								<SettingItem :title="__('Disable Right Click Message:', 'accesswise')">
									<el-input v-model="data.settings.protections.rc_disable_msg" @input="saveWrittenMessage" size="large" :placeholder="__('Disable Right Click Message', 'accesswise')"></el-input>
								</SettingItem>
								<SettingItem :title="__('Protect content when Javascript is disabled', 'accesswise')">
									<el-checkbox class="setting-input" v-model="data.settings.protections.rc_protect_no_js" @change="changeSetting($event)" :label="__('Yes, protect content when Javascript is disabled', 'accesswise')" value="true" />
								</SettingItem>
								<SettingItem v-if="data.settings.protections.rc_protect_no_js" :title="__('Message while Javascript is disabled', 'accesswise')">
									<el-input v-model="data.settings.protections.rc_no_js_msg" @input="saveWrittenMessage" size="large" :placeholder="__('Message while Javascript is disabled', 'accesswise')"></el-input>
								</SettingItem>
								<SettingItem :title="__('Exclude User Roles:', 'accesswise')">
									<el-select multiple class="m-2" placeholder="Exclude user roles" size="large" v-model="data.settings.protections.rc_exclude_roles" @change="changeSetting($event)">
										<el-option v-for="(item, key) in data.userRoles" :key="key" :label="item" :value="key" />
									</el-select>
								</SettingItem>
								<SettingItem :title="__('Protect only specified Individual Posts:', 'accesswise')">
									<el-select multiple class="m-2" placeholder="Protect only specified individual posts" size="large" v-model="data.settings.protections.rc_protect_individual_posts" @change="changeSetting($event)">
										<el-option v-for="(item, key) in data.postTypes" :key="key" :label="item.label" :value="key" />
									</el-select>
								</SettingItem>
							</template>
						</div>
					</el-tab-pane>
				</el-tabs>
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

			.setting-input {
				&.el-checkbox-group {
					display: grid;
					grid-template-columns: repeat(4, 1fr);
					row-gap: 15px;
				}
			}
		}
	}
}
</style>