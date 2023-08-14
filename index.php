<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тествовое задание | Feedback form</title>
    <link rel="stylesheet" href="styles/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
</head>

<body>
    <header>
        <h1>Добро пожаловать!</h1>
        <div class="auth">
            <span class="login" onclick="alert('Не успел доделать :(')">Войти</span>
        </div>
        <div class="navbar">
            <a href="/#home" class="navitem">Главная страница</a>
            <a href="/#news" class="navitem">Новости</a>
            <a href="/#forum" class="navitem">Форум</a>
            <a href="/#aboutUs" class="navitem">О нас</a>
            <a href="/" class="navitem selected">Обратная связь</a>
        </div>
    </header>
    <div class="reviews">
        <?php

        $conn  = new mysqli("localhost", "root", "", "feedback");

        $sql = "SELECT * FROM feedbacks WHERE approved = true ORDER by created_at DESC";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $template = '<div class="review">
                <img src="%s">
                <div class="info">
                    <span class="name">%s</span>
                    <span class="email">%s</span>
                    <div class="message">
                        %s
                    </div>
                </div>
                </div><br />';
            while ($row = $result->fetch_assoc()) {
                echo sprintf($template, $row['url'], $row['name'], $row['email'], $row['text']);
            }
        } else {
            echo "No reviews";
        }

        ?>
    </div>
    <div class="wrapper">
        <h2>Обратная связь</h2>
        <span class="error">Error</span>
        <form method="post" enctype="multipart/form-data" id="form">
            <p><label for="name">Имя:</label> <input type="text" name="name" id="name" required></p>
            <p><label for="email">Почта:</label> <input type="text" name="email" id="email" required></p>
            <p><label for="text">Сообщение:</label><br /><textarea name="text" id="text" required></textarea></p>
            <p><label for="file" class="attach">Прикрепить фото</label><input type="file" name="file" id="file" accept=".jpg,.gif,.png"></p>
            <p>
                <button type="submit" name="send">Отправить</button>
                <button onclick="alert('Не успел доделать :('); return false;">Предварительный просмотр</button>
            </p>
        </form>
    </div>
    <footer>
        <span class="copyright">
            2023 (R) Все права защищены!
        </span>
    </footer>
    <script>
        // Custom code
        $(function() {

            // Adding validation        
            $("#form").validate();

            $(document).on("submit", "#form", function(e) {

                e.preventDefault(); // prevent from default action

                var formData = new FormData(this);

                $.ajax({
                    url: 'send.php',
                    type: 'post',
                    cache: false,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        data = JSON.parse(data);
                        console.log(data);
                        if (data['status']) {

                        } else {
                            $('.error').text(data['message']);
                            $('.error').css('display', 'block');
                        }
                        $("#form")[0].reset();
                    }
                });
            });
        });
    </script>
</body>

</html>