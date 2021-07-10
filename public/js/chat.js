const formulario = document.getElementById("formulario");
const inputMessage = document.getElementById("inputMessage");
const msgerChat = document.getElementById("messages-chat");
const chatId = window.location.pathname.substr(7);
let authUser;
var token = document.head.querySelector('meta[name="csrf-token"]');
window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;

window.onload = function () {
    axios
        .get("/getUser")
        .then((res) => {
            authUser = res.data.authUser;
        })
        .then(() => {
            axios.get(`/chats/${chatId}/get_messages`).then((res) => {
                appendMessages(res.data.messages);
            });
        })
        .then(() => {
            Echo.private(`chat.${chatId}`).listen("NewMessage", (e) => {
                appendMessage(
                    e.message.user.name,
                    "",
                    "order-2 items-start",
                    e.message.content
                );
            });
        });
};

formulario.addEventListener("submit", (event) => {
    event.preventDefault();

    const msgText = inputMessage.value;

    if (!msgText) return;

    axios
        .post("/messages", {
            content: msgText,
            chat_id: chatId,
        })
        .then((res) => {
            let data = res.data;

            appendMessage(
                data.user.name,
                "justify-end",
                "order-1 items-end",
                data.content
            );
        })
        .catch((error) => {
            console.log(error);
        });

    inputMessage.value = "";
});

function appendMessages(messages) {
    let side = "";
    let otro = "";

    messages.forEach((message) => {
        side = message.user_id == authUser.id ? "justify-end" : "";
        otro =
            message.user_id == authUser.id
                ? "order-1 items-end"
                : "order-2 items-start";

        appendMessage(message.user.name, side, otro, message.content);
    });
}

function appendMessage(name, side, $otro, text) {
    //   Simple solution for small apps
    const msgHTML = `<div class="flex items-end ${side}">
        <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 ${$otro}">
            <div>
                <span id="text_nuevos" class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                    ${text}
                </span>
            </div> 
        </div> 
            <img src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144" alt="My profile" class="w-6 h-6 rounded-full order-1">
    </div>`;

    msgerChat.insertAdjacentHTML("beforeend", msgHTML);
}
