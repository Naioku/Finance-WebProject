<?php
	
	$logInErrorMsg = '<div style = "color: #a20d0d; text-align: center; margin-top: 10px;">Invalid login or password!</div>';
	$dbConnectionErrorMsg = "Error: ".$connection->connect_errno;
	
	session_start();
	
	if(!isset($_POST['login']) || !isset($_POST['password']))
	{
		header("Location: index.php");
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
			$login = $_POST['login'];
			$password = $_POST['password'];
			
			$login = htmlentities($login, ENT_QUOTES, "UTF-8");
			
			if($result = @$connection->query(sprintf(
				"SELECT * FROM users WHERE login = '%s'",
				mysqli_real_escape_string($connection, $login))))
			{
				$howManyUsers = $result->num_rows;
				if($howManyUsers > 0)
				{
					$row = $result->fetch_assoc();
					if(password_verify($password, $row['pass']))
					{	
						$_SESSION['loggedIn'] = true;
						
						$_SESSION['id'] = $row['id'];
						$_SESSION['login'] = $row['login'];
						
						if($row['name'] != NULL)
						{
							$_SESSION['name'] = $row['name'];
						}
						if($row['surname'] != NULL)
						{
							$_SESSION['surname'] = $row['surname'];
						}
						
						
						unset($_SESSION['logInErrorMsg']);
						$result->close();
						header('Location: mainMenu.php');
					}
					else
					{
						$_SESSION['logInErrorMsg'] = $logInErrorMsg;
						header('Location: logIn.php');
					}
				}
				else
				{
					$_SESSION['logInErrorMsg'] = $logInErrorMsg;
					header('Location: logIn.php');
				}
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
		$_SESSION['dbConnectionErrorMsg'] = $dbConnectionErrorMsg;
		$_SESSION['dbConnectionErrorMsg_devInfo'] = "Developer's info: ".$e;
	}
	
?>