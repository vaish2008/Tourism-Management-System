<?php
session_start();
error_reporting(0);
include ('includes/config.php');

if (isset($_POST['submit2'])) {
    $_SESSION['pkgid'] = $_POST['pkgid'];
    $_SESSION['fromdate'] = $_POST['fromdate'];
    $_SESSION['todate'] = $_POST['todate'];
    $_SESSION['comment'] = $_POST['comment'];
    $_SESSION['packageprice'] = $_POST['packageprice'];


    header('Location: booking-confirmation.php');
  
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Explore Mate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script
        type="applijewelleryion/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- Custom Theme files -->
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!--animate-->
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <link rel="stylesheet" href="css/jquery-ui.css" />
    <script>
        new WOW().init();
    </script>
    <!-- <script src="js/jquery-ui.js"></script>
                    <script>
                        $(function() {
                        $( "#datepicker,#datepicker1" ).datepicker();
                        });
                    </script>-->
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #db1a1a;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
    </style>
    <!-- Meta and other head content -->
</head>

<body>
    <!-- top-header -->
    <?php include ('includes/header.php'); ?>
    <div class="banner-3">
        <div class="container">
            <h1 class="wow zoomIn animated animated" data-wow-delay=".5s"> Explore Mate</h1>
        </div>
    </div>
    <!--- /banner ---->
    <!--- selectroom ---->
    <div class="selectroom">
        <div class="container">
            <?php
            $pid = intval($_GET['pkgid']);
            $sql = "SELECT * from tbltourpackages where PackageId=:pid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':pid', $pid, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            if ($query->rowCount() > 0) {
                foreach ($results as $result) { ?>

                    <form name="book" method="post" action="">
                        <div class="selectroom_top">
                            <div class="col-md-4 selectroom_left wow fadeInLeft animated" data-wow-delay=".5s">
                                <img src="admin/pacakgeimages/<?php echo htmlentities($result->PackageImage); ?>"
                                    class="img-responsive" alt="">
                            </div>
                            <div class="col-md-8 selectroom_right wow fadeInRight animated" data-wow-delay=".5s">
                                <h2><?php echo htmlentities($result->PackageName); ?></h2>
                                <p class="dow">#PKG-<?php echo htmlentities($result->PackageId); ?></p>
                                <p><b>Package Type :</b> <?php echo htmlentities($result->PackageType); ?></p>
                                <p><b>Package Location :</b> <?php echo htmlentities($result->PackageLocation); ?></p>
                                <p><b>Features</b> <?php echo htmlentities($result->PackageFetures); ?></p>
                                <div class="ban-bottom">
                                    <div class="bnr-right">
                                        <label class="inputLabel">From</label>
                                        <input class="date" id="datepicker" type="date" name="fromdate" required="">
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function () {
                                                var today = new Date();
                                                var day = String(today.getDate()).padStart(2, '0');
                                                var month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
                                                var year = today.getFullYear();
                                                var todayDate = year + '-' + month + '-' + day;
                                                document.getElementById("datepicker").setAttribute("min", todayDate);
                                            });
                                        </script>
                                    </div>
                                    <div class="bnr-right">
                                        <label class="inputLabel">To</label>
                                        <input class="date" id="datepicker1" type="date" name="todate" required="">
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function () {
                                                var today = new Date();
                                                var day = String(today.getDate()).padStart(2, '0');
                                                var month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
                                                var year = today.getFullYear();
                                                var todayDate = year + '-' + month + '-' + day;
                                                document.getElementById("datepicker1").setAttribute("min", todayDate);
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="grand">
                                    <p>Grand Total</p>
                                    <?php echo htmlentities($result->PackagePrice); ?>
                                    <input type="hidden" name="packageprice"
                                        value="<?php echo htmlentities($result->PackagePrice); ?>">
                                </div>
                            </div>
                            <h3>Package Details</h3>
                            <p style="padding-top: 1%"><?php echo htmlentities($result->PackageDetails); ?></p>
                            <div class="clearfix"></div>
                        </div>
                        <div class="selectroom_top">
                            <h2>Tour Package</h2>
                            <div class="selectroom-info animated wow fadeInUp animated" style="margin-top: -70px">
                                <ul>
                                    <li class="spe">
                                        <label class="inputLabel">Additional Information about vehicle</label>
                                        <input class="special" type="text" name="comment" required="">
                                    </li>
                                    <?php if ($_SESSION['login']) { ?>
                                        <li class="spe" align="center">
                                            <input type="hidden" name="pkgid"
                                                value="<?php echo htmlentities($result->PackageId); ?>">
                                            <button type="submit" name="submit2" class="btn-primary btn">Request</button>
                                        </li>
                                    <?php } else { ?>
                                        <li class="sigi" align="center" style="margin-top: 1%">
                                            <a href="request.php?pkgid=<?php echo htmlentities($result->PackageId); ?>" class="view"
                                                data-toggle="modal" data-target="#myModal4" class="btn-primary btn"> Request</a>
                                        </li>
                                    <?php } ?>
                                    <div class="clearfix"></div>
                                </ul>
                            </div>
                        </div>
                    </form>
                <?php }
            } ?>
        </div>
    </div>
    <!--- /selectroom ---->
    <!--- /footer-top ---->
    <?php include ('includes/footer.php'); ?>
    <!-- signup -->
    <?php include ('includes/signup.php'); ?>
    <!-- //signup -->
    <!-- signin -->
    <?php include ('includes/signin.php'); ?>
    <!-- //signin -->
    <!-- write us -->
    <?php include ('includes/write-us.php'); ?>
</body>

</html>