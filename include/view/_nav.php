<!-- navbar -->
<nav>
    <p id="logo">My Forum</p>

    <?php if(!isset($_SESSION['userid'])): ?>
        <div class="nav-items">
            <a href="index.php">Home</a>
            <a href="#contact-content">Contact</a>
        </div>
    <?php endif ?>

    <?php if(!isset($_SESSION['userid'])): ?>
        <div class="nav-items">
            <a id="login" class="ajax-link">Sign in</a>
            <button id="register" class="ajax-link">Sign up</button>
        </div>
    <?php endif ?>

    <?php if(isset($_SESSION['userid'])): ?>

        <div class="nav-items">
            <a id="threads" class="ajax-link">Threads</a>
            <a href="index.php">Home</a>
            <a href="#contact-content">Contact</a>
        </div>

        <div class="nav-items">
            <a id="profile" class="ajax-link" >My account</a>
            <button id="logout" class="ajax-link">Log out</button>
        </div>
    <?php endif ?>
    

</nav>