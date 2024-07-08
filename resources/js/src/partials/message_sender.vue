<template>
	<div class="sp-static-bar chat__sendArea">
		<form class="new-chat-form" action="#" @submit.prevent="sendMessage">
			<textarea v-model="getMixinValue.storeData.message" @keyup.enter="sendMessage" :placeholder="getMixinValue.lang.send_a_message" id="textarea"></textarea>
			<div class="left-icons">
				<div class="form-icon icon-edit">
					<i class="las la-edit"></i>
				</div>
			</div>
			<button type="submit" class="form-icon icon-send">
				<i class="las la-paper-plane"></i>
			</button>
			<button type="button" v-if="data.message_loading" class="form-icon icon-send">
				<i class="las la-spin la-spinner"></i>
			</button>
			<div class="dropDown__icon">
				<i class="las la-ellipsis-v"></i>
			</div>
			<div class="bottom-icons position-relative">
				<div style="position: absolute; display: none; bottom: 60px; right: 0" class="emoji_div" tabindex="-1">
					<EmojiPicker :native="true" @select="onSelectEmoji" />
				</div>
				<button type="button" class="bottom-icon button-gallary">
					<input type="file" accept="image/*,video/*,audio/*" id="image" class="input-file" name="image" @change="imageUp($event)" />
					<i class="las la-image"></i>
				</button>
				<button type="button" class="bottom-icon button-paperclip">
					<input type="file" id="file" class="input-file" name="file" @change="fileUp($event)" accept="application/pdf" />
					<i class="las la-paperclip"></i>
				</button>
				<div class="chat_popupBox">
					<button type="button" class="bottom-icon button-saved" @click="cannedMessages">
						<i class="las la-plus-square"></i>
					</button>
					<div class="savad-item-area show-item" tabindex="-1" style="display: none">
						<div class="saved-item-card">
							<div class="header-area">
								<h6 class="title">{{ getMixinValue.lang.saved_replies }}</h6>
							</div>
							<div class="body-area">
								<ul class="span-tag-list mt-0" v-if="data.canned_responses.length > 0">
									<li class="cursor-pointer canned_li" v-for="(response, index) in data.canned_responses" :key="index" @click="setMessage(response)">
										<span class="p-tag chat-sm-text">{{ response.name }} - {{ response.reply_text }}</span>
									</li>
								</ul>
								<p class="desc" v-else>
									{{ getMixinValue.lang.no_saved_replies }} >
									<a target="_blank" :href="getMixinValue.getUrl('bot-reply/create')">{{ getMixinValue.lang.admin }}</a>
								</p>
							</div>
						</div>
					</div>
				</div>
				<button type="button" class="bottom-icon picker">
					<i class="las la-grin-hearts"></i>
				</button>
				<button type="button" class="bottom-icon button-mic mic-button-activatin" @click="data.show_audio_recorder = !data.show_audio_recorder">
					<i class="las la-microphone"></i>
				</button>
				<div class="chat_popupBox">
					<!-- <button type="button" class="bottom-icon" id="ai_popup" @click="cannedMessages">
						<i class="las la-robot"></i>
					</button> -->
					<div class="modal-mask ai_popup">
						<div class="modal-wrapper">
							<div class="modal-container">
								<div class="modal-header modal-title">
									<div class="row w-100">
										<div class="col-6"><p class="m-0 mt-3">Ai Add Note</p></div>
										<div class="col-6 text-end">
											<button type="button" class="btn btn_close"><i class="las la-times"></i></button>
										</div>
									</div>
								</div>
								<div class="modal-body d-none">
									<div class="title-area mb-4">
										<div class="title-mid mb-4">Title</div>
										<input type="text" class="sp_modal_text" />
									</div>
									<div class="title-area mb-4">
										<div class="title-mid mb-4">Note</div>
										<textarea placeholder="Add Note" rows="5" spellcheck="false"></textarea>
									</div>
								</div>
								<div class="modal-body">
									<div class="custom__radio">
										<div>
											<input type="radio" id="test01" name="radio-group" checked />
											<label for="test01">Professional</label>
										</div>
										<div>
											<input type="radio" id="test02" name="radio-group" />
											<label for="test02">Emotional </label>
										</div>
										<div>
											<input type="radio" id="test03" name="radio-group" />
											<label for="test03">Funny</label>
										</div>
										<div>
											<input type="radio" id="test04" name="radio-group" />
											<label for="test04">Potential</label>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<div class="modal-footer mt-3">
										<button type="button" class="btn btn-primary btn-lg mt-0">Save</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="chat_popupBox">
					<!-- <button type="button" class="bottom-icon action-area" id="bot_popup" @click="bot_popup">
						<i class="lab la-android"></i>
					</button> -->

					<!-- Modal -->
					<div class="modal-mask bot_popup">
						<div class="modal-wrapper">
							<div class="modal-container">
								<div class="modal-header modal-title">
									<div class="row w-100">
										<div class="col-6"><p class="m-0 mt-3">Bot Add Note</p></div>
										<div class="col-6 text-end">
											<button type="button" class="btn btn_close"><i class="las la-times"></i></button>
										</div>
									</div>
								</div>
								<div class="modal-body">
									<div class="custom__radio">
										<div>
											<input type="radio" id="test1" name="radio-group" checked />
											<label for="test1">Professional</label>
										</div>
										<div>
											<input type="radio" id="test2" name="radio-group" />
											<label for="test2">Emotional </label>
										</div>
										<div>
											<input type="radio" id="test3" name="radio-group" />
											<label for="test3">Funny</label>
										</div>
										<div>
											<input type="radio" id="test4" name="radio-group" />
											<label for="test4">Potential</label>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<div class="modal-footer mt-3">
										<button type="button" class="btn btn-primary btn-lg mt-0">Save</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<div class="audio-peeker-area" :class="{ active: data.show_audio_recorder }">
			<div class="chat-container">
				<div class="audio-container">
					<button type="button" class="close-audio-peeker btn-pk btn-pk-round" :title="getMixinValue.lang.cancel" @click="closeAudioPeeker">
						<i class="las la-times"></i>
					</button>
					<button type="button" class="btn-pk btn-pk-round" id="stopRecording" @click="stopRecording" v-if="recording" :title="getMixinValue.lang.stop_recording">
						<i class="las la-stop"></i>
					</button>
					<button type="button" class="btn-pk btn-pk-round" id="startRecording" v-else @click="startRecording" :title="getMixinValue.lang.start_recording">
						<i class="las la-play"></i>
					</button>
					<audio id="audioPlayer" src="" controls></audio>
					<button type="button" v-if="data.message_loading" class="btn-pk">
						<i class="las la-spin la-spinner"></i>
					</button>
					<button v-else type="button" class="btn-pk" @click="sendRecorderAudio">
						<i class="las la-paper-plane"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script setup>
import EmojiPicker from "vue3-emoji-picker";
import { onMounted, reactive, ref, watch } from "vue";
import globalValue from "../mixins/helper.js";
const getMixinValue = globalValue();

const props = defineProps(["chat_room_id"]);
const emit = defineEmits(["sendMessages"]);

onMounted(() => {
	getMixinValue.storeData.receiver_id = props.chat_room_id;
});

watch(
	() => props.chat_room_id,
	() => {
		getMixinValue.storeData.receiver_id = props.chat_room_id;
	}
);
const data = reactive({
	show_emoji: false,
	show_audio_recorder: false,
	recording: false,
	message_loading: false,
	show_canned_replies: false,
	canned_responses: [],
	audio_chunks: [],
	media_recorder: null,
});

function onSelectEmoji(emoji) {
	getMixinValue.storeData.message += emoji.i;
}

async function cannedMessages() {
	if (data.canned_responses.length > 0) {
		return;
	}
	let url = getMixinValue.getUrl("canned-responses");
	await axios
		.get(url)
		.then((response) => {
			if (response.data.success) {
				data.canned_responses = response.data.canned_responses;
			}
		})
		.catch((error) => {
			data.message_loading = false;
			return alert("Something went wrong");
		});
}

async function setMessage(response) {
	getMixinValue.storeData.message = response.reply_text;
}

async function sendMessage() {
	if (!getMixinValue.storeData.message.trim()) {
		return alert("Please enter message");
	}
	getMixinValue.params_data.page = 1;
	await message();

	// Clear input fields
	getMixinValue.storeData.message = ""; // Clear message input
	getMixinValue.storeData.image = null; // Clear image input
	getMixinValue.storeData.document = null; // Clear document input

	document.getElementById("file").value = "";
	document.getElementById("image").value = "";
}

async function imageUp(event) {
	getMixinValue.storeData.image = event.target.files[0];
	await message();
}

async function fileUp(event) {
	getMixinValue.storeData.document = event.target.files[0];
	await message();
}

async function message() {
	let config = {
		headers: {
			"Content-Type": "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2),
			"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
		},
	};
	data.message_loading = true;
	let url = getMixinValue.getUrl("send-message");
	await axios
		.post(url, getMixinValue.createFormData(), config)
		.then((response) => {
			data.message_next_page_url = true;
			data.message_loading = false;
			if (response.data.success) {
				getMixinValue.storeData.message = "";
				getMixinValue.storeData.image = null; // Clear image input
				getMixinValue.storeData.document = null; // Clear document input
				emit("sendMessages", { message_sender: 1 });
				return true;
			} else {
			}
		})
		.catch((error) => {
			data.message_loading = false;
			return alert("Something went wrong");
		});
}
let audio_stream, recorder, file;
let recording = ref(false);
async function startRecording() {
	try {
		audio_stream = await navigator.mediaDevices.getUserMedia({ audio: true });
		recording.value = true;
		recorder = new MediaRecorder(audio_stream);
		recorder.ondataavailable = function (e) {
			file = e.data;
		};
		recorder.start();
	} catch (error) {
		alert(error);
	}
}

function stopRecording() {
	if (recorder && recorder.state === "recording") {
		recorder.onstop = function () {
			const audio_element = document.getElementById("audioPlayer");
			audio_element.src = URL.createObjectURL(file);
		};
		recorder.stop();
		audio_stream.getAudioTracks()[0].stop();
		recording.value = false;
	}
}
function closeAudioPeeker() {
	data.show_audio_recorder = false;
	if (recorder && recorder.state === "recording") {
		recorder.stop();
		audio_stream.getAudioTracks()[0].stop();
		recording.value = false;
		file = null;
		const audio_element = document.getElementById("audioPlayer");
		audio_element.src = "";
	}
}
async function sendRecorderAudio() {
	if (!file) {
		return alert("Please record audio first");
	}
	const last_file = new File([file], "test.mp3", { type: "audio/mp3" });
	getMixinValue.storeData.image = last_file;
	await message();
	file = null;
}
</script>
