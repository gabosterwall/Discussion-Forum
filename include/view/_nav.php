<!-- navbar -->
<nav>
    <p id="logo">My Forum</p>

    <div class="nav-items">
        <div class="ajax-link">Home</div>
        <div class="ajax-link">About</div>
        <div class="ajax-link">Contact</div>
        <div class="ajax-link">Policy</div>
        <?php if(isset($_SESSION['userid'])): ?>
            <div class="ajax-link">Threads</div>
        <?php endif ?>

        <?php if(!isset($_SESSION['userid'])): ?>
            <div class="nav-items">
                <div id="register" class="ajax-link">Sign Up</div>
                <button id="login" class="ajax-link">Login</button>
            </div>
        <?php endif ?>

        <?php if(isset($_SESSION['userid'])): ?>
            <div class="nav-items">
                <div class="ajax-link">My Account</div>
                <button id="logout" class="ajax-link">Log out</button>
            </div>
        <?php endif ?>
    </div>

</nav>