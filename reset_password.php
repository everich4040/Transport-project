<?php
    error_reporting(0);
    if(!isset($_REQUEST['uId']) || !isset($_REQUEST['key']) || empty($_REQUEST['uId']) || empty($_REQUEST['key'])){
        
        #############
        ## PRINT FAILED HTML VALIDATION

        echo 'validation failed! <h1 style="color:orangered"> please generate another code for reset </h1>';
        die();
        
    }
    include_once $_SERVER['DOCUMENT_ROOT'].'/server/assets/templates/dbConnect.php';
    
    $sql='SELECT * FROM `reset` WHERE `user_id` = :ID AND `hash`=:hash';
    $result=$db->query($sql,array(':ID'=>$_REQUEST['uId'],':hash'=>$_REQUEST['key']));
    
    if(!$result){
        
        #############
        ## PRINT FAILED HTML VALIDATION

        echo 'validation failed! <h1 style="color:orangered"> please generate another code for reset </h1>';
        die();
    
    }


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transport project</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/media_queries.css" />
    <link rel="stylesheet" href="./fontawesome/css/all.min.css">
    <!-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body>
    <!--The container carrying the entire page-->
    <div class="round_bg"><img src="img/round.png" alt="" /></div>
    <div class="curved_bg"><img src="img/curve.png" alt="" /></div>
    <div class="container">
        <div class="side_bg">
            <div class="inner_img">
                <img src="img/nice-guy.png" alt="" />
            </div>
        </div>
        <div class="registration">
            <!--Header of the page ie the logo, burger icon and the navigation links-->
            <header>
                <div class="logo">
                    <h1>LOGO</h1>
                </div>

                <!---THis is the starting of the navigation menu ie the links and burger icons-->
                <nav>
                    <div class="menu_bar">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </div>
                    <ul>
                        <li><a href="">Home</a></li>
                        <li><a href="">About</a></li>
                        <li><a href="">Contact</a></li>
                        <li><a href="">Portfolio</a></li>
                    </ul>
                </nav>
            </header>
            <!---The registration form-->
            <section class="sign_up">
                <form action="./server/formHandler.php" method="POST" id="resetForm">
                    <h3>Reset Password</h3>

                    
                    <div class="password">
                        <i class="fa fa-lock"></i>
                        <input name='password' type="password" placeholder="new Password" required />
                        <i class="fa fa-eye eye"></i>
                    </div>


                    <div class="submit">
                        <button name="reset_psw" class="btn_sign_up reset login_sign_in" onclick="ajax_request('resetForm',response);">
                            Reset Password
                        </button>
                    </div>
                    
                    
                    <input type="hidden" name="action" value="reset">

                    <?php
                    
                        echo '<input type="hidden" name="uId" value="'.$_REQUEST['uId'].'">';
                        echo '<input type="hidden" name="key" value="'.$_REQUEST['key'].'">';

                    ?>


                </form>
            </section>
        </div>
    </div>
    <script src="js/style.js"></script>
    <script src="js/ajax/main.js"></script>

    <script>



        function response(res) {

            console.log(res.response);

        }

    </script>

</body>

</html>