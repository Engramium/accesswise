<script setup>
import { ref } from "vue";
import { data, fn, icons } from "../../utils/data";
import { QuestionFilled } from "@element-plus/icons-vue";
import SettingInput from "./SettingInput.vue";

const props = defineProps([ "modelValue", "content" ]);
const emits = defineEmits([ "update:modelValue", "updateSetting" ]);

const updateSetting = (content) => {
  emits("updateSetting", content);
};
</script>

<template>
  <div class="setting-wrap">
    <div class="setting-title">
      <el-text tag="p" size="large">{{ content.title }}</el-text>
      <div class="help-icon">
        <el-popover placement="bottom" :width="200" trigger="hover" effect="dark" :content="content.helpText">
          <template #reference>
            <a v-if="content.helpUrl != '#'" class="help-icon" :href="content.helpUrl" target="_blank">
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

    <div class="section-input-wrap">
      <SettingInput v-for="(input, key) in content.inputs" :key="key" @updateSetting="updateSetting" :content="input"
        v-model="modelValue[ key ]" />
    </div>
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

  .section-input-wrap {
    width: 70%;
    @include flex(column, flex-start, flex-start);
    gap: 30px;
  }
}

.link-item {
  margin: 10px 20px;
}
</style>