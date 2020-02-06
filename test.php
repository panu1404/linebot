$ACCESS_TOKEN = 'Fgyd8Tqd8+B814nS7FzXU3Z6sHEqG1nEms8hV9Y06cimeg1RpIqGFLNildgmvVjtszIVZwMAHjwre9YTcABm5LjWVlmGobDBHxJz5xPOaTIfTDAfXQuSeBbIlZAOP9Uf0JvGQHGSuDIVFJ6PGJ24wAdB04t89/1O/w1cDnyilFU='; 
$channelSecret = '0eac42470681b13a7f71f967f7875119';


$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($ACCESS_TOKEN);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello');
$response = $bot->pushMessage('<to>', $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
