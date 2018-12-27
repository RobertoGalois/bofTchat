<?php require('./ids.php'); ?>
<?php
	if (!(isset($_SESSION['lastMsgLoaded'])))
		session_start();
?>

<?php

	function displayMessages($req)
	{
		$retMessages = '';
		$lastID = 0;
		while ($data = $req->fetch())
		{
			if ($lastID == 0) {
				$lastID = $data['id'];
			}

			$retMessages .= '
			<div class="message'.(($_SESSION['pseudo'] == $data['pseudo'])?(' mine'):('')).'">
			 <div class="authorMessage">
			  '.htmlspecialchars($data['pseudo']).'
			  <span class="dateTimeMessage">('.$data['dateTimePostFormat'].')</span>: 
		     </div>
				 <div class="textMessage">'.str_replace("\n", '<br />', htmlspecialchars($data['message'])).'</div>
			</div>
			';
		}

		if ($lastID != 0) {
			$_SESSION['lastMsgLoaded'] = $lastID;
		}

		return ($retMessages);
	}

	function connectBDD($pLg, $pPw)
	{
		$retBdd = NULL;

		try
		{
			$retBdd = new PDO('mysql:host=localhost;dbname=tchat;charset=utf8', $pLg, $pPw);
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}

		return ($retBdd);
	}

	if (isset($_SESSION['pseudo']))
	{
		$bdd = connectBdd($lg, $pw);
		$req = $bdd->prepare("SELECT id, pseudo, message, DATE_FORMAT(dateTimePost, 'le %d/%m/%Y à %H:%i:%s') AS dateTimePostFormat FROM messages WHERE ID > :lastLoadedID ORDER BY ID DESC");
		$req->execute(Array('lastLoadedID' => (int)($_SESSION['lastMsgLoaded'])));
		$messages =	displayMessages($req);
		$req->closeCursor();
		echo($messages);
		$bdd = NULL;
	}

	else
	{
		//faire des trucs probablement
	}
