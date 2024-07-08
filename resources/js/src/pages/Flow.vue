<script setup>
import { onMounted, reactive, ref } from "vue";
import { VueFlow, useVueFlow } from "@vue-flow/core";

import { Background } from "@vue-flow/background";
import { Controls } from "@vue-flow/controls";
import { MiniMap } from "@vue-flow/minimap";

// Initials Elements
import { initialElements } from "../assets/initial-elements";

import container from "../components/container.vue";
import VueMultiselect from "vue-multiselect";
// Manychat Copy Components.
import boxWithTitleVue from "../components/boxWithTitle.vue";
import boxWithStarter from "../components/boxWithStarter.vue";
import imageContainerVue from "../components/imageContainer.vue";
import boxWitAudioVue from "../components/boxWithAudio.vue";
import boxWitVideoVue from "../components/boxWithVideo.vue";
import boxWithFileVue from "../components/boxWithFile.vue";
import boxTemplate from "../components/boxTemplate.vue";
import boxLocation from "../components/boxWithLocation.vue";
import boxCondition from "../components/boxWithCondition.vue";
import boxWithInteractive from "../components/boxWithInteractive.vue";


import globalMenuVue from "../components/globalMenu.vue";

import redirectorEdgeVue from "../components/redirectorEdge.vue";

// Custom Connection line and Custom Edge
import CustomConnectionLine from "../components/CustomConnectionLine.vue";
import customEdgeVue from "../components/customEdge.vue";
////////////////////////////////////////////.

// Externalise node creation process on Drop here
import { createVueNode } from "../utils/createVueNode";
////////////////////////////////////////////.

// Usage of Store Pinia
import { useStore } from "../stores/main.js";
import globalValue from "../mixins/helper.js";

const getMixinValue = globalValue();

const store = useStore();

const { addEdges, addNodes, onConnect, onPaneReady, project, setInteractive } = useVueFlow();

// Initialize elements values here.
// onMounted(() => {
//   elements.value = []
// })

// Methods that helps, centering the vue.
onPaneReady(({ fitView }) => {
	fitView();
});
////////////////////////////////////////////.

// The dragAndDrop function that helps creating new nodes
// Just by dragging elements into the canvas.
// DragOver from the Sidebars.
const onDragOver = (event) => {
	event.preventDefault();
	if (event.dataTransfer) {
		event.dataTransfer.dropEffect = "move";
	}
};
////////////////////////////////////////////.

// The onDrop event handler that is responsible for the creation
const onDrop = (event) => {
	// console.log(event.target.parentNode);
	createVueNode(event, addNodes, project, store);
};
////////////////////////////////////////////.

// OnConnect node event, there is more work to do here.
onConnect((params) => {
	(params.type = "custom"), (params.animated = false);
	addEdges([params]);
});
////////////////////////////////////////////.

// Handling Clicked message to the message editor
// OnClick : connect message clicked to the message editor.
const onClick = (event) => {
	if (event.node.type == "facebook-message") {
		if (messageToEdit.value == event.node.id) {
			messageToEdit.value = "";
		} else {
			messageToEdit.value = event.node.id;
		}
	}
	store.messageToEdit = messageToEdit.value;
};
////////////////////////////////////////////.

// Implementation of a global key listener
let onKeyUp = (event) => {
	switch (event.key) {
		case "AltGraph":
			setInteractive(true);
			break;

		// Close the editor if Escape key is pressed
		case "Escape":
			messageToEdit.value = "";
			break;

		default:
			break;
	}
};

let onKeyDown = (event) => {
	switch (event.key) {
		case "AltGraph":
			setInteractive(false);
			break;

		default:
			break;
	}
};

onMounted(() => {
	templates();
	window.addEventListener("keydown", onKeyDown);
	window.addEventListener("keyup", onKeyUp);
});
////////////////////////////////////////////.

// Local variables and props declaration.
let messageToEdit = ref("");
const elements = ref(initialElements);
////////////////////////////////////////////.

// Removing data from the message store if delete button used
const onChange = (event) => {
	event.forEach((element) => {
		if (element.type == "remove") {
			store.layers.messages = store.layers.messages.filter((item) => {
				return item.id != element.id;
			});
		}
	});
};

async function templates() {
	let url = getMixinValue.getUrl("whatsapp-templates?flow_builder=1");
	await axios.get(url).then((response) => {
		if (response.data.success) {
			data.templates = response.data.templates;
		}
	});
}

////////////////////////////////////////////.
const data = reactive({
	opened: false,
	text: "",
	text_duration: "",
	current_id: "",
	type: "",
	image: "",
	file_duration: "",
	image_duration: "",
	audio: "",
	audio_duration: "",
	video_duration: "",
	video: "",
	location_duration: "",
	latitude: "",
	longitude: "",
	template_id: "",
	template_variables: {},
	variables: [],
	templates: [],
	box_title: "",
	match_type: "",
	condition_fields: [
		{
			variable: "",
			operator: "",
			value: "",
		},
	],
	condition_variable_options: [
		{
			label: "First Name",
			value: "first_name",
		},
		{
			label: "Last Name",
			value: "last_name",
		},
		{
			label: "Label",
			value: "label",
		},
		{
			label: "Email",
			value: "email",
		},
		{
			label: "Phone Number",
			value: "phone_number",
		},
	],
	condition_operator_options: [
		{
			label: "=",
			value: "equal",
		},
		{
			label: "<",
			value: "less_than",
		},
		{
			label: ">",
			value: "greater_than",
		},
		{
			label: "≤",
			value: "less_than_or_equal",
		},
		{
			label: "≥",
			value: "greater_than_or_equal",
		},
		{
			label: "≠",
			value: "not_equal",
		},
		{
			label: "Contains",
			value: "contains",
		},
		{
			label: "Starts With",
			value: "starts_with",
		},
		{
			label: "Ends With",
			value: "ends_with",
		},
		{
			label: "Has Value",
			value: "has_value",
		},
	],
});

function handleData(args) {
	let localStates = args.args.value;
	data.type = localStates.type;
	data.current_id = localStates.id;
	data.box_title = localStates.title;

	if (data.type == "box-with-title") {
		data.text = localStates.text;
		data.text_duration = localStates.text_duration;
	} else if (data.type == "node-image") {
		data.image = localStates.image;
		data.image_duration = localStates.image_duration;
	} else if (data.type == "box-with-audio") {
		data.audio = localStates.audio;
		data.audio_duration = localStates.audio_duration;
		const audio_element = document.getElementById("audio");
		if (audio_element) {
			audio_element.src = data.audio;
		}
	} else if (data.type == "box-with-video") {
		data.video = localStates.video;
		data.video_duration = localStates.video_duration;
		const video_element = document.getElementById("video");
		if (video_element) {
			video_element.src = data.video;
		}
	} else if (data.type == "box-with-file") {
		data.file = localStates.file;
		data.file_duration = localStates.file_duration;
	} else if (data.type == "box-with-location") {
		data.latitude = localStates.latitude;
		data.longitude = localStates.longitude;
		data.location_duration = localStates.location_duration;
	} else if (data.type == "box-with-template") {
		data.template_id = localStates.template_id;
	} else if (data.type == "box-with-condition") {
		data.match_type = localStates.match_type;
		data.condition_fields = localStates.condition_fields;
	} else if (data.type == "box-with-interactive") {
		data.match_type = localStates.match_type;
		data.condition_fields = localStates.condition_fields;
	}
}

async function handleFile(event, type) {
	let file = event.target.files[0];
	await uploadFile(file, type);
}

async function uploadFile(file, type) {
	let config = {
		headers: {
			"Content-Type": "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2),
			"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
		},
	};
	let form_data = new FormData();
	form_data.append("file", file);
	form_data.append("id", data.current_id);
	form_data.append("type", data.type);
	let url = getMixinValue.getUrl("upload-files");
	await axios
		.post(url, form_data, config)
		.then((response) => {
			if (type == "audio") {
				data.audio = response.data.file_object.file;
				const audio_element = document.getElementById("audio");
				audio_element.src = response.data.file_object.file;
			} else if (type == "video") {
				data.video = response.data.file_object.file;
				const video_element = document.getElementById("video");
				video_element.src = response.data.file_object.file;
			} else {
				data[type] = response.data.file_object.file;
			}
		})
		.catch((error) => {});
}
function addRow() {
	data.condition_fields.push({
		variable: "",
		operator: "",
		value: "",
	});
}
function removeRow() {
	data.condition_fields.pop();
}
</script>

<template>
	<div>
		<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
			<div class="offcanvas-header">
				<h5 class="offcanvas-title" id="offcanvasRightLabel">
					Configure <span>{{ data.box_title }}</span>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			</div>
			<div class="offcanvas-body">
				<div v-if="data.type == 'starter-box'">
					<div class="trigger-trigger-keyword-group trigger-onDemand-field form-group text-left mt-2">
						<label for="trigger-trigger-keyword"> Write down the keywords for which the bot will bet triggered </label>
						<input
							type="text"
							class="form-control"
							id="trigger-trigger-keyword"
							aria-describedby="trigger-trigger-keyword-help"
							:placeholder="getMixinValue.lang.hello_hi_start"
						/>
					</div>
					<div class="card_content">
						<div class=" form-group">
							<label for="matching_type" class="d-block">Send replay based in your matching type<span class="text-danger">*</span></label>
							<div class="flex_input p-0" style="border: none;">
								<div class="radio_button">
									<input type="radio" name="matching_types" id="exacts" value="exacts" checked />
									<label class="" for="exacts">
										Exact keyword match
									</label>
								</div>
								<div class="radio_button">
									<input type="radio" name="matching_types" id="contains" value="contains" />
									<label class="" for="contains">
										Contain
									</label>
								</div>
							</div>
						</div>

						
						<div class="form-group">
							<label for="labels" class="form-label">Label</label>
							<select class="form-select form-select-lg mb-3">
								<option selected="selected">Dropdown 01</option>
								<option>Dropdown 02</option>
								<option>Dropdown 03</option>
							</select>
						</div>
						<div class="form-group">
							<label for="sagement" class="form-label">Sagement</label>
							<select class="form-select form-select-lg mb-3">
								<option selected="selected">Dropdown 01</option>
								<option>Dropdown 02</option>
								<option>Dropdown 03</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="matching_type" class="d-block">{{ getMixinValue.lang.matching_type }}<span class="text-danger">*</span></label>
						<div class="radio_button">
							<input type="radio" name="matching_type" id="exact" value="exact" checked />
							<label class="mt-2 mb-2" for="exact">
								{{ getMixinValue.lang.exact_keyword_match }}
							</label>
						</div>
						<div class="radio_button">
							<input type="radio" name="matching_type" id="contain" value="contain" />
							<label class="mb-2" for="contain">
								{{ getMixinValue.lang.contain }}
							</label>
						</div>
					</div>

					

				</div>

				<div v-if="data.type == 'box-with-title'">
					<div class="form-group">
						<label for="">{{ getMixinValue.lang.duration }} ({{ getMixinValue.lang.in_sec }})</label>
						<input type="text" class="form-control" placeholder="e.g.10" v-model="data.text_duration" />
					</div>
					<div class="form-group">
						<label for="">Text</label>
						<input type="text" class="form-control" placeholder="Enter Text" v-model="data.text" />
					</div>
				</div>
				<div v-else-if="data.type == 'node-image'">
					<div class="form-group">
						<label for="">{{ getMixinValue.lang.duration }} ({{ getMixinValue.lang.in_sec }})</label>
						<input type="text" class="form-control" placeholder="e.g.10" v-model="data.image_duration" />
					</div>
					<div class="form-group">
						<label for="">Image</label>
						<div class="file_upload_text">
							<input type="file" accept="image/*" class="form-control" @change="handleFile($event, 'image')" />
						</div>
						<div>
							<img :src="data.image" class="image" />
						</div>
					</div>
				</div>
				<div v-else-if="data.type == 'box-with-audio'">
					<div class="form-group">
						<label for="">{{ getMixinValue.lang.duration }} ({{ getMixinValue.lang.in_sec }})</label>
						<input type="text" class="form-control" placeholder="e.g.10" v-model="data.audio_duration" />
					</div>
					<div class="form-group">
						<label for="">Audio</label>
						<div class="file_upload_text">
							<input type="file" accept="audio/*" class="form-control" @change="handleFile($event, 'audio')" />
						</div>
						<div style="margin-top: 20px" v-if="data.audio">
							<p style="margin: 0">Preview</p>
							<vue-plyr>
								<audio id="audio">
									<source :src="data.audio" type="audio/mp3" />
								</audio>
							</vue-plyr>
						</div>
					</div>
				</div>
				<div v-else-if="data.type == 'box-with-video'">
					<div class="form-group">
						<label for="">{{ getMixinValue.lang.duration }} ({{ getMixinValue.lang.in_sec }})</label>
						<input type="text" class="form-control" placeholder="e.g.10" v-model="data.video_duration" />
					</div>
					<div class="form-group">
						<label for="">Video</label>
						<div class="file_upload_text">
							<input type="file" accept="video/*" class="form-control" @change="handleFile($event, 'video')" />
						</div>
						<div style="margin-top: 20px" v-if="data.video">
							<p style="margin: 0">Preview</p>
							<vue-plyr>
								<video id="video">
									<source :src="data.video" type="video/mp4" />
								</video>
							</vue-plyr>
						</div>
					</div>
				</div>
				<div v-else-if="data.type == 'box-with-file'">
					<div class="form-group">
						<label for="">{{ getMixinValue.lang.duration }} ({{ getMixinValue.lang.in_sec }})</label>
						<input type="text" class="form-control" placeholder="e.g.10" v-model="data.file_duration" />
					</div>
					<div class="form-group">
						<label for="">File</label>
						<div class="file_upload_text">
							<input type="file" class="form-control" @change="handleFile($event, 'file')" />
						</div>
						<div style="margin-top: 20px" v-if="data.file">
							<p style="margin: 0">Preview</p>
							<iframe id="iframe" :src="data.file"></iframe>
						</div>
					</div>
				</div>
				<div v-else-if="data.type == 'box-with-interactive'">
					<div class="">
						<div class="form-group">
							<label for="">Duration (in sec)</label>
							<input type="text" class="form-control" placeholder="e.g.10">
						</div>
						<div class="form-group">
							<label for="">Text</label>
							<input type="text" class="form-control" placeholder="Test Header">
						</div>
						<div class="form-group">
							<label for="">Message Body</label>
							<textarea name="message" class="form-control" id="message" placeholder="Message Body"></textarea>
						</div>
						<div class="form-group">
							<label for="">Message Footer (Optional)</label>
							<input type="text" class="form-control" id="" placeholder="Message Footer (Optional)">
						</div>
					</div>
					
				</div>

				
				<div v-else-if="data.type == 'box-with-location'">
					<div class="form-group">
						<label for="">{{ getMixinValue.lang.duration }} ({{ getMixinValue.lang.in_sec }})</label>
						<input type="text" class="form-control" placeholder="e.g.10" v-model="data.location_duration" />
					</div>
					<div class="form-group">
						<label for="">Latitude</label>
						<input type="text" v-model="data.latitude" class="form-control" />
					</div>
					<div class="form-group">
						<label for="">Longitude</label>
						<input type="text" v-model="data.longitude" class="form-control" />
					</div>
				</div>
				<div v-else-if="data.type == 'box-with-condition'">
					<label>Match Type</label>
					<VueMultiselect
						v-model="data.match_type"
						label="label"
						track-by="value"
						:options="[
							{ value: 'all_match', label: 'All Match' },
							{ value: 'any_match', label: 'Any Match' },
						]"
					>
					</VueMultiselect>

					<div>
						<p class="mt-3 mb-1">Fields</p>
						<div class="flex_input" v-for="(field, index) in data.condition_fields" :key="index">
							<div class="form-group">
								<label for="">Variable</label>
								<VueMultiselect v-model="field.variable" label="label" track-by="value" :options="data.condition_variable_options"> </VueMultiselect>
							</div>
							<div class="form-group">
								<label for="">Operator</label>
								<VueMultiselect v-model="field.operator" label="label" track-by="value" :options="data.condition_operator_options"> </VueMultiselect>
							</div>
							<div class="form-group">
								<label for="">Value</label>
								<input type="text" class="form-control" v-model="field.value" />
							</div>
							<div class="form-group">
								<label for="">Action</label>
								<div class="action_btn">
									<a v-if="index > 0" href="javascript:void(0)" @click="removeRow"><i class="las la-minus"></i></a>
									<a v-else href="javascript:void(0)" @click="addRow"><i class="las la-plus"></i></a>
								</div>
							</div>
						</div>
					</div>

					<table class="table table-bordered d-none">
						<thead>
							<tr>
								<th>Variable</th>
								<th>Operator</th>
								<th>Value</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(field, index) in data.condition_fields" :key="index">
								<td>
									<VueMultiselect v-model="field.variable" label="label" track-by="value" :options="data.condition_variable_options"> </VueMultiselect>
								</td>
								<td>
									<VueMultiselect v-model="field.operator" label="label" track-by="value" :options="data.condition_operator_options"> </VueMultiselect>
								</td>
								<td>
									<input type="text" class="form-control" v-model="field.value" />
								</td>
								<td>
									<a v-if="index > 0" href="javascript:void(0)" @click="removeRow"><i class="las la-minus"></i></a>
									<a v-else href="javascript:void(0)" @click="addRow"><i class="las la-plus"></i></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!--        <div class="form-group" v-else-if="data.type == 'box-with-template'">
          <label>Template</label>
          <select class="form-control" v-model="data.template_id">
            <option value="">Select Template</option>
            <option v-for="(template, index) in data.templates" :key="index" :value="template.id">{{ template.category }} => {{ template.name }}</option>
          </select>
          <div v-if="data.variables.length > 0">
            <div class="form-group" v-for="(variable, index) in data.variables" :key="index">
              <label>{{ variable }}</label>
              <input type="text" v-model="data.template_variables[variable]" class="form-control">
            </div>
          </div>
        </div>-->
			</div>
			<div class="offcanvas-footer">
				<div class="offcanvas-button">
					<button data-bs-dismiss="offcanvas" class="btn sg-btn-primary w-100">Save</button>
				</div>
			</div>
		</div>
		<div id="allTheNav">
			<div class="flowBuilder__inner d-flex" style="height: 100vh">
				<globalMenuVue></globalMenuVue>
				<div class="m-1 border" id="vue_flow" oncontextmenu="return false;" style="position: relative">
					<VueFlow
						v-model="elements"
						class="customnodeflow"
						:snap-to-grid="true"
						:select-nodes-on-drag="true"
						:only-render-visible-elements="true"
						:default-viewport="{ zoom: 0.5 }"
						:max-zoom="50"
						:min-zoom="0.05"
						@dragover="onDragOver"
						@drop="onDrop"
						@nodeDoubleClick="onClick"
						@nodesChange="onChange"
					>
						<Background pattern-color="grey" gap="16" size="1.2" />
						<template #connection-line="{ sourceX, sourceY, targetX, targetY }">
							<CustomConnectionLine :source-x="sourceX" :source-y="sourceY" :target-x="targetX" :target-y="targetY" />
						</template>
						<template #edge-custom="props">
							<customEdgeVue v-bind="props" />
						</template>
						<template #node-redirector="props">
							<redirectorEdgeVue v-bind="props" />
						</template>
						<template #node-starter-box="props">
							<boxWithStarter 
							@data-sent="handleData"
							:id="props.id" 
							:selected="props.selected" 
							:text="data.text" 
							:current_id="data.current_id" 
							/>
						</template>
						<template #node-box-with-title="props">
							<boxWithTitleVue
								@data-sent="handleData"
								:id="props.id"
								:selected="props.selected"
								:duration="data.text_duration"
								:text="data.text"
								:current_id="data.current_id"
							/>
						</template>
						<template #node-node-image="props">
							<imageContainerVue
								@data-sent="handleData"
								:id="props.id"
								:selected="props.selected"
								:duration="data.image_duration"
								:image="data.image"
								:current_id="data.current_id"
							/>
						</template>
						<template #node-box-with-audio="props">
							<boxWitAudioVue
								@data-sent="handleData"
								:id="props.id"
								:selected="props.selected"
								:duration="data.audio_duration"
								:audio="data.audio"
								:current_id="data.current_id"
							/>
						</template>
						<template #node-box-with-video="props">
							<boxWitVideoVue
								@data-sent="handleData"
								:id="props.id"
								:selected="props.selected"
								:duration="data.video_duration"
								:video="data.video"
								:current_id="data.current_id"
							/>
						</template>
						<template #node-box-with-file="props">
							<boxWithFileVue
								@data-sent="handleData"
								:id="props.id"
								:selected="props.selected"
								:duration="data.file_duration"
								:file="data.file"
								:current_id="data.current_id"
							/>
						</template>
						<template #node-box-with-location="props">
							<boxLocation
								@data-sent="handleData"
								:id="props.id"
								:selected="props.selected"
								:duration="data.location_duration"
								:latitude="data.latitude"
								:longitude="data.longitude"
								:current_id="data.current_id"
							/>
						</template>
						<template #node-box-with-template="props">
							<boxTemplate
								@data-sent="handleData"
								:id="props.id"
								:selected="props.selected"
								:template_id="data.template_id"
								:template_variables="data.template_variables"
								:current_id="data.current_id"
							/>
						</template>
						<template #node-box-with-condition="props">
							<boxCondition
								@data-sent="handleData"
								:id="props.id"
								:selected="props.selected"
								:current_id="data.current_id"
								:match_type="data.match_type"
								:condition_fields="data.condition_fields"
							/>
						</template>
						<template #node-box-with-interactive="props">
							<boxWithInteractive
								@data-sent="handleData"
								:id="props.id"
								:duration="data.location_duration"
								:selected="props.selected"
								:match_type="data.match_type"
								:current_id="data.current_id"
							/>
						</template>
						
						<Controls />
						<MiniMap v-show="messageToEdit === ''" />
					</VueFlow>
				</div>
			</div>
		</div>
		<!-- {{ store }} -->
	</div>
</template>
