(function () {
    "use strict";

    var root = document.querySelector("[data-gu-chatbot]");
    if (!root) {
        return;
    }

    var endpoint = root.getAttribute("data-endpoint");
    var botName = root.getAttribute("data-bot-name") || "G_universiteBot";
    var toggle = root.querySelector("[data-gu-chat-toggle]");
    var panel = root.querySelector("[data-gu-chat-panel]");
    var messagesEl = root.querySelector("[data-gu-chat-messages]");
    var form = root.querySelector("[data-gu-chat-form]");
    var input = root.querySelector("[data-gu-chat-input]");
    var sendBtn = root.querySelector("[data-gu-chat-send]");
    var closeBtn = root.querySelector("[data-gu-chat-close]");
    var clearBtn = root.querySelector("[data-gu-chat-clear]");
    var history = [];

    function updateThemeColor() {
        var probe = document.createElement("button");
        probe.className = "btn btn-primary";
        probe.style.cssText = "position:absolute;left:-9999px;top:-9999px;";
        document.body.appendChild(probe);
        var color = window.getComputedStyle(probe).backgroundColor || "#5A8DEE";
        document.body.removeChild(probe);
        root.style.setProperty("--gu-chat-primary", color);
    }

    function getContext() {
        var heading = document.querySelector(".content-header-title, h1, h2, title");
        return {
            title: heading ? heading.textContent.trim() : document.title,
            path: window.location.pathname + window.location.search,
            url: window.location.href
        };
    }

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function addMessage(role, content) {
        var message = document.createElement("div");
        message.className = "gu-chatbot__message gu-chatbot__message--" + role;
        message.textContent = content;
        messagesEl.insertBefore(message, root.querySelector("[data-gu-chat-typing]"));
        scrollToBottom();
    }

    function setTyping(isTyping) {
        root.classList.toggle("is-typing", isTyping);
        sendBtn.disabled = isTyping;
        input.disabled = isTyping;
        scrollToBottom();
    }

    function openChat() {
        root.classList.add("is-open");
        toggle.setAttribute("aria-expanded", "true");
        setTimeout(function () {
            input.focus();
        }, 80);
    }

    function closeChat() {
        root.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
    }

    function clearConversation() {
        history = [];
        messagesEl.querySelectorAll(".gu-chatbot__message").forEach(function (node) {
            node.remove();
        });
        addMessage("assistant", "Bonjour, je suis " + botName + ". Comment puis-je vous aider dans G_UNIVERSITE ?");
    }

    function pushHistory(role, content) {
        history.push({ role: role, content: content });
        if (history.length > 10) {
            history = history.slice(history.length - 10);
        }
    }

    function sendMessage(message) {
        addMessage("user", message);
        pushHistory("user", message);
        setTyping(true);

        fetch(endpoint, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                message: message,
                history: history.slice(0, -1),
                context: getContext()
            })
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    if (!response.ok) {
                        throw new Error(data.error || "Erreur lors de la reponse IA.");
                    }
                    return data;
                });
            })
            .then(function (data) {
                var answer = data.answer || "Je n ai pas pu repondre pour le moment.";
                addMessage("assistant", answer);
                pushHistory("assistant", answer);
            })
            .catch(function (error) {
                addMessage("assistant", error.message || "Le service IA est indisponible pour le moment.");
            })
            .finally(function () {
                setTyping(false);
                input.focus();
            });
    }

    toggle.addEventListener("click", function () {
        if (root.classList.contains("is-open")) {
            closeChat();
        } else {
            openChat();
        }
    });

    closeBtn.addEventListener("click", closeChat);
    clearBtn.addEventListener("click", clearConversation);

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        var message = input.value.trim();
        if (!message) {
            return;
        }
        input.value = "";
        sendMessage(message);
    });

    input.addEventListener("keydown", function (event) {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault();
            form.dispatchEvent(new Event("submit", { cancelable: true }));
        }
    });

    updateThemeColor();
    clearConversation();

    if (window.MutationObserver) {
        new MutationObserver(updateThemeColor).observe(document.body, {
            attributes: true,
            attributeFilter: ["class"]
        });
    }
})();
