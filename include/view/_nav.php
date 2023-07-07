<!-- navbar -->
<nav>
    <p id="logo">My Forum</p>

    <?php if(!isset($_SESSION['userid'])): ?>
        <div class="nav-items">
            <a href="index.php">Home</a>
            <a href="#contact-content">Contact</a>
            <button id="register" class="ajax-link">Sign Up</button>
            <button id="login" class="ajax-link">Login</button>
        </div>
    <?php endif ?>

    <?php if(isset($_SESSION['userid'])): ?>

        <div class="nav-items">
            <?php if(isset($_SESSION['userid'])): ?>
                <a id="posts" class="ajax-link">Posts</a>
            <?php endif ?>
            <a href="index.php">Home</a>
            <a href="#contact-content">Contact</a>
        </div>

        <div class="nav-items">
            <a id="profile" class="ajax-link" >My account</a>
            <button id="logout" class="ajax-link">Log out</button>
        </div>
    <?php endif ?>
    

</nav>