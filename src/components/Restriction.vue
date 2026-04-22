<script setup>
import { data, fn } from "../utils/data";
import SettingItem from "./parts/SettingItem.vue";

const { updateSetting, saveWrittenMessage } = fn.useSettingsUpdater( data );
const changeSetting = updateSetting;
</script>
<template>
	<div v-if="data.settings != null && data.pages != null" class="settings-wrap">
		<div class="feature-wrap">
			<div class="feature-content">
				<h2 class="title">{{ __('Restriction', 'accesswise') }}</h2>
				<p class="description">{{ __('Limit site access to authenticated members while keeping selected URLs publicly reachable.', 'accesswise') }}</p>
				<div class="items-wrap">
					<SettingItem :title="__('Private Website', 'accesswise')">
						<el-checkbox-group class="setting-input" v-model="data.settings.restrictions.private_website" @change="changeSetting($event)">
							<div>
								<el-checkbox :label="__('Restrict site access to only logged-in members', 'accesswise')" value="logged_in_users" />
							</div>
						</el-checkbox-group>
						<div v-if="data.settings.restrictions.private_website?.includes('logged_in_users')" class="setting-input">
							<el-input v-model="data.settings.restrictions.public_website_contents" @input="saveWrittenMessage" :autosize="{ minRows: 4, maxRows: 10 }" type="textarea" placeholder="e.g. /groups/" />
							<label>{{ __('Enter URLs or URI fragments (e.g. /groups/) to remain publicly visible always. Enter one URL or URI per line.', 'accesswise') }}</label>
						</div>
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
