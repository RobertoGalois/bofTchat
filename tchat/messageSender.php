<?php session_start(); ?>
<?php require('./ids.php'); ?>
<?php require('./pseudoChecker.php'); ?>
<?php require('./messageChecker.php') ?>
<?php


	if (isset($_POST['pseudo'])
			&& isset($_POST['message'])
			&& (empty(checkMessage($_POST['message']))))
	{
		if ($_SESSION['pseudo'] == $_POST['pseudo'])
		{
			try
			{
				$bdd = new PDO('mysql:host=localhost;dbname=tchat;charset=utf8', $lg, $pw);
			}
			catch (Exception $e)
			{
   	   			die('Erreur : ' . $e->getMessage());
			}

			$req = $bdd->prepare("INSERT INTO messages(pseudo, message) VALUES(:author, :message)");
			$req->execute(array(
				'author' => $_POST['pseudo'],
				'message' => $_POST['message']
			));
		}

		else
		{
			//somebody's trying to do something reaaaaaly baaad...
		}
	}

	else
	{
		header('location: ./index.php');
	}

	$bdd = NULL;
