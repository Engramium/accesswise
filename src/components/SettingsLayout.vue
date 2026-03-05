<script setup>
import Navigation from "./parts/Navigation.vue";
</script>
<template>
	<div class="setting-layout">
		<div class="navigation-wrap">
			<Navigation />
		</div>
		<div class="content-wrap">
			<router-view></router-view>
		</div>
	</div>
</template>
<style scoped lang='scss'>
@import "../scss/_variables";
@import "../scss/_mixins";

.setting-layout {
	@include flex(row, space-between, flex-start);
	gap: 20px;
}

.navigation-wrap {
	width: 300px;
	background: #fff;
	padding: 20px;
	border-radius: 5px;
	box-shadow: $box_shadow;
}

.content-wrap {
	// width: 100%;
}
</style>
