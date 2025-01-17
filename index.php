<?php
	include('include/start.php');	

	if(!isset($_GET["ID"]))
	{
		header("Location: php/error.php");
	}
?>

<!DOCTYPE html>
<html lang="<?php echo $language ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $site_title;?> - <?php lang('Mail Release')?></title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/starter-template.css" rel="stylesheet">
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php echo $company_url; ?>"><?php echo $site_title; ?> - <?php lang('Antispam Mail Release'); ?></a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="starter-template">
			<div class="col-md-8 col-md-offset-2">
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="<?php lang('Close'); ?>"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel"><?php lang('Release result'); ?></h4>
							</div>
							<div class="modal-body">
								<div id="message"></div>
								<p><?php lang('Now you can close the browser window');?></p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php lang('Close')?></button>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><b><?php lang('Warning')?>!</b></h3>
					</div>
					<div class="panel-body">
						<p><?php lang('Sure to release message',$_GET["ID"]); ?></p>
						<p><?php lang('Release recommendation')?><p>
					</div>
				</div>
				<div class="alert alert-danger" role="alert">
					<b><?php lang('Release alert')?></b>
				</div>
				<form role="form" id="frmRelease">
					<div class="g-recaptcha" data-sitekey="<?php echo $recaptchca_api_key ?>" data-callback="enableBtn"></div>
					<p></p>
					<div class="form-group text-left">
						<button type="submit" id="submitBtn" class="btn btn-danger btn-lg"><?php lang('Release Mail')?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("submitBtn").disabled = true;

		function enableBtn(){
			document.getElementById("submitBtn").disabled = false;
		}

		$( '#frmRelease').submit( function() {
			var formControl = true;
			var mailid = "<?php Print($_GET["ID"]); ?>";
			var isHuman = grecaptcha.getResponse();

			if(isHuman.length == 0) {
				formControl = false;
			}

			if(formControl) {
				$.ajax({
					type: "POST",
					url: "php/release.php",
					data: {
							mailid:mailid,
							isHuman:isHuman
					}
				}).done(function(msg) {
					var code = msg.split('|')[0];
					var data = msg.substr(msg.indexOf("|") + 1);
					$( '#myModal' ).modal('show');
							$( '#message' ).addClass( 'alert' );
					if (code == "250") {
								$( '#message' ).addClass( 'alert-success' );
						data = "<?php lang('Release Succeeded')?><br> <br>" + data;
					} else {
						$( '#message' ).addClass( 'alert-danger' );
						data = "<?php lang('Release Failed')?><br> <br>" + data;
					}
							$( '#message').html( data );
					$( '#submitBtn' ).prop('disabled', true);
					});
			}
			return false;
		} );
	</script>
</body>
</html>
