d
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
const props = defineProps({
	id: String,
	current_id: String,
	selected: Boolean,
	text: String,
});
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
	() => props.text,
	() => {
		if (props.id === props.current_id) {
			localStates.value.text = props.text;
		}
	}
);
const emit = defineEmits(["data-sent"]);

function handleData() {
	emit("data-sent", { args: localStates });
}
</script>

<template>
	<Handle id="right" class="handle" type="source" :position="Position.Right" />

	<div @mouseenter="transparent = false" @mouseleave="transparent = true" class="d-flex flex-column align-items-center">
		<!-- Delete Button and color controls -->
		<!-- <topMenu :eid="props.id" :transparent="transparent"></topMenu> -->
		<!-- Delete Button and color controls -->

		<div
			@click="handleData"
			data-bs-toggle="offcanvas"
			data-bs-target="#offcanvasRight"
			class="main-container"
			:style="{
				border: selectedColor ? '3px red solid' : `3px ${localStates.color} solid`,
			}"
		>
			<div class="content">
				<div class="card" style="width: 18rem; text-align: center">
					<div class="card-body">
						<p class="card-title">{{ getMixinValue.lang.start_bot_flow }}</p>
						<div>
							<span class="desc">Hello Title</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
