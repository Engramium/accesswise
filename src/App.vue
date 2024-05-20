<script setup>
import { data, fn, icons } from "./utils/data";
import Header from "./components/parts/Header.vue";
import Footer from "./components/parts/Footer.vue";

const getSettings = () => {
  const loading = ElLoading.service({
    fullscreen: true,
    lock: true,
    text: "Loading",
    background: "rgba(0, 0, 0, 0.7)",
  });

  const res = fn.fetchAdminAjax(accesswise.admin_ajax, "get", {
    action: "accesswise_get_settings",
    nonce: accesswise.nonce,
  });

  res.then((response) => {
    if (response.status) {
      data.settings = response.data;
    }
    loading.close();
  });
};

getSettings();

const getWpPages = () => {
  const res = fn.fetchPublicUrl(accesswise.rest_url + "wp/v2/pages", 'get');
  res.then(response => {
    let formatPages = { default: 'Default'};
    response.forEach(element => {
      formatPages[element.slug] = element.title.rendered
    });
    data.pages = formatPages;
  })
};

getWpPages();
</script>

<template>
  <div class="accesswise-layout" :class="data.currentTab">
    <Header></Header>
    <div class="accesswise-content">
      <div class="content">
        <router-view></router-view>
      </div>
    </div>
    <Footer></Footer>
  </div>
</template>

<style scoped lang="scss">
@import "./scss/_variables";
@import "./scss/_mixins";

.accesswise-layout {
  @include flex(column, space-between, center);
  height: 100%;
  gap: 60px;
}

.accesswise-header,
.accesswise-footer,
.accesswise-content {
  width: 100%;
}

.accesswise-content {
  .content {
    padding: 0 20px;
  }
}
</style>