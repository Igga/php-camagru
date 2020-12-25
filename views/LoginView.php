<div class="center-wrapper">
    <?php if($error) { echo '<h3 class="error">'.$error.'</h3>';} ?>

    <h1 class="page-header">Login</h1>
    <form class="form-column" action="/user/login" method="post">
        <label for="login" class="form-label">Login</label>
        <input id="login" name="login" type="text" placeholder="Enter login" />
        <label for="password" class="form-label">Password</label>
        <input id="password" name="password" type="password" placeholder="Enter password" />

        <button type="submit" class="form-button">Login</button>
        <a>Forgot password?</a>
    </form>
</div>
