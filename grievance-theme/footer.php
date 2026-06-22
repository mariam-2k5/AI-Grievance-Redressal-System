</div>

<footer>
    <p>&copy; <?php echo date('Y'); ?> AI Grievance Redressal System</p>
</footer>

<?php wp_footer(); ?>
<?php if ( is_page('submit-grievance') || is_page('track-grievance') ) : ?>
<div id="chatbot">
    <div id="chat-header">🤖 Help Bot</div>
    <div id="chat-body"></div>
    <input type="text" id="chat-input" placeholder="Ask something..." />
</div>
<?php endif; ?>

</body>
</html>

<script>
document.getElementById("chat-input")?.addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        let input = this.value.toLowerCase();
        let body = document.getElementById("chat-body");

        body.innerHTML += "<p><strong>You:</strong> " + this.value + "</p>";

        let reply = "I can help you with grievance submission, tracking, or resolution time.";

if (input.includes("hi") || input.includes("hello")) {
    reply = "Hello 👋 How can I assist you today?";
}
else if (input.includes("submit")) {
    reply = "To submit a grievance, go to the Submit Grievance page and fill in all required details.";
}
else if (input.includes("status") || input.includes("track")) {
    reply = "You can track your grievance using your Grievance ID on the Track Grievance page.";
}
else if (input.includes("time") || input.includes("days")) {
    reply = "Most grievances are resolved within 3–5 working days.";
}
else if (input.includes("resolved")) {
    reply = "Once resolved, the status will change to Resolved and admin reply will be visible.";
}
else if (input.includes("admin")) {
    reply = "Admins review grievances and provide updates through the dashboard.";
}
else if (input.includes("thank")) {
    reply = "You're welcome 😊 Happy to help!";
}

        body.innerHTML += "<p><strong>Bot:</strong> " + reply + "</p>";
        this.value = "";
        body.scrollTop = body.scrollHeight;
    }
});
</script>
    