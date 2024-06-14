<script setup>
import { ref, inject, watch } from "vue";
import { data, fn, icons } from "../utils/data";
import SettingItem from "./parts/SettingItem.vue";

const getSettingsFields = () => {
  return {
    generals: {
      toolbar: {
        input: 'checkbox',
        title: wp.i18n.__("Toolbar", "accesswise"),
        helpUrl: "#",
        helpText: wp.i18n.__("The admin Toolbar is a horizontal black bar at the top of the screen.", "accesswise"),
        options: {
          hide_from_admins: wp.i18n.__("Hide the Toolbar from admins", "accesswise"),
          hide_from_non_admins: wp.i18n.__("Hide the Toolbar from members (non-admins)", "accesswise"),
          hide_from_logged_out_users: wp.i18n.__("Hide the Toolbar from logged-out users", "accesswise"),
        }
      },
      redirection_after_login: {
        input: 'select',
        title: wp.i18n.__("Redirection (After Login)", "accesswise"),
        helpUrl: "#",
        helpText: wp.i18n.__("Forward to your preferred page or post type, depending on the user's logged-in state.", "accesswise"),
        options: data.pages,
      },
      redirection_after_logout: {
        input: 'select',
        title: wp.i18n.__("Redirection (After Logout)", "accesswise"),
        helpUrl: "#",
        helpText: wp.i18n.__("Forward to your preferred page or post type, depending on the user's logged-out state.", "accesswise"),
        options: data.pages,
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
          show_last_login: wp.i18n.__("Show Last login in users", "accesswise")
        },
      },
      right_click: {
        input: 'checkbox',
        title: wp.i18n.__("Copy Protection & Disable Right Click", "accesswise"),
        helpUrl: "#",
        helpText: wp.i18n.__("Regardless of this setting, this will not be impact for the Administrators.", "accesswise"),
        options: {
          disable_right_click: wp.i18n.__("Disable Right Click", "accesswise"),
          disable_copy: wp.i18n.__("Disable Copy", "accesswise"),
        },
      },
    },
  };
};

const updateSetting = (content) => {
  const res = fn.fetchAdminAjax(accesswise.admin_ajax, "post", {
    action: "accesswise_update_settings",
    generals: data.settings.generals,
    nonce: accesswise.nonce,
  });

  let status = "";
  if ((content.status == false || content.status == true) && typeof content.status == "boolean") {
    status = content.status ? wp.i18n.__('Enabled', 'accesswise') : wp.i18n.__('Disabled', 'accesswise');
  } else {
    if (content.currentClick == null) {
      status = content.feature.options[ content.status ];
    } else {
      if(content.status.includes(content.currentClick)) {
        status = `${wp.i18n.__('Enabled:', 'accesswise')} ${content.feature.options[ content.currentClick ]}`;
      } else {
        status = `${wp.i18n.__('Disabled:', 'accesswise')} ${content.feature.options[ content.currentClick ]}`;
      }
    }
  }

  let msg = `${content.feature.title}: ${status}`;

  res.then((response) => {
    if (response.status) {
      ElNotification({
        title: wp.i18n.__('Success', 'accesswise'),
        message: msg,
        type: "success",
        offset: 50,
      });
    } else {
      ElNotification({
        title: wp.i18n.__('Error', 'accesswise'),
        message: response.msg,
        type: "error",
        offset: 50,
      });
    }
  });
};
</script>
<template>
  <div v-if="data.settings != null && data.pages != null" class="settings-wrap">
    <div class="feature-wrap">
      <div class="feature-content">
        <div class="grid">
          <div v-for="(general, index) in getSettingsFields().generals" :key="index" class="grid-item">
            <SettingItem @update-setting="updateSetting" :content="general" v-model="data.settings.generals[index]" />
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