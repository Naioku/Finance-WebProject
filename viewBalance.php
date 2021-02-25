<?php
	session_start();
	if(!isset($_SESSION['loggedIn']))
	{
		header("Location: index.php");
		exit();
	}
	
	$userId = $_SESSION['id'];
	
	$noRecordsNote = "<div class = 'narrator'>It should be records here. Choose period of time in the control panel on the left, right or above. If You already have it done, it means that You're out of records or... there is a scabby bug in my books... Then, please, let me know about this :o</div>";
	$isAnyGrupedIncomeToShow = False;
	$isAnyNotGrupedIncomeToShow = False;
	$isAnyGrupedExpenseToShow = False;
	$isAnyNotGrupedExpensesToShow = False;
	$isAnyBalanceRecord = False;
	
	// Default dates - present month
	if(!isset($_POST['dateFrom']) && !isset($_POST['dateTo']))
	{
		$_POST['dateFrom'] = date('Y-m-01');
		$_POST['dateTo'] = date('Y-m-t');
	}
	
	if(isset($_POST['dateFrom']) && isset($_POST['dateTo']))
	{
		$dateFrom = $_POST['dateFrom'];
		$dateTo = $_POST['dateTo'];
		
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
				// Getting incomes gruped by amount (without comment).
				$query = "SELECT SUM(incomes.amount) AS amount_sum, incomes_category_assigned_to_users.name AS category
				FROM incomes, incomes_category_assigned_to_users
				WHERE incomes.user_id = '$userId' AND
				incomes.date_of_income BETWEEN '$dateFrom' AND '$dateTo' AND
				incomes.user_id = incomes_category_assigned_to_users.user_id AND
				incomes.incomes_category_assigned_to_user_id = incomes_category_assigned_to_users.id
				GROUP BY incomes_category_assigned_to_users.name
				ORDER BY incomes.date_of_income
				DESC";
				
				if($grupedIncomesResult = $connection->query($query))
				{
					$grupedIncomesQuantity = $grupedIncomesResult->num_rows;
					if($grupedIncomesQuantity > 0) $isAnyGrupedIncomeToShow = True;
				}
				else
				{
					throw new Exception($connection->error);
				}
				
				// Getting incomes not gruped (with comment).
				$query = "SELECT incomes.date_of_income AS date, incomes.amount, incomes_category_assigned_to_users.name AS category, incomes.income_comment AS comment
				FROM incomes, incomes_category_assigned_to_users
				WHERE incomes.user_id = '$userId' AND
				incomes.date_of_income BETWEEN '$dateFrom' AND '$dateTo' AND
				incomes.user_id = incomes_category_assigned_to_users.user_id AND
				incomes.incomes_category_assigned_to_user_id = incomes_category_assigned_to_users.id
				ORDER BY incomes.date_of_income
				DESC";
				
				if($notGrupedIncomesResult = $connection->query($query))
				{
					$notGrupedIncomesQuantity = $notGrupedIncomesResult->num_rows;
					if($notGrupedIncomesQuantity > 0) $isAnyNotGrupedIncomeToShow = True;
				}
				else
				{
					throw new Exception($connection->error);
				}
				
				// Getting gruped expenses (without comment).
				$query = "SELECT SUM(expenses.amount) AS amount_sum, expenses_category_assigned_to_users.name AS category
				FROM expenses, expenses_category_assigned_to_users
				WHERE expenses.user_id = '$userId' AND
				expenses.date_of_expense BETWEEN '$dateFrom' AND '$dateTo' AND
				expenses.user_id = expenses_category_assigned_to_users.user_id AND
				expenses.expenses_category_assigned_to_user_id = expenses_category_assigned_to_users.id
				GROUP BY expenses_category_assigned_to_users.name
				ORDER BY expenses.date_of_expense
				DESC";
				
				if($grupedExpensesResult = $connection->query($query))
				{
					$grupedExpensesQuantity = $grupedExpensesResult->num_rows;
					if($grupedExpensesQuantity > 0) $isAnyGrupedExpenseToShow = True;
				}
				else
				{
					throw new Exception($connection->error);
				}
				
				// Getting expenses not gruped (with comment).
				$query = "SELECT expenses.date_of_expense AS date, expenses.amount, expenses_category_assigned_to_users.name AS category, payment_methods_assigned_to_users.name AS payment_method, expenses.expense_comment AS comment
				FROM expenses, expenses_category_assigned_to_users, payment_methods_assigned_to_users
				WHERE expenses.user_id = '$userId' AND
				expenses.date_of_expense BETWEEN '$dateFrom' AND '$dateTo' AND
				expenses.user_id = expenses_category_assigned_to_users.user_id AND
				expenses.expenses_category_assigned_to_user_id = expenses_category_assigned_to_users.id AND
				expenses.payment_method_assigned_to_user_id = payment_methods_assigned_to_users.id
				ORDER BY expenses.date_of_expense
				DESC";
				
				if($notGrupedExpensesResult = $connection->query($query))
				{
					$notGrupedExpensesQuantity = $notGrupedExpensesResult->num_rows;
					if($notGrupedExpensesQuantity > 0) $isAnyNotGrupedExpensesToShow = True;
				}
				else
				{
					throw new Exception($connection->error);
				}
				
				// Balance
				$query = "SELECT SUM(expenses.amount) AS amount_sum
				FROM expenses, expenses_category_assigned_to_users
				WHERE expenses.user_id = '$userId' AND
				expenses.date_of_expense BETWEEN '$dateFrom' AND '$dateTo' AND
				expenses.user_id = expenses_category_assigned_to_users.user_id AND
				expenses.expenses_category_assigned_to_user_id = expenses_category_assigned_to_users.id;";
				
				if($expensesResult = $connection->query($query))
				{
					if($expensesResult->num_rows > 0)
					{
						$row = $expensesResult->fetch_assoc();
						$expensesSum = $row['amount_sum'];
						if($expensesSum == NULL) $expensesSum = 0;
					}
				}
				else
				{
					throw new Exception($connection->error);
				}
				
				$query = "SELECT SUM(incomes.amount) AS amount_sum
				FROM incomes, incomes_category_assigned_to_users
				WHERE incomes.user_id = '$userId' AND
				incomes.date_of_income BETWEEN '$dateFrom' AND '$dateTo' AND
				incomes.user_id = incomes_category_assigned_to_users.user_id AND
				incomes.incomes_category_assigned_to_user_id = incomes_category_assigned_to_users.id";
				
				if($incomesResult = $connection->query($query))
				{
					if($incomesResult->num_rows > 0)
					{
						$row = $incomesResult->fetch_assoc();
						$incomesSum = $row['amount_sum'];
						if($incomesSum == NULL) $incomesSum = 0;
					}
				}
				else
				{
					throw new Exception($connection->error);
				}
				
				if(isset($incomesSum) && isset($expensesSum))
				{
					
					$balance = $incomesSum - $expensesSum;
					$isAnyBalanceRecord = True;
				}
				
				$connection->close();
			}
		}
		catch(Exception $e)
		{
			$_SESSION['dbConnectionErrorMsg'] = "Error: ".$connection->connect_errno;
			//$_SESSION['dbConnectionErrorMsg_devInfo'] = "Developer's info: ".$e;
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
			
					<div class = "container myViewBalanceContainer">
					
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
										<a class = "nav-link" href = "add-income">Add income</a>
									</li>
									<li class = "nav-item myNavItem">
										<a class = "nav-link" href = "add-expense">Add expense</a>
									</li>
									<li class = "nav-item myNavItem">
										<a class = "nav-link active myActive" href = "view-balance">View balance</a>
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
											Your balance?
										</h1>
										<div class = "oneLineCenterText">Ok! Give me date and I'll find the necessary books ;p</div>
									</div>
								
								</header>
								
								<div class = "viewBalanceContent">
								
									<div class = "balanceControlPanelLeft">
										
										<form method = "post">
										
											<div class = "row">
												
												<div class = "col-12 balanceControlPanelDates">
													<div class = "col-12 col-sm-6 balanceControlPanelDate">
														<input class = "form-control balanceControlPanel" type = "text" name = "dateFrom" id = "dateFrom" placeholder = "date from" onfocus = "(this.type='date')" onblur = "(this.type='text')" required>
													</div>
													
													<div class = "col-12 col-sm-6 balanceControlPanelDate">
														<input class = "form-control balanceControlPanel" type = "text" name = "dateTo" id = "dateTo" placeholder = "date to" onfocus = "(this.type='date')" onblur = "(this.type='text')" required>
													</div>
													<div style = "clear: both;"></div>
												</div>
											
											</div>
											
											<div class = "row">
											
												<div class = "col-12 col-sm-10">
													<div class = "balanceControlPanelMonthYearButtons">
														<button class = "balanceControlPanel" type = "button" onclick = "inputPresentMonthDate()">Present month</button>
														<button class = "balanceControlPanel" type = "button" onclick = "inputPreviousMonthDate()">Previous month</button>
														<button class = "balanceControlPanel" type = "button" onclick = "inputPresentYearDate()">Present year</button>
														<div style = "clear: both;"></div>
													</div>
												</div>
												
												<div class = "col-12 col-sm-2 okButtonCol">
													<div class = "balanceControlPanelSubmit">
														<button class = "balanceControlPanelSubmit"><i class = "icon-ok"></i></button>
														<div style = "clear: both;"></div>
													</div>
												</div>
												
											</div>
											
											<div class = "row">
											
												<a href = "main-menu">
													<div class = "col-12 backViewBalance">
														<i class = "icon-undo"></i><br />
														<span class = "text">Back</span>
													</div>
												</a>
											
											</div>
											
										</form>
										
									</div>
									<div style = "clear: both;"></div>
									
									<div class = "balanceContentUp">
										<div class = "row">
											<div class = "col-12 col-sm-6">
												<div class = "viewBalanceTablesPositive">
													<div class = "viewBalanceTableNamesPositive">Short incomes view</div>
													<?php
														if($isAnyGrupedIncomeToShow)
														{
															echo '<table class = "positiveBalanceContent">';
															echo '<thead class = "positiveBalanceContent">';
															echo '<tr class = "positiveBalanceContent">';
															echo '<th class = "amount">Amount [zł]</th>';
															echo '<th class = "category">Category</th>';
															echo '</tr>';
															echo '</thead>';
															echo '<tbody>';
															for($i = 1; $i <= $grupedIncomesQuantity; $i++)
															{
																$row = $grupedIncomesResult->fetch_assoc();
																if($i % 2 == 1)
																{
																	echo '<tr class = "positiveBalanceContentOdd">';
																	echo '<th class = "amount">'.$row['amount_sum'].'</th>';
																	echo '<th class = "category">'.$row['category'].'</th>';
																	echo '</tr>';
																}
																else if($i % 2 == 0)
																{
																	echo '<tr class = "positiveBalanceContentEven">';
																	echo '<th class = "amount">'.$row['amount_sum'].'</th>';
																	echo '<th class = "category">'.$row['category'].'</th>';
																	echo '</tr>';
																}
															}
															echo '</tbody>';
															echo '</table>';
														}
														else
														{
															echo $noRecordsNote;
														}
													?>
												</div>
												<div style = "clear: both;"></div>
											</div>
											
											<div class = "col-12 col-sm-6">
												<div class = "viewBalanceTablesNegative">
													<div class = "viewBalanceTableNamesNegative">Short expenses view</div>
													<?php
														if($isAnyGrupedExpenseToShow)
														{
															echo '<table class = "negativeBalanceContent">';
															echo '<thead class = "negativeBalanceContent">';
															echo '<tr class = "negativeBalanceContent">';
															echo '<th class = "amount">Amount [zł]</th>';
															echo '<th class = "category">Category</th>';
															echo '</tr>';
															echo '</thead>';
															echo '<tbody>';
															for($i = 1; $i <= $grupedExpensesQuantity; $i++)
															{
																$row = $grupedExpensesResult->fetch_assoc();
																if($i % 2 == 1)
																{
																	echo '<tr class = "negativeBalanceContentOdd">';
																	echo '<th class = "amount">'.$row['amount_sum'].'</th>';
																	echo '<th class = "category">'.$row['category'].'</th>';
																	echo '</tr>';
																}
																else if($i % 2 == 0)
																{
																	echo '<tr class = "negativeBalanceContentEven">';
																	echo '<th class = "amount">'.$row['amount_sum'].'</th>';
																	echo '<th class = "category">'.$row['category'].'</th>';
																	echo '</tr>';
																}
															}
															echo '</tbody>';
															echo '</table>';
														}
														else
														{
															echo $noRecordsNote;
														}
													?>
												</div>
												<div style = "clear: both;"></div>
											</div>
										</div>

									</div>
									
									<div class = "balanceControlPanelRight">
									
										<form method = "post">
											<div class = "balanceControlPanelDate">
												<input class = "form-control balanceControlPanel" type = "text" name = "dateFrom" placeholder = "date from" id = "dateFromMobileView" onfocus = "(this.type='date')" onblur = "(this.type='text')" required>
											</div>
											<div class = "balanceControlPanelDate">
												<input class = "form-control balanceControlPanel" type = "text" name = "dateTo" placeholder = "date to" id = "dateToMobileView" onfocus = "(this.type='date')" onblur = "(this.type='text')" required>
											</div>
											<div style = "clear: both;"></div>
											
											<div class = "balanceControlPanelMonthYearButtons">
												<button class = "balanceControlPanel" type = "button" onclick = "inputPresentMonthDate('dateFromMobileView', 'dateToMobileView')">Present month</button>
												<button class = "balanceControlPanel" type = "button" onclick = "inputPreviousMonthDate('dateFromMobileView', 'dateToMobileView')">Previous month</button>
												<button class = "balanceControlPanel" type = "button" onclick = "inputPresentYearDate('dateFromMobileView', 'dateToMobileView')">Present year</button>
											</div>
											
											<button class = "balanceControlPanelSubmit"><i class = "icon-ok"></i></button>
											<div style = "clear: both;"></div>
											
											<a href = "main-menu">
												<div class = "backViewBalance">
													<i class = "icon-undo"></i><br />
													<span class = "text">Back</span>
												</div>
											</a>
										</form>
									</div>
									<div style = "clear: both;"></div>
									
									<div class = "balanceContentDownInfo">
										<div class = "narrator">
											[If You want to see full statement turn on auto rotation and rotate Your phone.]
										</div>
									</div>
									
									<div class = "balanceContentDown">
										<div class = "viewBalanceTables">
											<div class = "viewBalanceTableNamesPositive">Full incomes view</div>
											<?php
												if($isAnyNotGrupedIncomeToShow)
												{
													echo '<table class = "positiveBalanceContent">';
													echo '<thead class = "positiveBalanceContent">';
													echo '<tr class = "positiveBalanceContent">';
													echo '<th class = "date">Date</th>';
													echo '<th class = "amount">Amount [zł]</th>';
													echo '<th class = "category">Category</th>';
													echo '<th class = "comment">Comment</th>';
													echo '</tr>';
													echo '</thead>';
													echo '<tbody>';
													for($i = 1; $i <= $notGrupedIncomesQuantity; $i++)
													{
														$row = $notGrupedIncomesResult->fetch_assoc();
														if($i % 2 == 1)
														{
															echo '<tr class = "positiveBalanceContentOdd">';
															echo '<th class = "date">'.$row['date'].'</th>';
															echo '<th class = "amount">'.$row['amount'].'</th>';
															echo '<th class = "category">'.$row['category'].'</th>';
															echo '<th class = "comment">'.$row['comment'].'</th>';
															echo '</tr>';
														}
														else if($i % 2 == 0)
														{
															echo '<tr class = "positiveBalanceContentEven">';
															echo '<th class = "date">'.$row['date'].'</th>';
															echo '<th class = "amount">'.$row['amount'].'</th>';
															echo '<th class = "category">'.$row['category'].'</th>';
															echo '<th class = "comment">'.$row['comment'].'</th>';
															echo '</tr>';
														}
													}
													echo '</tbody>';
													echo '</table>';
												}
												else
												{
													echo $noRecordsNote;
												}
											?>
										</div>
										
										<div class = "viewBalanceTables">
											<div class = "viewBalanceTableNamesNegative">Full expenses view</div>
											<?php
												if($isAnyNotGrupedExpensesToShow)
												{
													echo '<table class = "negativeBalanceContent">';
													echo '<thead class = "negativeBalanceContent">';
													echo '<tr class = "negativeBalanceContent">';
													echo '<th class = "date">Date</th>';
													echo '<th class = "amount">Amount [zł]</th>';
													echo '<th class = "category">Category</th>';
													echo '<th class = "paymentMethod">Payment Method</th>';
													echo '<th class = "comment">Comment</th>';
													echo '</tr>';
													echo '</thead>';
													echo '<tbody>';
													for($i = 1; $i <= $notGrupedExpensesQuantity; $i++)
													{
														$row = $notGrupedExpensesResult->fetch_assoc();
														if($i % 2 == 1)
														{
															echo '<tr class = "negativeBalanceContentOdd">';
															echo '<th class = "date">'.$row['date'].'</th>';
															echo '<th class = "amount">'.$row['amount'].'</th>';
															echo '<th class = "category">'.$row['category'].'</th>';
															echo '<th class = "paymentMethod">'.$row['payment_method'].'</th>';
															echo '<th class = "comment">'.$row['comment'].'</th>';
															echo '</tr>';
														}
														else if($i % 2 == 0)
														{
															echo '<tr class = "negativeBalanceContentEven">';
															echo '<th class = "date">'.$row['date'].'</th>';
															echo '<th class = "amount">'.$row['amount'].'</th>';
															echo '<th class = "category">'.$row['category'].'</th>';
															echo '<th class = "paymentMethod">'.$row['payment_method'].'</th>';
															echo '<th class = "comment">'.$row['comment'].'</th>';
															echo '</tr>';
														}
													}
													echo '</tbody>';
													echo '</table>';
												}
												else
												{
													echo $noRecordsNote;
												}
											?>
										</div>
										
										<div class = "balance">
											<?php
												if($isAnyBalanceRecord)
												{
													if($balance > 0)
													{
														echo 'Your balance: <span class = "balancePositive">'.$balance.'</span><br />';
														echo 'During this period it seems <span class = "balancePositive">nice :)</span>';
													}
													else if($balance < 0)
													{
														echo 'Your balance: <span class = "balanceNegative">'.$balance.'</span><br />';
														echo "During this period you had <span class = 'balanceNegative'>some expenses</span>, didn't you?";
													}
													else if($balance == 0)
													{
														echo 'Your balance: <span class = "balanceNeutral">'.$balance.'</span><br />';
														echo 'During this period it seems <span class = "balanceNeutral">ok :)</span>';
													}
													
												}
											?>
										</div>
									</div>
									<div style = "clear: both;"></div>
								
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
	
	<script src = "viewBalanceJavaScript.js"></script>
	
</body>