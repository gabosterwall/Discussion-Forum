<!DOCTYPE html>
<html lang="en">
<head>
    <?php session_start(); ?>
    <title>[Logo]</title>
    <?php include "include/view/_header.php"?>
</head>
<body>
    
    <?php
        include "include/view/_nav.php";
    ?>

    <!-- about start -->
    <section class="home-page">

        <div class="home-content">
            <h2>Lorem ipsum dolor sit amet consectetur adipisicing elit. Minus quia pariatur, cupiditate nisi, eaque odit possimus dolores asperiores totam dolorem aliquam veniam. Temporibus, voluptate. Iusto in nihil incidunt eaque molestias.</h2>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aliquid quo qui architecto officia facere sint autem perferendis aspernatur inventore ea minus non consectetur, sed voluptas expedita laborum! Maxime, unde ipsam?</p>
        </div>

    </section>

    <div id="content-container"></div>

    <?php
        include "include/view/_footer.php";
    ?>

</body>

</html>