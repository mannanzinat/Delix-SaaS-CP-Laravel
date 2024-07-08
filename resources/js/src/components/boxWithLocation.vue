<script setup>
import { ref, computed, watch } from "vue";
import { Handle, Position } from "@vue-flow/core";

// Importing Store Pinia
import { useStore } from "../stores/main.js";

// custom Top Menu import
import topMenu from "./topMenu.vue";
import globalValue from "../mixins/helper.js";
const getMixinValue = globalValue();
// Local variables and props declaration.
const transparent = ref(true);
let selectedColor = ref(false);
const props = defineProps(["id", "selected", "latitude", "longitude", "current_id", "duration"]);
////////////////////////////////////////////.

// Usage of Store Pinia
const store = useStore();

// Computed Values from Store
let localStates = computed(() => {
	return store.getMessageById(props.id);
});

watch(
	() => props.selected,
	(isSelected) => (selectedColor.value = isSelected)
);

watch(
	() => props.latitude,
	() => {
		if (props.id === props.current_id) {
			localStates.value.latitude = props.latitude;
		}
	}
);
watch(
	() => props.longitude,
	() => {
		if (props.id === props.current_id) {
			localStates.value.longitude = props.longitude;
			localStates.value.longitude = props.longitude;
			localStates.value.location_duration = props.duration;
		}
	}
);
const emit = defineEmits(["data-sent"]);
function handleData() {
	emit("data-sent", { args: localStates });
}
</script>
<template>
	<!-- Handle for different utilities -->
	<Handle id="right" class="handle" type="source" :position="Position.Right" />
	<Handle id="left" class="handle" type="target" :position="Position.Left" />

	<div @mouseenter="transparent = false" @mouseleave="transparent = true" class="d-flex flex-column align-items-center">
		<!-- Delete Button and color controls -->
		<topMenu :eid="props.id" :transparent="transparent"></topMenu>
		<!-- Delete Button and color controls -->

		<div
			data-bs-toggle="offcanvas"
			data-bs-target="#offcanvasRight"
			@click="handleData"
			class="main-container"
			:style="{
				border: selectedColor ? '3px red solid' : `3px ${localStates.color} solid`,
			}"
		>
			<div class="content">
				<div class="card" style="width: 18rem; text-align: center">
					<div class="card-body">
						<p class="card-title">{{ getMixinValue.lang.location }}</p>
						<div class="mt-2">
							<a href="https://maps.google.com/maps?q=10.305385,77.923029&hl=es;z=14&amp;output=embed" class="btn sg-btn-primary triger_btn"
								><i class="las la-map-marked-alt"></i> Maps</a
							>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
