<?php
    include "include/functions/config.php";
?>

<div class="profile-page">

        <div class="profile-container">

            <?php
                $arr = fetchUserInfo();

                if($arr['Image'] == null || $arr['Image'] == ''){
                    echo '<img src="img/default_user.png">';
                }
                else{
                    echo '<img src="'.$arr['Image'].'">';
                }
            ?>
            
            <div class="profile-box">

                <label for="Username">Username:</label>
                <h2 name="Username"><?php echo $arr['Username']; ?></h2>
        
                <label for="Email">Email:</label>
                <h2 name="Email"><?php echo $arr['Email']; ?></h2>
                
            </div>
            
            <button>Edit Profile</button>
            
        </div>

    </div>