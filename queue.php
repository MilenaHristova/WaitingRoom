<html>
<head>
<meta charset="UTF-8"/>
<title> Чакалня </title>
    <link rel="stylesheet" href="queue.css">
</head>

<body>
    <div class="container">
        <div class="navbar">
            <button><a href=#>Изход</a></button>
        </div>
        <?php if(5 == 5) : ?>
          <div class="panel">Твой ред е! url: hhhhhhhh парола: хххх</div>
        <?php endif; ?>
        
        <div class="left-panel">
            <h2>Опашка чакащи</h2>
            <div class="header">
                <div class="next">
                    <p>Следващият номер:</p>
                    <p>8999</p>
                </div>
                <div class="time">
                    <p>Средно чакане:</p>
                    <p>25 минути</p>
                </div>
            </div>
            
            <div class="queue">
                <ul class="list">
                    <li>First</li>
                    <li>Second</li>
                    <li>Third</li>
                </ul>
            </div>
        </div>
        <div class="center">
            <h2>Временна опашка</h2>
            <div class="header">
                <div class="next">
                    <p>Следващият номер:</p>
                    <p>8999</p>
                </div>
            </div>
            
            <div class="queue">
                <ul class="list">
                    <li>First</li>
                    <li>Second</li>
                    <li>Third</li>
                </ul>
            </div>
        </div>
        <div class="side-panel">
            <div class="chat">
                <p>Съобщения</p>
                <div class="msg">
                    <p class="msg-text">Message 1</p>
                    <p class="author">Author</p>
                </div>
                <div class="msg">
                    <p class="msg-text">Message 2</p>
                    <p class="author">Author</p>
                </div>
            </div>
            <div class="add-msg">
                <form action="db.php" method="post">
                    <textarea name="msg"></textarea>
                    <input type="radio" name="send_to" id="all" value="all">
                    <label for="all">до всички</label>
                    <input type="radio" name="send_to" id="teacher" value="teacher">
                    <label for="teacher">до преподавател</label>
                    <input type="submit" class="msg-submit-btn" value="Изпрати">
                </form>
            </div>
            
       </div>
    </div>
    
    

</body>

</html>