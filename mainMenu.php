<?php
	session_start();
	if(!isset($_SESSION['loggedIn']))
	{
		header("Location: index.php");
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
<body class = "userPanel">

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
										<h1 class = "articleHeaders">
											Oh! It's You! Hi,<br />
											<?php
												
												if (isset($_SESSION['name']) && isset($_SESSION['surname']))
												{
													echo '<span class = "userName">'.$_SESSION['name'].' '.$_SESSION['surname'].'</span> :)';
												}
												else if(isset($_SESSION['name']))
												{
													echo '<span class = "userName">'.$_SESSION['name'].'</span> :)';
													
												}
												else if(isset($_SESSION['surname']))
												{
													echo '<span class = "userName">'.$_SESSION['surname'].'</span> :)';
													
												}
												else
												{
													echo '<span class = "userName">'.$_SESSION['login'].'</span> :)';
												}
																								
											?>
										</h1>
										<div class = "oneLineCenterText">What are we going to do today? Which book should I bring?</div>
									</div>
								
								</header>
								
								<div class = "row">
							
									<div class = "col-12">
								
										<div class = "buttonsContainer">
										
											<a href = "add-income">
												<div class = "incomeButton">							
													<i class = "icon-dollar"></i><br />
													<span class = "buttonText">Add income</span>
												</div>
											</a>
											
											<a href = "add-expense">
												<div class = "expenseButton">
													<i class = "icon-money"></i><br />
													<span class = "buttonText">Add expense</span>
												</div>
											</a>
											<div style = "clear:both;"></div>
											
											<a href = "view-balance">
												<div class = "viewBalanceButton">							
													<i class = "icon-balance-scale"></i><br />
													<span class = "buttonText">View balance</span>
												</div>
											</a>
											<div style = "clear:both;"></div>
											
											<a href = "settings" class = "disabled">
												<div class = "settingsButton">							
													<i class = "icon-cog-alt"></i><br />
													<span class = "buttonText">Settings</span>
												</div>
											</a>
											
											<a href = "log-out">
												<div class = "logOutButton">
													<i class = "icon-logout"></i><br />
													<span class = "buttonText">Log out</span>
												</div>
											</a>
											
											<div style = "clear:both;"></div>
											
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
				<a href="https://www.pixiv.net/en/users/1736499" target = "_blank">
				Background art <span class = "japaneseText">「受験生の夏」</span> created by <span class = "japaneseText">杉８７</span> - www.pixiv.net. Please check out the profile!
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