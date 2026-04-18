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


.setting-layout {
	@include flex(row, flex-start, stretch);
	gap: 20px;
}

.navigation-wrap {
	width: 300px;
	background: #fff;
	padding: 20px;
	border-radius: 5px;
}

.content-wrap {
	flex: 1;
	background: #fff;
	padding: 30px;
	border-radius: 5px;
}
</style>
