<script setup>
import SidebarTemplate from "./SidebarTemplate.vue";
import CardText from "../../assets/svg/CardText.svg";
import BiGoogle from "../../assets/svg/BiGoogle.svg";
import CardImage from "../../assets/svg/CardImage.svg";
import CardAudio from "../../assets/svg/CardAudio.svg";
import CardVideo from "../../assets/svg/CardVideo.svg";
import CardFile from "../../assets/svg/CardFile.svg";
import CardLocation from "../../assets/svg/CardLocation.svg";
import CardCondition from "../../assets/svg/CardCondition.svg";
import CardInteractive from "../../assets/svg/CardInteractive.svg";
import DashSquare from "../../assets/svg/DashSquare.svg";
import globalValue from "../../mixins/helper.js";
const getMixinValue = globalValue();

const templates = [
	{
		type: "box-with-title",
		title: "Text",
		subtitle: "",
		logoColor: "#129f7f",
		logoComponent: CardText,
	},
	{
		type: "node-image",
		title: "Image",
		subtitle: "",
		logoColor: "#5392f9",
		logoComponent: CardImage,
	},
	{
		type: "box-with-audio",
		title: "Audio",
		subtitle: "",
		logoColor: "#fdc334",
		logoComponent: CardAudio,
	},
	{
		type: "box-with-video",
		title: "Video",
		subtitle: "",
		logoColor: "#ff6b6b",
		logoComponent: CardVideo,
	},
	{
		type: "box-with-file",
		title: "File",
		subtitle: "",
		logoColor: "#1565c0",
		logoComponent: CardFile,
	},
	{
		type: "box-with-location",
		title: "Location",
		subtitle: "",
		logoColor: "#0eab61",
		logoComponent: CardLocation,
	},
	{
		type: "box-with-interactive",
		title: "Interactive",
		subtitle: "",
		logoColor: "#0eaba7",
		logoComponent: CardInteractive,
	},
	/* {
    type: 'box-with-template',
    title: 'Template',
    subtitle: '',
    logoColor: 'red',
    logoComponent: BiGoogle,
  },*/
	{
		type: "box-with-condition",
		title: "Condition",
		subtitle: "",
		logoColor: "#159be1",
		logoComponent: CardCondition,
	},
];
</script>

<template>
	<aside>
		<h1 class="flowBuilder__title">{{ getMixinValue.lang.available_components }}</h1>
		<div class="container">
			<div class="flowBuilder__wrapper">
				<SidebarTemplate v-for="template in templates" v-bind="template" />
			</div>
		</div>
	</aside>
</template>
