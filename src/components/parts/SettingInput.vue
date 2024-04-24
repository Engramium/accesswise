<script setup>
import { ref } from "vue";
import { data, fn, icons } from "../../utils/data";
import { QuestionFilled } from "@element-plus/icons-vue";

const props = defineProps([ "modelValue", "content" ]);
const emits = defineEmits([ "update:modelValue", "updateSetting" ]);

const changeSetting = (event, content) => {
  emits("update:modelValue", event);
  emits("updateSetting", { feature: content, status: event });
};
</script>

<template>
  <el-checkbox-group class="setting-input" v-if="content.type == 'checkbox'" :model-value="modelValue"
    @change="changeSetting($event, content)">
    <div v-for="(item, key) in content.options" :key="key">
      <el-checkbox :label="item" :value="key" />
    </div>
  </el-checkbox-group>

  <el-select v-if="content.type == 'select'" size="large" style="width: 240px" :model-value="modelValue"
    @change="changeSetting($event, content)">
    <el-option v-for="(item, key) in content.options" :key="key" :label="item" :value="key" />
  </el-select>

  <el-input v-if="content.type == 'text'" placeholder="Full url of custom link" size="large" style="width: 240px"
    :model-value="modelValue" @change="changeSetting($event, content)" />
</template>

<style lang="scss" scoped>
// @import "../../scss/_variables";
// @import "../../scss/_mixins";

// .setting-wrap {
//   @include flex(row, flex-start, flex-start);
//   width: 100%;
//   gap: 30px;

//   .setting-title {
//     @include flex(row, flex-start);
//     gap: 10px;
//     width: 30%;

//     .help-icon {
//       @include flex();
//     }
//   }

//   .setting-input {
//     width: 70%;
//   }
// }

// .link-item {
//   margin: 10px 20px;
// }</style>