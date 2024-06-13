<script setup>
import { ref } from "vue";
import { data, fn, icons } from "../../utils/data";
import { QuestionFilled } from "@element-plus/icons-vue";

const props = defineProps(["modelValue", "content"]);
const emits = defineEmits(["update:modelValue", "updateSetting"]);

const currentClick = ref(null);

const changeSetting = (event, content) => {
  emits("update:modelValue", event);
  emits("updateSetting", { feature: content, status: event, currentClick: currentClick.value });
  currentClick.value = null;
};
</script>

<template>
  <div class="setting-wrap">
    <div class="setting-title">
      <el-text tag="p" size="large">{{ content.title }}</el-text>
      <div class="help-icon">
        <el-popover
          placement="bottom"
          :width="200"
          trigger="hover"
          effect="dark"
          :content="content.helpText"
        >
          <template #reference>
            <a
              v-if="content.helpUrl != '#'"
              class="help-icon"
              :href="content.helpUrl"
              target="_blank"
            >
              <el-icon class="pointer" :size="20" color="#777777">
                <QuestionFilled />
              </el-icon>
            </a>
            <el-icon v-else class="pointer" :size="20" color="#777777">
              <QuestionFilled />
            </el-icon>
          </template>
        </el-popover>
      </div>
    </div>

    <!-- <el-switch
      class="setting-input"
      v-if="!content.hasOwnProperty('options')"
      :model-value="modelValue"
      size="large"
      @change="changeSetting($event, content)"
    /> -->

    <!-- <el-checkbox
      class="setting-input"
      v-if="!content.hasOwnProperty('options')"
      :model-value="modelValue"
      size="large"
      @change="changeSetting($event, content)"
      label="Option A"
      value="Value A"
    /> -->

    <el-checkbox-group
      class="setting-input"
      v-if="content.input == 'checkbox'"
      :model-value="modelValue"
      @change="changeSetting($event, content)"
    >
      <div v-for="(item, key) in content.options" :key="key">
        <el-checkbox @click="currentClick=key" :label="item" :value="key" />
      </div>
    </el-checkbox-group>

    <el-select
      v-if="content.input == 'select'"
      class="m-2"
      placeholder="Select"
      size="large"
      style="width: 240px"
      :model-value="modelValue"
      @change="changeSetting($event, content)"
    >
      <el-option
        v-for="(item, key) in content.options"
        :key="key"
        :label="item"
        :value="key"
      />
    </el-select>
  </div>
</template>

<style lang="scss" scoped>
@import "../../scss/_variables";
@import "../../scss/_mixins";
.setting-wrap {
  @include flex(row, flex-start, flex-start);
  width: 100%;
  gap: 30px;

  .setting-title {
    @include flex(row, flex-start);
    gap: 10px;
    width: 30%;

    .help-icon {
      @include flex();
    }
  }

  .setting-input {
    width: 70%;
  }
}
.link-item {
  margin: 10px 20px;
}
</style>