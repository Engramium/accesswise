<script setup>
import { ref } from "vue";
import { data, fn, icons } from "../../utils/data";
import { QuestionFilled } from "@element-plus/icons-vue";

const props = defineProps( [ "title", "helpText", "helpURL" ] );

</script>

<template>
	<div class="setting-wrap">
		<div class="setting-title">
			<el-text tag="p" size="large">{{ title }}</el-text>
			<div class="help-icon">
				<el-popover placement="bottom" :width="200" trigger="hover" effect="dark" :content="helpText">
					<template #reference>
						<a v-if="helpURL != '#'" class="help-icon" :href="helpURL" target="_blank">
							<el-icon class="pointer" :size="20" color="#777777">
								<QuestionFilled />
							</el-icon>
						</a>
						<el-icon v-else class="pointer" :size="20" color="#777777">
							<QuestionFilled />
						</el-icon>
					</template>
				</el-popover>
			</div>
		</div>
		<slot></slot>
	</div>
</template>

<style lang="scss" scoped>
@import "../../scss/_variables";
@import "../../scss/_mixins";

.setting-wrap {
	@include flex(row, flex-start, flex-start);
	width: 100%;
	gap: 30px;

	.setting-title {
		@include flex(row, flex-start);
		gap: 10px;
		width: 30%;

		.help-icon {
			@include flex();
		}
	}

	.setting-input {
		width: 70%;
	}
}

.link-item {
	margin: 10px 20px;
}
</style>