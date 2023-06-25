<div class="userinput-container">

    <div class="message-box"></div>
    
    <div class="userinput-box">

        <button class="close-button">&#x2715;</button>

        <h2>Login</h2>

        <!-- ÄNDRA TILL AJAX -->

        <!-- onsubmit="return validate('loginForm')" --> 
        <form name="loginForm" id="loginForm" method="POST">

            <input type="text" name="username" placeholder="New username" required spellcheck="false">
            
            <input type="password" name="password" placeholder="New password" required spellcheck="false">

            <input type="submit" id="login-button" value="Login">
            
        </form>
       
    </div>

</div>