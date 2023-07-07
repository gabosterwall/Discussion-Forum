<div class="profile">

    <img src="" alt="">

    <div class="message-box"></div>

    <form class="profile-container" id="profForm" enctype="multipart/form-data">

        <div class="profile-box">
            <label for="new_username">Username: </label>
            <input type="text" name="new_username" spellcheck="false">

            <label for="new_email">Email: </label>
            <input type="email" name="new_email" spellcheck="false">

            <label for="new_img">Upload image: </label>
            <input type="file" name="new_img" accept="image/jpeg, image/jpg, image/png">
        </div>

        <div class="profile-box">
            <label for="password">Old password: </label>
            <input type="password" name="password" placeholder="(Required)" spellcheck="false" required>

            <label for="cpassword">Confirm password: </label>
            <input type="password" name="cpassword" placeholder="(Required)" spellcheck="false" required>

            <label for="new_password">New password: </label>
            <input type="password" name="new_password" placeholder="" spellcheck="false">
        </div>

    </form>
    
    <button id="update-button" class="ajax-link">Update</button>

</div>