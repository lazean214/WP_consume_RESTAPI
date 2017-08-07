# WP CONSUME REST API

HOW TO USE

<?php consumeRestAPI(PARAMETER , ENDPOINT) ?>


# INPUTS
'''
API TOKEN: YOURAPITOKENHERE
end_point_1: https://yourdomain/api/endpoint1/
end_point_2: https://yourdomain/api/endpoint1/
end_point_3: https://yourdomain/api/endpoint1/
end_point_4: https://yourdomain/api/endpoint1/
end_point_5: https://yourdomain/api/endpoint1/
'''
# SAMPLE
> $data = consumeRestAPI('parameter', end_point_1);
 
# cURL
'''
$authorization = "Authorization: Bearer YOURAPITOKENHERE";
$url = 'https://yourdomain/api/endpoint1/' .$parameter;
$cURL = curl_init();
curl_setopt($cURL, CURLOPT_URL, $url);
curl_setopt($cURL, CURLOPT_HTTPGET, true);
curl_setopt($cURL, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Accept: application/json', $authorization,
		));

$result = curl_exec($cURL);
curl_close($cURL);
$json = json_decode($result);
return $json;
'''
Feel free to modify the code as you seem fit.
 
 





