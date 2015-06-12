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
		<meta name="robots" content="noindex" />
	</head>
	<body>
<?php if (strlen($targetURL)>7) { ?>
			<!-- Google Tag Manager -->
			<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-000000" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<script type="text/javascript">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-000000');</script>
			<!-- End Google Tag Manager -->	
<!--
	TO DO: Event and forward
	NEW: dataLayer.push({'new-variable': 'value'}); 
	[...]
			//TRIGGER EVENT-LOGGING
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
			
			-->
		Einen kleinen Augenblick, es geht gleich weiter zu 
		echo '<a href="' . $targetURL . '">' . $targetURL . '</a>'
<?php } else { ?>	
		Unbekannter QR-Code
<?php } ?>	
	
<?php
  
?>
	</body>
</html>
