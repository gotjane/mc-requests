<?php require('check.php');
require_once('settings.php');
include('header.php');

if(!$_SERVER['QUERY_STRING']) {
	?>
	<h1>Error</h1>
	This page should not be accessed directly! Please go back and try something else.
	<?php	
}

elseif($_SERVER['QUERY_STRING']=="deleted") {
	if (!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST") {	    exit("<p>You did not press the submit button; this page should not be accessed directly.</p>");	}
	else {	    $exploits = "/(content-type|bcc:|cc:|document.cookie|onclick|onload|javascript|alert)/i";	    $profanity = "/(beastial|bestial|blowjob|clit|cum|cunilingus|cunillingus|cunnilingus|cunt|ejaculate|fag|felatio|fellatio|fuck|fuk|fuks|gangbang|gangbanged|gangbangs|hotsex|jism|jiz|kock|kondum|kum|kunilingus|orgasim|orgasims|orgasm|orgasms|phonesex|phuk|phuq|porn|pussies|pussy|spunk|xxx)/i";	    $spamwords = "/(viagra|phentermine|tramadol|adipex|advai|alprazolam|ambien|ambian|amoxicillin|antivert|blackjack|backgammon|texas|holdem|poker|carisoprodol|ciara|ciprofloxacin|debt|dating|porn)/i";	    $bots = "/(Indy|Blaiz|Java|libwww-perl|Python|OutfoxBot|User-Agent|PycURL|AlphaServer)/i";	    if (preg_match($bots, $_SERVER['HTTP_USER_AGENT'])) {	        exit("<h1>Error</h1>\nKnown spam bots are not allowed.");	    }	    foreach ($_POST as $key => $value) {	        $value = trim($value);	        if (empty($value)) {	            exit("<h1>Error</h1>\nAll fields are required. Please go back and complete the form.");	        }
	        elseif (preg_match($exploits, $value)) {	            exit("<h1>Error</h1>\nExploits/malicious scripting attributes aren't allowed.");	        }
	        elseif (preg_match($profanity, $value) || preg_match($spamwords, $value)) {	            exit("<h1>Error</h1>\nThat kind of language is not allowed through this form.");	        }	        $_POST[$key] = stripslashes(strip_tags($value));	    }

		$id=$_POST['id'];
		$getdata = mysql_query("SELECT * FROM `$table_mcs` WHERE id='$id'");
		$delete = "DELETE FROM `$table_mcs` WHERE id='$id'";

		if (mysql_query($delete, $connect)) {
			echo "<h1>Success</h1>\n";
			echo "Card deck successfully deleted.";
		}
		else {
			echo "<h1>Error</h1>\n";
			echo "There was an error and the card deck hasn't been deleted.<br />\n";
			die("Error:". mysql_error());
		}
	}
}

else {
	$id=$_GET['id'];
	$getdata = mysql_query("SELECT * FROM `$table_mcs` WHERE id='$id'");
	?>
	<h1>Delete a member card request?</h1>
	<form method="post" action="delete_mcs.php?deleted">
	<input type="hidden" name="id" value="<?php echo "$id"; ?>" />
	Are you sure you want to delete this request? Deleting it does not yet automatically send it to the member! <b>This action can not be undone!</b> Click on the button below to delete the card deck:<br />
	<input type="submit" name="submit" value="Delete!">
	</form>
	<?php
}

include('footer.php'); ?>
