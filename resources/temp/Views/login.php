<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sistem Informasi Importir</title>
<link href="web/assets/mdbootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="web/assets/font-awesome/css/all.css">
<style type="text/css">
    body {
		font-family: 'Varela Round', sans-serif;
	}    
    #myModal {
        position: absolute;
        margin: auto;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;        
        background-color: #ccc;
        border-radius: 3px;
    }
	.modal-login {		
		color: #636363;                
		width: 350px;
	}
	.modal-login .modal-content {
		padding: 20px;
		border-radius: 5px;
		border: none;
	}
	.modal-login .modal-header {
		border-bottom: none;   
        position: relative;
        justify-content: center;
	}
	.modal-login h4 {
		text-align: center;
		font-size: 26px;
		margin: 30px 0 -15px;
	}
	.modal-login .form-control:focus {
		border-color: #70c5c0;
	}
	.modal-login .form-control, .modal-login .btn {
		min-height: 40px;
		border-radius: 3px; 
	}	
	.modal-login .modal-footer {
		background: #ecf0f1;
		border-color: #dee4e7;
		text-align: center;
        justify-content: center;
		margin: 0 -20px -20px;
		border-radius: 5px;
		font-size: 13px;
	}
	.modal-login .modal-footer a {
		color: #999;
	}		
	.modal-login .avatar {
		position: absolute;
		margin: 0 auto;
		left: 0;
		right: 0;
		top: -70px;
		width: 95px;
		height: 95px;
		border-radius: 50%;
		z-index: 9;    
        padding-top: 20px;
		background: #60c7c1;
		text-align: center;
		box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
	}
	.modal-login .avatar img {
		width: 100%;
	}
	.modal-login.modal-dialog {
		margin-top: 80px;
	}
    .modal-login .btn {
        color: #fff;
        border-radius: 4px;
		background: #60c7c1;
		text-decoration: none;
		transition: all 0.4s;
        line-height: normal;
        border: none;
    }
	.modal-login .btn:hover, .modal-login .btn:focus {
		background: #45aba6;
		outline: none;
	}
	.trigger-btn {
		display: inline-block;
		margin: 100px auto;
	}
</style>
</head>
<body>

<!-- Modal HTML -->
<div id="myModal">
	<div class="modal-dialog modal-login mx-auto">
		<div class="modal-content">
			<div class="modal-header">
				<div class="avatar">
					<i class="fa fa-user fa-3x text-white"></i>
				</div>				
				<h4 class="modal-title">Member Login</h4>	                
			</div>
			<div class="modal-body">
				<form action="/login" method="POST">
					<div class="form-group">
						<input type="text" class="form-control" name="username" placeholder="Username" required="required">		
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="password" placeholder="Password" required="required">	
					</div>        
					<div class="form-group">
						<input type="submit" class="btn btn-primary btn-lg btn-block login-btn" value="Login">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<a href="#">{{ message }}</a>
			</div>
		</div>
	</div>
</div>     
</body>
<script src="web/assets/jquery/jquery.min.js"></script>
<script src="web/assets/mdbootstrap/js/bootstrap.min.js"></script>
</html>                           