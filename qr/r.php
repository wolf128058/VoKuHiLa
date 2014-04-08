<?php
/* QR-REDIRECTOR for VoKuHiLa
 * >> Author: Jonas Hess (revier online GmbH & Co.KG) [jonas.hess@revier.de] 
 * >> Function: Use Google Analytics to count the visitor and redirect him to the desired URL
 * >> To Customize:
 *    1. Insert Your Google Analytics Account Number (UA-Number) in this file
 *    2. Customize the URLs in targets.txt
 *       (One single full URL per line, if comment desired start with {mydesiredcommentoncode}http://...)
 *    3. Generate qr-code-image for http://www.domain.com/qr/r.php?nr=23,
 *       where 23 corresponds to the number of the line containing the target in targets.txt (starts with 1)
 *       or redirect to this URL by an htaccess fowarding to use http://www.domain.com/qr1, http://www.domain.com/qr2, ...
 *    4. Feel free to translate the alert text or to customize the redirection text, if you want to.
 *    5. Feel free to add some custom style by CSS... try to keep your design responsive!
 */

	//read target urls from targets.txt
	$lines = file ('targets.txt');
	
	//select desired line from target urls.txt
	$targetURL = $lines[$_GET['nr']*1-1];
    $targetURL = preg_replace("/(\r\n|\n|\r)/", "", $targetURL);
	
	//find comment in URL
	$start = strrpos($targetURL, '{');
	$end   = strrpos($targetURL, '}');
	
	//determine event category
	$eventcat = 'QR-Code-' . $_GET['nr'];
	
	//add comment on event cat if given
	if ($start== 0 && $end!== false)
	{
		$comment   = substr($targetURL, $start+1, $end-1);
		$targetURL = substr($targetURL, $end+1);
		$eventcat .= ' (' . $comment .  ')';
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

			ga('create', 'UA-00000000-0', 'domain.com');
			ga('set', 'anonymizeIp', true);
			ga('require', 'displayfeatures');

			ga('send', {
				'hitType': 'event',
				'eventCategory': '<?php echo $eventcat ?>',
				'eventAction': '<?php echo  $targetURL ?>',
				'eventLabel': 'QR-Code aufgerufen',
				'eventValue': 4,
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
