<?php

	// Functions
	function showOneColumnFromCategoriesOrPaymentMethods($queryResultData, $startFrom, $endOn)
	{
		for($i = $startFrom; $i <= $endOn; $i++)
		{
			$row = $queryResultData->fetch_assoc();
			echo '<div><label><input type = "radio" value = "'.$row['id'].'" name = "category">';
			echo $row['name'];
			echo '</label></div>';
		}
	}

	$noCategoriesMsg = "You have no categories to show.";
	$addedIncomeMsg = 'Income has been added successfully! :)';
	$addingIncomeErrorMsg = 'Error has been occured in adding protocol.';
	
	session_start();
	if(!isset($_SESSION['loggedIn']))
	{
		header("Location: index.php");
		exit();
	}
	
	$loggedUserId = $_SESSION['id'];
	
	$isAnyCategory = False;
	
	require_once "databaseConnect.php";
	try
	{
		$connection = @new mysqli($host, $dbUser, $dbPassword, $dbName);
		if($connection->connect_errno != 0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			$query = "SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = '$loggedUserId'";
			if($resultIncomeCategory = $connection->query($query))
			{
				$howManyCategories = $resultIncomeCategory->num_rows;
				if($howManyCategories > 0)
				{
					$isAnyCategory = True;
				}
				else
				{
					$isAnyCategory = False;
				}
			}
			else
			{
				throw new Exception($connection->error);
			}
		}
		
		$connection->close();
	}
	catch(Exception $e)
	{
		$_SESSION['dbConnectionErrorMsg'] = "Error: ".$connection->connect_errno;
		$_SESSION['dbConnectionErrorMsg_devInfo'] = "Developer's info: ".$e;
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
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
	
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
			
					<div class = "container myUserSitesContainer">
						
						<nav class = "navbar navbar-dark navbar-expand-lg myNavbar">
							<button class = "navbar-toggler myNavbar" type = "button" data-bs-toggle = "collapse" data-bs-target = "#friendlyUserMenu" aria-controls="friendlyUserMenu" aria-expanded="false" aria-label="Toggle navigation">
								<span class = "navbar-toggler-icon myNavbarTogglerIcon"></span>
							</button>
							
							<div class = "collapse navbar-collapse" id = "friendlyUserMenu">
							
								<ul class = "navbar-nav mx-auto">
								
									<li class = "nav-item myNavItem">
										<a class = "nav-link" href = "main-menu">Main menu</a>
									</li>
									<li class = "nav-item myNavItem">
										<a class = "nav-link myActive" href = "add-income">Add income</a>
									</li>
									<li class = "nav-item myNavItem">
										<a class = "nav-link active" href = "add-expense">Add expense</a>
									</li>
									<li class = "nav-item myNavItem">
										<a class = "nav-link" href = "view-balance">View balance</a>
									</li>
									<li class = "nav-item myNavItem">
										<a class = "nav-link disabled" href = "settings">Settings</a>
									</li>
									
								</ul>
								
							</div>
							
						</nav>
					
						<div class = "row">
							
							<div class = "col-12">
					
								<header>
								
									<div class = "textContainer">
										<h1 class = "articleHeaders">
											Adding income...
										</h1>
										<div class = "oneLineCenterText">Ok! So give me data... I'll do the rest.</div>
									</div>
								
								</header>
								
								<div class = "row">
							
									<div class = "col-12">
								
										<div class = "transferFormContainer">
											
											<?php
												if(isset($_SESSION['addedTransfer']) && $_SESSION['addedTransfer'] = True)
												{
													echo '<div class = "successfullyAddedTransfer">';
													echo $addedIncomeMsg;
													echo '</div>';
													unset($_SESSION['addedTransfer']);
												}
												
												if(isset($_SESSION['addTransferErrorMsg']))
												{
													echo '<div class = "allErrorMsgsDiv">';
													echo $addingIncomeErrorMsg;
													echo '</div>';
													echo $_SESSION['addTransferErrorMsg'];
													echo '</div>';
													unset($_SESSION['addTransferErrorMsg']);
												}
											?>
											
											<form action = "checkAndAddIncome.php" method = "post">
												
												<div class = "col-12 category">
												
													<fieldset class = "category">
													
														<legend> Category </legend>
														<?php
															if($isAnyCategory)
															{
																$firstBreakPoint = ceil($howManyCategories/3);
																$secondBreakPoint = $firstBreakPoint * 2;
																
																// First column
																// First record
																$row = $resultIncomeCategory->fetch_assoc();
																echo '<div class = "columns">';
																echo '<div><label><input type = "radio" value = "'.$row['id'].'" name = "category" checked>';
																echo $row['name'];
																echo '</label></div>';
																// Other records
																showOneColumnFromCategoriesOrPaymentMethods($resultIncomeCategory, 2, $firstBreakPoint);
																echo '</div>';
																
																//Second column
																echo '<div class = "columns">';
																showOneColumnFromCategoriesOrPaymentMethods($resultIncomeCategory, $firstBreakPoint+1, $secondBreakPoint);
																echo '</div>';
																
																// Third column
																echo '<div class = "columns">';
																showOneColumnFromCategoriesOrPaymentMethods($resultIncomeCategory, $secondBreakPoint+1, $howManyCategories);
																echo '</div>';
															}
															else
															{
																echo $noCategoriesMsg;
															}
														?>
																											
														<div style = "clear:both;"></div>
														
													</fieldset>
																											
														<div style = "clear:both;"></div>
													</fieldset>
													
												</div>
												
												<div class = "col-12 col-md-5 col-lg-4" style = "float:left;">
												
													<div class = "col-12 amountAndDate">
														<input class = "form-control transferForm" type = "number" step = 0.01 placeholder = "amount" name = "amount" required>					
														<input class = "form-control transferForm" type = "text" name = "date" placeholder = "date" onfocus = "(this.type='date')" onblur = "(this.type='text')" required>
													</div>
													<div style = "clear:both;"></div>
													
													
												</div>
											
												<div class = "col-12 col-md-7 col-lg-8" style = "float:left;">
																								
													<div class = "col-12 textArea">
														<textArea class = "form-control textArea" placeholder = "comment (optional)" name = "comment" ></textArea>
													</div>
													<div style = "clear:both;"></div>
												
												</div>
												<div style = "clear:both;"></div>												
											
												<div class = "col-12 buttonsContainer">
												
													<button class = "addIncome">							
														<i class = "icon-dollar"></i><br />
														<span class = "text">Add income</span>
													</button>
													
													<a href = "main-menu">
														<div class = "back">
															<i class = "icon-undo"></i><br />
															<span class = "text">Back</span>
														</div>
													</a>
													<div style = "clear:both;"></div>

												</div>
												
												
											</form>
										
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
	
	<!--
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
	
	<script src = "bootstrap/js/bootstrap.min.js"></script>
	-->

</body>