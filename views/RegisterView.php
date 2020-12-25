<div class="center-wrapper">
    <?php if($error) { echo '<h3 class="error">'.$error.'</h3>';} ?>

    <h1 class="page-header">Register</h1>
    <form class="form-column" action="/user/register" method="post">
        <label for="login" class="form-label">Login</label>
        <input id="login" name="login" type="text" placeholder="Enter login" required />

        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" placeholder="Enter email" required />

        <label for="password" class="form-label">Password (min. 5 chars, low and upper)</label>
        <input id="password" name="password" type="password" placeholder="Enter password" required />

        <label for="repassword" class="form-label">Password repeat</label>
        <input id="repassword" name="repassword" type="password" placeholder="Enter password" required />

        <button type="submit" class="form-button">Register</button>
        <a href="/user/login">Have account? Login</a>
    </form>
</div>
