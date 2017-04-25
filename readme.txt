README:

1. We can signup using our own username and other details but in case an existing one is required, use username: "smith" and password: "mypass".
2. I've used formaction="board.php?replyto=" followed by message ID for reply buttons but it'll not be shown in the page when you enter text and click on reply button. This is because I had to redirect the webpage to "board.php" (you can see the "header("Location:board.php");" statement after each of the insert statements) in order to avoid duplicate entries into the DB on page reload.