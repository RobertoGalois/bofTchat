<?php

	function checkMessage($message)
	{
		$retErrors = [];
		if ($message == '')
		{
			$retErrors[] = 'Vous ne pouvez pas envoyer un message vide';
		}

		return ($retErrors);
	}

?>
