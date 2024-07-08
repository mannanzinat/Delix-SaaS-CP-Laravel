<script setup>
import { ref, computed, watch } from "vue";
import { Handle, Position } from "@vue-flow/core";

// Importing Store Pinia
import { useStore } from "../stores/main.js";

// custom Top Menu import
import topMenu from "./topMenu.vue";
import globalValue from "../mixins/helper.js";
const getMixinValue = globalValue();
// Local variables and props declaration
const transparent = ref(true);
let selectedColor = ref(false);
const props = defineProps(['id','selected', 'current_id', 'match_type', 'condition_fields']);
////////////////////////////////////////////.

// Usage of Store Pinia
const store = useStore();

// Computed Values from Store.
let localStates = computed(() => {
	return store.getMessageById(props.id);
});

// Watching Selected Manual event
watch(
	() => props.selected,
	(isSelected) => (selectedColor.value = isSelected)
);
watch(
	() => props.match_type || props.condition_fields,
	() => {
		if (props.id === props.current_id) {
			localStates.value.match_type = props.match_type;
			localStates.value.condition_fields = props.condition_fields;
		}
	}
);
const emit = defineEmits(["data-sent"]);
function handleData() {
	emit("data-sent", { args: localStates });
}
////////////////////////////////////////////.
</script>

<template>
	<!-- Handle for different utilities -->
	<Handle id="right" class="handle" type="source" :position="Position.Right" style="top: 60%" />
	<Handle id="false" class="handle" type="source" :position="Position.Right" style="top: 75%" />
	<Handle id="left" class="handle" type="target" :position="Position.Left" />
	<!--  <Handle
      id="bottom"
      class="handle"
      type="source"
      :position="Position.Bottom"
      style="top: 100%;right: 150%"
    />-->
	<!-- Handle for different utilities -->

	<div @mouseenter="transparent = false" @mouseleave="transparent = true" class="d-flex flex-column align-items-center">
		<!-- Delete Button and color controls -->
		<topMenu :eid="props.id" :transparent="transparent"></topMenu>
		<!-- Delete Button and color controls -->

		<div data-bs-toggle="offcanvas"
         data-bs-target="#offcanvasRight"
         @click="handleData"
			class="main-container"
			:style="{
				border: selectedColor ? '3px red solid' : `3px ${localStates.color} solid`,
			}"
		>
			<div class="content">
				<div class="card" style="width: 18rem; text-align: center;">
					<div class="card-body" style="padding-left: 10px; padding-bottom: 0">
						<p class="card-title">{{ getMixinValue.lang.condition }}</p>
						<div style="text-align: right; margin-top: 20px">
							<p style="margin: 0">{{ getMixinValue.lang.true }}</p>
							<p>{{ getMixinValue.lang.false }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
