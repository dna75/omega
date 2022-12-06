<?php
include('./include/config.inc.php');


$message = '';
if (isset($_GET['message'])) {

	switch ($_GET['message']) {

		case "recovered":

			$message = "Het wachtwoord is hersteld, u kunt opnieuw inloggen.";
			break;

	}
}
?>

<html>
<head>

	<link rel="stylesheet" href="/cockpit/styles/login.css" type="text/css" />

    <!-- Bootstrap core CSS -->
    <link href="/cockpit/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">


	<!-- Google Fonts CSS -->

	<!-- Font Awesome -->
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <!-- eigen style sheets -->

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

</head>
<body>

<div class="container">
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-md-4 col-md-offset-4 ">
			<img class="img-responsive" src="/cockpit/images/loginspinnerz.png" alt="loginspinnerz" width="" height="" />
		</div>
	</div>

     <form class="form-signin" action="../cockpit/" method="post">
     <p class="titel red">Cockpit</p>
     <hr>


		<?php if ($message != '') echo "<p>" . $message . "</p>"; ?>

		<div class="input-group" style="padding-bottom: 15px;">
			<div style="background-color:#fff;" class="input-group-addon"><i style="background-color:#fff;" class="fa fa-user fa-fw"></i></div>
			<input type="text" class="form-control" name="login_username" placeholder="Email" onChange="javascript:this.value=this.value.toLowerCase();" autofocus>

		</div>

		<div class="input-group">
			<div style="background-color:#fff;" class="input-group-addon"><i style="background-color:#fff;" class="fa fa-key fa-fw"></i></div>
        	<input type="password" class="form-control" name="login_password" placeholder="Wachtwoord">
		</div>

		<button class="btn btn-lg btn-danger btn-block submit" style="border-radius:0px !important; background-color:#e51937;" type="submit"><i class="fa fa-lock"></i> Inloggen</button>

        <a class="btn btn-sm btn-default btn-block" style="margin-top:14px;" href="forgot_password.php">Wachtwoord vergeten? </a><span class="clearfix"></span>

      </form>
</div>

		<script src="/cockpit/scripts/jquery.js"></script>
		<script src="/cockpit/vendor/bootstrap/bootstrap.min.js"></script>



</body>
</html>
