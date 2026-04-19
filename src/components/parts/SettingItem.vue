<script setup>
const props = defineProps( {
	title: String,
	description: String,
	isInline: Boolean,
} );
</script>

<template>
	<div class="setting-wrap" :class="{ 'is-inline': isInline }">
		<div class="setting-title">
			<h3 v-if="title">{{ title }}</h3>
			<p v-if="description">{{ description }}</p>
		</div>
		<div class="setting-input">
			<slot></slot>
		</div>
	</div>
</template>

<style lang="scss" scoped>
.setting-wrap {
	@include flex(column, flex-start, flex-start);
	width: 100%;
	gap: 10px;

	&.is-inline {
		@include flex(row, space-between, center);
	}

	.setting-title {
		@include flex(column, flex-start, flex-start);
		gap: 10px;
	}

	&:not(.is-inline) .setting-input {
		width: 100%;
	}
}
</style>