<?php

	$agents = array(
		"Mozilla/5.0 (Linux; Android 5.0.1; SAMSUNG GT-I9505 Build/LRX22C) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/2.1 Chrome/34.0.1847.76  Safari/537.36",
		"Mozilla/5.0 (Linux; Android 5.0.1; SAMSUNG GT-I9505 Build/LRX22C) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/2.1 Chrome/34.0.1847.76 Mobile Safari/537.36",
		"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0; Touch)"
		
	);

	$regex = array(
		//(?=.*one)(?=.*two)
		"(?=.*ipad|playbook|android)(?=.*mobile)" => "T|Mobile",
		"(?=.*ipad|playbook|android)(?!=.*mobile)" => "T|Tablet",
		"(?=.*MSIE.*Touch)|(?=.*Touch.*MSIE.*)" => "T3|Windows Touch"

	);

	foreach ( $regex as $reg => $outs ) {
		foreach ( $agents as $agent ) {
			//print "Look for <strong>$reg</strong> in <br>$agent <BR>";
			if ( preg_match("/". $reg . "/i", $agent)){
				preg_match_all("/". $reg . "/i", $agent, $match);
				//print_r($match);
				print "!!!!!!!! Found Agent: <BR>$reg / <BR>$outs / <BR>$agent<HR>";
				break;
			
			}
			print "<HR>";
		}
	}

?>