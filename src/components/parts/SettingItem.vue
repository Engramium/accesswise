<script setup>
const props = defineProps( {
	title: String,
	description: String,
	isInline: Boolean,
} );
</script>

<template>
	<div class="setting-wrap" :class="{ 'is-inline': isInline }">
		<div class="content">
			<h3 class="title" v-if="title">{{ title }}</h3>
			<p class="description" v-if="description">{{ description }}</p>
		</div>
		<div class="input-wrap">
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

	.content {
		@include flex(column, flex-start, flex-start);
		gap: 10px;

		.title {
			font-size: 15px;
			font-weight: 600;
		}

		.description {
			font-size: 14px;
			font-weight: 400;
		}
	}

	&:not(.is-inline) .input-wrap {
		width: 100%;
	}
}
</style>