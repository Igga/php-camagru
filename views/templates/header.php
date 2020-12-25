<header class="header">
    <span class="logo">Camagru</span>
    <a href="/">Home</a>
    <a href="">Gallery</a>
    <?php if($this->isLogged()): ?>
        <div>
            <a href="/user/profile">Profile</a>
            <a href="/user/logout">Logout</a>
        </div>
    <?php else: ?>
        <div>
            <a href="/user/register">Register</a>
            <a href="/user/login">Login</a>
        </div>
    <?php endif; ?>
</header>
