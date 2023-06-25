<div class="userinput-container">

    <div class="message-box"></div>

    <div class="userinput-box">

        <button class="close-button">&#x2715;</button>

        <h2>New Account</h2>

        <!-- ÄNDRA TILL AJAX -->
        
        <!-- onsubmit="return validate('regForm')" -->
        <form name="regForm" id="regForm" method="POST">

            <input type="text" name="username" placeholder="New username" required spellcheck="false">
            
            <input type="email" name="email" placeholder="Adress@email.com" required spellcheck="false">
            
            <input type="password" name="password" placeholder="New password" required spellcheck="false">

            <input type="password" name="cpassword" placeholder="Confirm password" required spellcheck="false">

            <input type="submit" id="register-button" value="Submit">
            
        </form>
       
    </div>

</div>
