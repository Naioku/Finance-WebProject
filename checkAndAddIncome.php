<?php

	session_start();
	
	if(!isset($_POST['category']) || !isset($_POST['amount']) || !isset($_POST['date']))
	{
		header("Location: addIncome.php");
		exit();
	}
	
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
			$userId = $_SESSION['id'];
			
			$categoryId = $_POST['category'];
			$amount = $_POST['amount'];
			$date = $_POST['date'];
			$comment = $_POST['comment'];
					
			if(!is_numeric($amount))
			{
				$_SESSION['addTransferErrorMsg'] = 'Amount must be numer.';
				header('Location: addIncome.php');
			}
						
			if(!is_string($comment))
			{
				$_SESSION['addTransferErrorMsg'] = 'Category must be text value.';
				header('Location: addIncome.php');
			}
			
			
			if($connection->query("INSERT INTO incomes (user_id, incomes_category_assigned_to_user_id, amount, date_of_income, income_comment) VALUE ('$userId', '$categoryId', '$amount', '$date', '$comment')"))
			{
				$_SESSION['addedTransfer'] = True;
				header('Location: addIncome.php');
			}
			else
			{
				throw new Exception($connection->error);
			}
			
			$connection->close();
		}
	}
	catch(Exception $e)
	{
		$_SESSION['dbConnectionErrorMsg'] = "Error: ".$connection->connect_errno;
		$_SESSION['dbConnectionErrorMsg_devInfo'] = "Developer's info: ".$e;
		header('Location: addIncome.php');
	}

?>