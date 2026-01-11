<h1>Welcome</h1>

<?php if (isGuest()) { ?>
    <div>
        Please <a href="/login">log in</a> or register <a href="/register">here</a> to access your dashboard.
    </div>
<?php } else {  ?>
    You are logged in!
<?php } ?>