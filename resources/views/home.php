<h1>Welcome</h1>

<?php if (isGuest()) { ?>
    <div>
        Please <a href="/login">log in</a> or register <a href="/register">here</a> to access your dashboard.
    </div>
<?php } else {  ?>
    <div>
        <a href='/contacts'>Contact List</a>
    </div>
<?php } ?>