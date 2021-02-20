<?php
	
	$dbConnectionErrorMsg = "Server error. It seems I've lost my key or the books started to fly or something like that... I'm trying to fix this, but I don't know how much it'll take... Please check another time... I'm doing my best!";
	
	$registrationError_loginLengthMsg = "* Login must have from 3 to 20 characters";
	$registrationError_loginUnavailableCharMsg = "* Login must be built from letters and digits";
	$registrationError_emailIncorrectMsg = "* Type correct e-mail address";
	$registrationError_passwordLengthMsg = "* Password must have from 8 to 20 characters";
	$registrationError_passwordsNotMatchMsg = "* Passwords are not the same";
	$registrationError_emailExistsMsg = "* That email is already here";
	
	// Names of SQLs tables.
	$tableName_incomesCategoryAssignedToUsers = "incomes_category_assigned_to_users";
	$tableName_incomesCategoryDefault = "incomes_category_default";
	$tableName_expensesCategoryAssignedToUsers = "expenses_category_assigned_to_users";
	$tableName_expensesCategoryDefault = "expenses_category_default";
	$tableName_paymentMethodsAssignedToUsers = "payment_methods_assigned_to_users";
	$tableName_paymentMethodsDefault = "payment_methods_default";
	$tableName_users = "users";
	
	session_start();
	
	if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true)
	{
		header("Location: mainMenu.php");
		exit();
	}
	
	if(isset($_POST['email']))
	{
		$validationOK = true;
		
		// Login validation ===============
		$login = $_POST['login'];
		
		if((strlen($login)) < 3 || (strlen($login) > 20))
		{
			$validationOK = false;
			$_SESSION['registrationError_login'] = $registrationError_loginLengthMsg;
		}
		
		if(ctype_alnum($login) == false)
		{
			$validationOK = false;
			$_SESSION['registrationError_login'] = $registrationError_loginUnavailableCharMsg;
		}
		
		// E-mail validation ===============
		$email = $_POST['email'];
		$emailGood = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if((filter_var($emailGood, FILTER_VALIDATE_EMAIL) == false) || ($email != $emailGood))
		{
			$validationOK = false;
			$_SESSION['registrationError_email'] = $registrationError_emailIncorrectMsg;
		}
		
		// Password validation ===============
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if((strlen($password1) < 8) || (strlen($password1) > 20))
		{
			$validationOK = false;
			$_SESSION['registrationError_password'] = $registrationError_passwordLengthMsg;
		}
		
		if($password1 != $password2)
		{
			$validationOK = false;
			$_SESSION['registrationError_password'] = $registrationError_passwordsNotMatchMsg;
		}
		
		$passwordHashed = password_hash($password1, PASSWORD_DEFAULT);
		
		// Getting name and surname ===============
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		
		
		// Remember entered data ===============
		$_SESSION['regForm_name'] = $_POST['name'];
		$_SESSION['regForm_surname'] = $_POST['surname'];
		$_SESSION['regForm_login'] = $login;
		$_SESSION['regForm_email'] = $email;
		// Password should be deleted.
		
		
		require_once "databaseConnect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try
		{
			$connection = @new mysqli($host, $dbUser, $dbPassword, $dbName);
			if($connection->connect_errno != 0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$result = $connection->query("SELECT id FROM users WHERE email = '$email'");
				
				if(!$result) throw new Exception($connection->error);
				
				$howManyEmails = $result->num_rows;
				if($howManyEmails > 0)
				{
					$validationOK = false;
					$_SESSION['registrationError_email'] = $registrationError_emailExistsMsg;
				}
				
				if($validationOK == true)
				{
					// You can pass through.
					if($connection->query("INSERT INTO users VALUES(NULL, '$login', '$passwordHashed', '$email', '$name', '$surname');"))
					{
						// Addition of user - OK
						if($connection->query(
							"INSERT INTO ".$tableName_incomesCategoryAssignedToUsers." (user_id, name) SELECT ".$tableName_users.".id, ".$tableName_incomesCategoryDefault.".name FROM ".$tableName_users.", ".$tableName_incomesCategoryDefault." WHERE users.email = '$email';"))
						{
							// Addition of default incomes - OK
							if($connection->query(
							"INSERT INTO ".$tableName_expensesCategoryAssignedToUsers." (user_id, name) SELECT ".$tableName_users.".id, ".$tableName_expensesCategoryDefault.".name FROM ".$tableName_users.", ".$tableName_expensesCategoryDefault." WHERE users.email = '$email';"))
							{
								// Addition of default expenses - OK
								if($connection->query(
								"INSERT INTO ".$tableName_paymentMethodsAssignedToUsers." (user_id, name) SELECT ".$tableName_users.".id, ".$tableName_paymentMethodsDefault.".name FROM ".$tableName_users.", ".$tableName_paymentMethodsDefault." WHERE users.email = '$email';"))
								{
									// Addition of default payment methods - OK
									$_SESSION['succeededRegistration'] = true;
									$result->close();
									header("Location: welcomeNewUser.php");
								}
								else
								{
									//delete last added expenses categories
									$connection->query("DELETE FROM ".$tableName_expensesCategoryAssignedToUsers." WHERE user_id = (SELECT id FROM users WHERE email = '$email');");
									//delete last added incomes categories
									$connection->query("DELETE FROM ".$tableName_incomesCategoryAssignedToUsers." WHERE user_id = (SELECT id FROM users WHERE email = '$email');");
									//delete last added user
									$connection->query("DELETE FROM ".$tableName_users." WHERE email = '$email';");
									throw new Exception($connection->error);
								}
							}
							else
							{
								//delete last added incomes categories
								$connection->query("DELETE FROM ".$tableName_incomesCategoryAssignedToUsers." WHERE user_id = (SELECT id FROM users WHERE email = '$email');");
								//delete last added user
								$connection->query("DELETE FROM ".$tableName_users." WHERE email = '$email';");
								throw new Exception($connection->error);
							}
						}
						else
						{
							//delete last added user
							$connection->query("DELETE FROM ".$tableName_users." WHERE email = '$email';");
							throw new Exception($connection->error);
						}
					}
					else
					{
						throw new Exception($connection->error);
					}
				}
				
				$connection->close();
			}
		}
		catch(Exception $e)
		{
			$_SESSION['dbConnectionErrorMsg'] = $dbConnectionErrorMsg;
			$_SESSION['dbConnectionErrorMsg_devInfo'] = "Developer's info: ".$e;
		}		
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
	
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	
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
										<h1 class = "articleHeaders">Ok, so...</h1>
										<div class = "oneLineCenterText">Please, tell me something about You :)</div>
									</div>
								
								</header>
								
								<div class = "row">
								
									<div class = "col-12">
								
										<div class = "registrationLogInForm">
											
											<form method = "post">
											
												<input class = "form-control registrationLogInForm" name = "name" type = "text" placeholder = "name" value =
													<?php
														if(isset($_SESSION['regForm_name']))
														{
															echo $_SESSION['regForm_name'];
															unset($_SESSION['regForm_name']);
														}
													?>
												>
												<input class = "form-control registrationLogInForm" name = "surname" type = "text" placeholder = "surname" value =
													<?php
														if(isset($_SESSION['regForm_surname']))
														{
															echo $_SESSION['regForm_surname'];
															unset($_SESSION['regForm_surname']);
														}
													?>
												>
												<div style = "clear:both;"></div>
												
												<input class = "form-control registrationLogInForm" id = "login" name = "login" type = "text" placeholder = "login" required value =
													<?php
														if(isset($_SESSION['regForm_login']))
														{
															echo $_SESSION['regForm_login'];
															unset($_SESSION['regForm_login']);
														}
													?>
												>
												
												<input class = "form-control registrationLogInForm" name = "password1" type = "password" placeholder = "password" required>
												
												<input class = "form-control registrationLogInForm" name = "password2" type = "password" placeholder = "confirm password" required>
												<div style = "clear:both;"></div>
												
												<input class = "form-control registrationLogInForm" name = "email" type = "email" placeholder = "email" required value =
													<?php
														if(isset($_SESSION['regForm_email']))
														{
															echo $_SESSION['regForm_email'];
															unset($_SESSION['regForm_email']);
														}
													?>
												>
												<div style = "clear:both;"></div>
												
												<?php
													if(isset($_SESSION['registrationError_login']) ||
														isset($_SESSION['registrationError_email']) ||
														isset($_SESSION['registrationError_password']))
													{
														echo '<div class = "allErrorMsgsDiv">';
														echo '<div class = "errorMsgs">Check it:</div>';
														
														if(isset($_SESSION['registrationError_login']))
														{
															echo '<div class = "errorMsgs">'.$_SESSION['registrationError_login'].'</div>';
															unset($_SESSION['registrationError_login']);
														}
														
														if(isset($_SESSION['registrationError_email']))
														{
															echo '<div class = "errorMsgs">'.$_SESSION['registrationError_email'].'</div>';
															unset($_SESSION['registrationError_email']);
														}
														
														if(isset($_SESSION['registrationError_password']))
														{
															echo '<div class = "errorMsgs">'.$_SESSION['registrationError_password'].'</div>';
															unset($_SESSION['registrationError_password']);
														}
														
														echo '</div>';
													}
													
													if(isset($_SESSION['dbConnectionErrorMsg']))
													{
														echo '<div class = "serverErrorMsgs">';
														echo $_SESSION['dbConnectionErrorMsg'];
														echo '</div>';
														
														echo '<div class = "serverErrorMsgs">';
														echo $_SESSION['dbConnectionErrorMsg_devInfo'];
														echo '</div>';
														
														unset($_SESSION['dbConnectionErrorMsg']);
														unset($_SESSION['dbConnectionErrorMsg_devInfo']);
													}
													
													
												?>
												
												<!--
													<div class="myRecaptcha g-recaptcha" data-theme = "dark" data-size = "compact" data-sitekey="6LdbuzUaAAAAADKeQyGboG4Wz3JOkP3C_9PvMocq"></div>
												-->
												
												<input class = "registrationLogInForm" type = "submit" value = "Register!">
												<div style = "clear:both;"></div>
											
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