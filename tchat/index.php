<?php session_start(); ?> 
<?php require('./includes/header.php'); ?>
<?php require('pseudoChecker.php'); ?>
<?php
	/*
	** S'il y a une tentative de connexion, lancer la connexion
	*/
	if (isset($_POST['pseudo']))
	{
		if (!empty($errors = checkPseudo($_POST['pseudo'])))
		{
			$_SESSION['loginError'] = $errors;
		}
		else 
		{
			$_SESSION['loginError'] = NULL;
			$_SESSION['pseudo'] = $_POST['pseudo'];
		}

		header("location: ./index.php");
	}

	/*
	** Sinon, s'il n'y a pas de session ouverte, proposer le formulaire de login
	**/
	else if (!isset($_SESSION['pseudo']))
	{?>
		<form method="POST" action="./index.php" id="loginForm" class="content">
		 <label for="pseudo">Pseudo: </label><input type="text" name="pseudo" placeholder="Entrez votre pseudo" />
		 <input type="submit" value="Valider">
		 <?php
			if (isset($_SESSION['loginError']))
			{
				$errorsDisplayer = '<div id="loginError">';

				foreach($_SESSION['loginError'] as $value)
					$errorsDisplayer .= '- '.htmlSpecialChars($value).'<br />';

				echo ($errorsDisplayer.'</div>');
			}
		 ?>
		</form>
		<script>
		$(function () {
		 	$("#loginForm input[type=text]").focus();
		});
		</script>

	<?php	
	}

	/*
	** Sinon c'est que c'est logged
	*/
	else
	{
		$encPseudo = htmlspecialchars($_SESSION['pseudo']);
		$_SESSION['lastMsgLoaded'] = 0;	

		?>
		<div id="tchat" class="content">
		 <div id="headerTchat">
		  <form method="GET" action="logOut.php">
		   <span id="loginInfos">Login: <?= $encPseudo ?> </span><input type="submit" value="Déconnexion" />
		  </form>
		 </div>
		 <div id="bodyTchat">
		 <?php
			require('./messageLoader.php');
		 ?>
		 </div>
		 <div id="footerTchat">
		  <textarea name="message" id="contentMessage"></textarea>
		  <div>
		   <button>Envoyer</button>
		  </div>
		 </div>
		</div>
		<script>

		function loadMsgs()
		{
			$.get('./messageLoader.php', null, function (data) {
				var addedEl = $(data);
					addedEl.hide(0);
				$("#bodyTchat").prepend(addedEl);
				addedEl.show(400);
			});
		}

		$(function () {

			const	textareaEl = $("#footerTchat textarea");
			var		shiftDown = false;

		 	$("#contentMessage").focus();
			setInterval(function () {
				loadMsgs();
			}, 1000);

			$("#footerTchat button").click(function (e) {
				e.preventDefault();
				$.post('./messageSender.php', {'pseudo': '<?= $_SESSION['pseudo'] ?>', 'message': textareaEl.val() }, function () {
					loadMsgs();	
				});

				textareaEl.val("");
			});

			textareaEl.keydown(function (e) {
				if (e.which == 16)
					shiftDown = true;

				if ((e.which == 13) && (!shiftDown)) {
					e.preventDefault();
					$("#footerTchat button").trigger('click');
				}
			});

			textareaEl.keyup(function (e) {
				if (e.which == 16) {
					shiftDown = false;
				}
			});
		});
		</script>
		<?php
	}

?>
<?php require('./includes/footer.php'); ?>
