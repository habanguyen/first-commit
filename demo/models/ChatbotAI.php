<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chatbot hỗ trợ khách hàng</title>
    <style>
        #chatbox { width: 100%; height: 400px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px; }
        #userInput { width: 80%; }
        #sendBtn { width: 18%; }
    </style>
</head>
<body>
    <h2>Trợ lý AI hỗ trợ khách hàng</h2>
    <div id="chatbox"></div>
    <input type="text" id="userInput" placeholder="Nhập câu hỏi...">
    <button id="sendBtn">Gửi</button>

    <script>
        document.getElementById('sendBtn').onclick = function () {
            var userText = document.getElementById('userInput').value;
            document.getElementById('chatbox').innerHTML += "<b>Bạn:</b> " + userText + "<br>";

            fetch("chatbot.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "question=" + encodeURIComponent(userText)
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('chatbox').innerHTML += "<b>AI:</b> " + data + "<br>";
                document.getElementById('userInput').value = "";
                document.getElementById('chatbox').scrollTop = document.getElementById('chatbox').scrollHeight;
            });
        };
    </script>
</body>
</html>
