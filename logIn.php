<?php
	session_start();
	
	if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true)
	{
		header("Location: mainMenu.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>Catch Your Coins</title>
	<meta name="description" content="Catch Your every single coin!">
	<meta name="keywords" content="money, savings, credits, new car, new game, new clothes">
	<meta name="author" content="Naioku">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type = "text/css">
	<link rel="stylesheet" href="style.css" type = "text/css">
	<link rel="stylesheet" href="fontello/css/fontello.css" type = "text/css">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Nerko+One&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	
	<!--[if lt IE 9]>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
</head>
<body>

	<header>
		<div class = "container-fluid logo">
			<div class = "logoIcon">
				<i class = "icon-wallet"></i>
			</div>
			<div class = "fullLogo">
				Catch Your <span class = "logo">Coins</span>
			</div>
			<div class = "shortLogo">
				ChYr<span class = "logo">Cs</span>
			</div>
			<div style = "clear: both"></div>
		</div>
		
	</header>
	
	<div class = "wrapper">
	
		<main>
		
			<article>
				
				<section>
			
					<div class = "container myContainer">
						
						<div class = "row">
							
							<div class = "col-12">
					
								<header>
								
									<div class = "textContainer">
										<h1 class = "articleHeaders">Come in!</h1>
										<div class = "oneLineCenterText">Use Your key, to get in! :)</div>
									</div>
								
								</header>
								
								<div class = "row">
								
									<div class = "col-12">
								
										<div class = "registrationLogInForm">
											
											<form action = "logInPHPOnly.php" method = "post">
												
												<input class = "form-control registrationLogInForm" type = "text" placeholder = "login" name = "login" required>
												<input class = "form-control registrationLogInForm" type = "password" placeholder = "password" name = "password" required>
												<div style = "clear:both;"></div>
												
												<input class = "registrationLogInForm" type = "submit" value = "Yup! It's me!">
												<div style = "clear:both;"></div>
												
											</form>
											
											<?php
											
												if(isset($_SESSION['logInErrorMsg'])) echo $_SESSION['logInErrorMsg'];
											
											?>
											
										</div>
									
									</div>
								
								</div>
								
							</div>
							
						</div>
						
					</div>
				
				</section>
			
			</article>
		
		</main>
	
	</div>
	
	<footer>
	
		<div class = "container-fluid footer">
			
			<div class = "footerLink">
				<a href="https://www.freepik.com/photos/tree" target = "_blank">
					Tree photo created by jcomp - www.freepik.com
				</a>
			</div>
			<br />
			<div class = "footerAuthor"><span>Author: Adrian Komuda. All rights reserved &copy; </span></div>
			<div class = "footerThanks">Thankyou for visit!</div>
		
		</div>
	
	</footer>
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
	
	<script src = "bootstrap/js/bootstrap.min.js"></script>

</body>