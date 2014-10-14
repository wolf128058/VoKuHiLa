<?php

/* QR-REDIRECTOR for VoKuHiLa
 * >> Author: Jonas Hess (revier online GmbH & Co.KG) [jonas.hess@revier.de] 
 * >> Function: Use Google Analytics to count the visitor and redirect him to the desired URL
 */

	//read target urls from matching targets_$kunde_$projekt.txt
	$kunde     = addslashes(urldecode($_GET['kunde']));
	$projekt   = addslashes(urldecode($_GET['projekt']));
	$urlsource = 'targets_' . $kunde .'_' . $projekt. '.txt';
	if(!file_exists($urlsource)) die('URLs zu Kunde / Projekt nicht bekannt.');	

	$lines = file ($urlsource);
	
	//select desired line from target urls.txt
	$targetURL = $lines[$_GET['nr']*1-1];
    $targetURL = preg_replace("/(\r\n|\n|\r)/", "", $targetURL);
	
	//find comment in URL
	$start = strrpos($targetURL, '{');
	$end   = strrpos($targetURL, '}');
	
	//determine event label
	$eventlabel = 'Kunde:' . $kunde . ' | Projekt:' . $projekt . ' | CodeNr:' . $_GET['nr'];
	
	//add comment on event cat if given
	if ($start== 0 && $end!== false)
	{
		$comment   = substr($targetURL, $start+1, $end-1);
		$targetURL = substr($targetURL, $end+1);
		$eventlabel .= ' | Verwendung:' . $comment;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>QR-Code Weiterleitung</title>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript">
<?php if (strlen($targetURL)>7) { ?>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-00000000-0', '<?php echo $_SERVER['HTTP_HOST']?>');
			ga('set', 'anonymizeIp', true);
			ga('set', 'forceSSL', true);
			ga('require', 'displayfeatures');

			ga('send', {
				'hitType': 'event',
				'eventlabelegory': 'QR-Code Aufruf',				
				'eventAction': '<?php echo  $targetURL ?>',
				'eventLabel': '<?php echo $eventlabel ?>',				
				'eventValue': 1,
				'hitCallback': function() {
					window.location.href='<?php echo  $targetURL ?>';
				} 
			});  
<?php } else { ?>	
			alert('Unbekannter QR-Code');
<?php } ?>	
		</script>
		<meta name="robots" content="noindex" />
	</head>
	<body>
	Einen kleinen Augenblick, es geht gleich weiter zu 
<?php
  echo '<a href="' . $targetURL . '">' . $targetURL . '</a>'
?>
	</body>
</html>
