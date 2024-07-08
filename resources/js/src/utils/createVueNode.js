import getId from "./radomId";

let i = 0;
const createVueNode = (event, addNodes, project, store) => {
    let id = getId();
    i++;

    const type = event.dataTransfer?.getData("application/vueflow");
    const position = project({x: event.clientX - 450, y: event.clientY - 20});

    let newNode = {
        id: type + id,
        type,
        position,
        label: `${type} node`,
    };

    //////////////////////////////////////////.
    switch (type) {

        case "starting-step":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "starting-step",
                    label: "Label",
                    content: "Type",
                    color: "#ffffff",
                    items: [],
                    position: i
                });
            });
            break;

        case "container":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "container",
                    label: "Label",
                    width: "20rem",
                    height: "10rem",
                    color: "#3A8CC7",
                });
            });
            break;

        case "redirector":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "redirector",
                    label: "Label",
                    color: "#000000",
                });
            });
            break;

        case "starter-box":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "starter-box",
                    label: "Label",
                    title: "Start Bot flow",
                    text: "Text",
                    subtitle: "Subtitle",
                    color: "#000000",
                    position: i
                });
            });
            break;
        case "box-with-title":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "box-with-title",
                    label: "Label",
                    title: "Title",
                    text: "Text",
                    title_duration: 0,
                    subtitle: "Subtitle",
                    color: "#000000",
                    position: i
                });
            });
            break;
        case "node-image":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "node-image",
                    label: "Label",
                    title: "Image",
                    src: "",
                    image_duration: 0,
                    image: '',
                    width: "340px",
                    height: "240px",
                    color: "#000000",
                    position: i

                });
            });
            break;
        case "box-with-audio":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "box-with-audio",
                    label: "Label",
                    title: "Audio",
                    audio_duration: 0,
                    audio: "",
                    subtitle: "Subtitle",
                    color: "#000000",
                    position: i
                });
            });
            break;
        case "box-with-video":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "box-with-video",
                    label: "Label",
                    title: "Video",
                    video_duration: 0,
                    video: "",
                    subtitle: "Subtitle",
                    color: "#000000",
                    position: i
                });
            });
            break;
        case "box-with-file":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "box-with-file",
                    label: "Label",
                    title: "File",
                    file_duration: 0,
                    file: "",
                    subtitle: "Subtitle",
                    color: "#000000",
                    position: i
                });
            });
            break;
        case "box-with-location":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "box-with-location",
                    label: "Label",
                    title: "Location",
                    location_duration: 0,
                    latitude: "",
                    longitude: "",
                    subtitle: "Subtitle",
                    color: "#000000",
                    position: i
                });
            });
            break;
        case "box-with-template":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "box-with-template",
                    label: "Label",
                    title: "Template",
                    template_id: "",
                    subtitle: "Subtitle",
                    template_variables: {},
                    color: "#000000",
                    position: i
                });
            });
            break;
        case "box-with-condition":
            store.$patch((state) => {
                state.layers.messages.push({
                    id: newNode.id,
                    type: "box-with-condition",
                    label: "Label",
                    title: "Title",
                    match_type: '',
                    condition_fields: [
                        {
                            variable: '',
                            operator: '',
                            value: ''
                        }
                    ],
                    subtitle: "Subtitle",
                    color: "#000000",
                    position: i
                });
            });
            break;
            case "box-with-interactive":
                store.$patch((state) => {
                    state.layers.messages.push({
                        id: newNode.id,
                        type: "box-with-interactive",
                        label: "Label",
                        title: "Title",
                        match_type: '',
                        condition_fields: [
                            {
                                variable: '',
                                operator: '',
                                value: ''
                            }
                        ],
                        subtitle: "Subtitle",
                        color: "#000000",
                        position: i
                    });
                });
                break;

        default:
            break;
    }
    //////////////////////////////////////////.

    // Implementation of a basic container catching
    if (event.target.parentNode.id.substring(-1, 9) === "container") {
        newNode.parentNode = event.target.parentNode.id;
    }
    ////////////////////////////////////////////.

    addNodes([newNode]);
};

const copyVueNode = (addNodes, eid, getNode, store) => {
    let id = getId(); // Create a New UUid
    const nodeById = getNode.value(eid); // Get The node to copy by its Id (eid)

    const type = nodeById.type; // Get the node's type
    // When we copy, we need to create it above the old one (translate +50 x y)
    const position = {
        ...nodeById.position,
        x: nodeById.position.x + 50,
        y: nodeById.position.y - 50,
    };

    // Create a new message in the store
    store.$patch((state) => {
        const currentMessage = state.layers.messages.filter(
            (item) => item.id === eid
        ); // Get all the old message info

        state.layers.messages = [
            ...state.layers.messages,
            {
                ...JSON.parse(JSON.stringify(currentMessage))[0], // The element is copied by reference do we need to dereference it
                id: type + id,
            },
        ];
    });

    addNodes([
        {
            id: type + id,
            type,
            position,
            label: `${type} node`,
        },
    ]);
};

export {
    createVueNode,
    copyVueNode
};
