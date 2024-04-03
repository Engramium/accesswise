<script setup>
import { ref, inject } from "vue";
import { data, fn, icons } from "../utils/data";
import SettingItem from "./parts/SettingItem.vue";

const settings = {
  generals: {
    toolbar: {
      input: 'checkbox',
      title: wp.i18n.__("Toolbar", "accesswise"),
      helpUrl: "#",
      helpText: wp.i18n.__("The admin Toolbar is a horizontal black bar at the top of the screen.", "accesswise"),
      options: {
        logged_in_admins: wp.i18n.__("Show the Toolbar for logged-in admins", "accesswise"),
        logged_in_members: wp.i18n.__("Show the Toolbar for logged-in members (non-admins)", "accesswise"),
        logged_out_users: wp.i18n.__("Show the Toolbar for logged-out users", "accesswise"),
      }
    },
    redirection_after_login: {
      input: 'select',
      title: wp.i18n.__("Redirection (After Login)", "accesswise"),
      helpUrl: "#",
      helpText: wp.i18n.__("Forward to your preferred page or post type, depending on the user's logged-in state.", "accesswise"),
      options: {
        default: wp.i18n.__("Default", "accesswise"),
        custom: wp.i18n.__("Custom", "accesswise"),
        page_1: wp.i18n.__("Page 1", "accesswise"),
      },
    },
    redirection_after_logout: {
      input: 'select',
      title: wp.i18n.__("Redirection (After Logout)", "accesswise"),
      helpUrl: "#",
      helpText: wp.i18n.__("Forward to your preferred page or post type, depending on the user's logged-out state.", "accesswise"),
      options: {
        default: wp.i18n.__("Default", "accesswise"),
        custom: wp.i18n.__("Custom", "accesswise"),
        page_1: wp.i18n.__("Page 1", "accesswise"),
      },
    },
    private_website: {
      input: 'checkbox',
      title: wp.i18n.__("Private Website", "accesswise"),
      helpUrl: "#",
      helpText: wp.i18n.__("Login and Registration content will remain publicly visible.", "accesswise"),
      options: {
        logged_in_users: wp.i18n.__("Restrict site access to only logged-in members", "accesswise"),
      },
    },
    when_last_login: {
      input: 'checkbox',
      title: wp.i18n.__("When Last Login", "accesswise"),
      helpUrl: "#",
      helpText: wp.i18n.__("When Last Login", "accesswise"),
      options: {
        enable: wp.i18n.__("Enable", "accesswise")
      },
    },
    right_click: {
      input: 'checkbox',
      title: wp.i18n.__("Copy Protection & Disable Right Click", "accesswise"),
      helpUrl: "#",
      helpText: wp.i18n.__("Regardless of this setting, this will not be impact for the Administrators.", "accesswise"),
      options: {
        enable: wp.i18n.__("Enabled", "accesswise"),
        disable: wp.i18n.__("Disable", "accesswise"),
      },
    },
  },
};

const updateSetting = (content) => {
  console.log(data.settings.generals);
  // return;

  const res = fn.fetchAdminAjax(accesswise.admin_ajax, "post", {
    action: "accesswise_update_settings",
    generals: data.settings.generals,
    nonce: accesswise.nonce,
  });

  let status = "";
  if (content.status == false || content.status == true) {
    status = content.status ? "Enabled" : "Disabled";
  } else {
    status = content.feature.options[content.status];
  }

  let msg = `${content.feature.title}: ${status}`;

  res.then((response) => {
    if (response.status) {
      ElNotification({
        title: "Success",
        message: msg,
        type: "success",
        offset: 50,
      });
    } else {
      ElNotification({
        title: "Error",
        message: response.msg,
        type: "error",
        offset: 50,
      });
    }
  });
};
</script>
<template>
  <div v-if="data.settings != null" class="settings-wrap">
    <div class="feature-wrap">
      <div class="feature-content">
        <div class="grid">
          <div
            v-for="(general, index) in settings.generals"
            :key="index"
            class="grid-item"
          >
            <SettingItem
              @update-setting="updateSetting"
              :content="general"
              v-model="data.settings.generals[index]"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped lang="scss">
@import "../scss/_variables";
@import "../scss/_mixins";
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
    .grid {
      .grid-item {
        @include card();
        padding: 20px;
        margin: 30px 0;
        max-width: 1124px;
      }
    }
  }
}
</style>