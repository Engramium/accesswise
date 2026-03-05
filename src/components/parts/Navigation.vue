<script setup>
import { watch } from "vue";
import { useRoute } from "vue-router";
import { data, fn, icons } from "../../utils/data";
import menuFix from '../../utils/admin-menu-fix.js';

const route = useRoute();
data.currentRouteName = route.name;
watch( route, () => {
	data.currentRouteName = route.name;
	menuFix( 'accesswise-settings' );
} );

</script>
<template>
	<div class="navigation">
		<router-link
			to="/settings/general"
			:class="data.currentRouteName == 'settings-general' ? 'menu-item active' : 'menu-item'
				"
			@click="data.currentRouteName = 'settings-general'">
			<span v-html="icons.general"></span>
			{{ __("General", "accesswise") }}
		</router-link>
		<router-link
			to="/settings/protection"
			:class="data.currentRouteName == 'settings-protection' ? 'menu-item active' : 'menu-item'
				"
			@click="data.currentRouteName = 'settings-protection'">
			<span v-html="icons.protection"></span>
			{{ __("Protection", "accesswise") }}</router-link>
		<router-link
			to="/settings/restriction"
			:class="data.currentRouteName == 'settings-restriction' ? 'menu-item active' : 'menu-item'
				"
			@click="data.currentRouteName = 'settings-restriction'">
			<span v-html="icons.protection"></span>
			{{ __("Restriction", "accesswise") }}
		</router-link>
	</div>
</template>
<style scoped lang='scss'>
@import "../../scss/_variables";
@import "../../scss/_mixins";

.navigation {
	@include flex(column, flex-start, center);
	gap: 20px;
	background: #fff;

	.menu-item {
		@include flex(row, flex-start, center);
		gap: 17px;
		padding: 15px 20px;
		cursor: pointer;
		text-decoration: none;
		color: gray;
		font-size: 16px;
		line-height: 18px;
		width: 85%;
		background: #fff;
		color: #000;
		border-radius: 5px;

		&:hover {
			color: #fff;
			background: #004685;
		}

		&.active {
			color: #fff;
			background: #004685;
		}
	}
}
</style>