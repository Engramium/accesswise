<script setup>
import { watch } from "vue";
import { useRoute } from "vue-router";
import { data, fn, icons } from "../../utils/data";
import Help from "./Help.vue";
import menuFix from '../../utils/admin-menu-fix.js'

const route = useRoute();
data.currentRouteName = route.name;
watch(route, () => {
  data.currentRouteName = route.name;
  menuFix('accesswise-settings');
});

</script>
<template>
  <div class="accesswise-header">
    <div class="menu-bar">
      <div class="logo-wrap">
        <img
          :src="accesswise.plugin_url + 'public/logo/main-logo.svg'"
          alt="Accesswise Main Logo"
        />
        <el-tag :type="'danger'" effect="plain" round
          >v{{ accesswise.plugin_version }}</el-tag
        >
      </div>
      <div class="menu-wrap">
        <div class="tabs">
          <router-link
            to="/"
            :class="data.currentRouteName == 'welcome' ? 'menu-item active' : 'menu-item'"
            @click="data.currentRouteName = 'welcome'"
            >{{ __("Welcome", "accesswise") }}</router-link
          >
          <router-link
            to="/settings"
            :class="
              data.currentRouteName == 'settings' ? 'menu-item active' : 'menu-item'
            "
            @click="data.currentRouteName = 'settings'"
            >{{ __("Settings", "accesswise") }}</router-link
          >
        </div>
        <div class="actions">
          <!-- <el-link type="primary">{{ __("Upgrade", "accesswise") }}</el-link> -->
          <Help />
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped lang='scss'>
@import "../../scss/_variables";
@import "../../scss/_mixins";

.accesswise-header {
  .menu-bar {
    background-color: #ffffff;
    @include flex();
    padding: 0 30px;
    gap: 30px;
    box-shadow: $box_shadow;

    .logo-wrap {
      @include flex(row, flex-start);
      width: 20%;
      gap: 15px;

      img {
        width: 120px !important;
        height: 80px;
      }

      p {
        background-color: #f4e1de;
        border-radius: 25px;
        border: 1px solid #d2ada7;
        padding: 5px 20px;
        font-weight: bold;
      }
    }

    .menu-wrap {
      @include flex(row, space-between);
      width: 80%;

      .tabs {
        @include flex();
        gap: 30px;

        .menu-item {
          padding: 30px 0;
          cursor: pointer;
          text-decoration: none;
          color: gray;
          font-size: 16px;
          line-height: 18px;
          font-weight: 400;
          box-shadow: none;

          border-bottom: 2px solid transparent;

          &:hover {
            border-bottom: 2px solid gray;
          }

          &.active {
            color: #F56C6C;
            border-bottom: 2px solid #F56C6C;
          }
        }
      }

      .actions {
        @include flex();
        gap: 30px;
      }
    }
  }
}
</style>