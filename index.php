<?php

$API_URL = 'https://api.line.me/v2/bot/message';
$ACCESS_TOKEN = 'Fgyd8Tqd8+B814nS7FzXU3Z6sHEqG1nEms8hV9Y06cimeg1RpIqGFLNildgmvVjtszIVZwMAHjwre9YTcABm5LjWVlmGobDBHxJz5xPOaTIfTDAfXQuSeBbIlZAOP9Uf0JvGQHGSuDIVFJ6PGJ24wAdB04t89/1O/w1cDnyilFU='; 
$channelSecret = '0eac42470681b13a7f71f967f7875119';


$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array



if ( sizeof($request_array['events']) > 0 ) {

    foreach ($request_array['events'] as $event) {

        $reply_message = '';
        $reply_token = $event['replyToken'];
//////////////////
         if ( $event['type'] == 'message' ){
               if( $event['message']['type'] == 'text' ){
                   $text = $event['message']['text'];
                   if($text=='debug'){ $reply_message = json_encode($request_array);}else{
                     $reply_message = 'ได้รับข้อความ (i '.$text.') แล้ว'; 
                       if($text=='hi'){
                         	 ////////////
			       $userId = $event['source']['userId'];
			       		$LINEDatas['url'] = "https://api.line.me/v2/bot/profile/".$userId;
			       		$LINEDatas['token']= $ACCESS_TOKEN;
			       	$profile_userid =getLINEProfile($LINEDatas);
			      $reply_message =	getLINEProfile($LINEDatas);
			       foreach ($profile_userid as $data_userid) {
				       // $reply_message = 'สวัสดีคุณ '.$profile_userid['message']['displayName'];
			      		 }
			       
			      
                           //////////////
                       }
                   }
               }
         }
        
        $data = [
            'replyToken' => $reply_token,
            'messages' => [['type' => 'text', 'text' => $reply_message]]
            /// 'messages' => [['type' => 'text', 'text' => json_encode($request_array)]]
        ];
   
////////////////////////
    
        $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

        $send_result = send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);

        echo "Result: ".$send_result."\r\n";
        
    }
}

echo "OK";




function send_reply_message($url, $post_header, $post_body)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
function getLINEProfile($datas)
{
   $datasReturn = [];
   $curl = curl_init();
   curl_setopt_array($curl, array(
     CURLOPT_URL => $datas['url'],
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => "",
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 30,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => "GET",
     CURLOPT_HTTPHEADER => array(
       "Authorization: Bearer ".$datas['token'],
       "cache-control: no-cache"
     ),
   ));
   $response = curl_exec($curl);
   $err = curl_error($curl);
   curl_close($curl);
   if($err){
      $datasReturn['result'] = 'E';
      $datasReturn['message'] = $err;
   }else{
      if($response == "{}"){
          $datasReturn['result'] = 'S';
          $datasReturn['message'] = 'Success';
      }else{
          $datasReturn['result'] = 'E';
          $datasReturn['message'] = $response;
      }
   }
   return $response; ///  $datasReturn
}
/*
{"events":[
{"type":"message",
  "replyToken":"84b22bdde34746bab03d77824092ba05",
	"source":{
		"userId":"Ua00942631a4b6d272f826f2d7c103a00",
		"type":"user"
		},
	"timestamp":1579769380396,
	"mode":"active",
	"message":{
		  "type":"text",
		  "id":"11305435089567",
		  "text":"debug"
		}
	}],
	"destination":"Uaa52dedf207643a40b8a19443e14b1ea"}

{
	"userId":"Ua00942631a4b6d272f826f2d7c103a00",
	"displayName":"^_^ Æ ^_^",
		"pictureUrl":"https://profile.line-scdn.net/0hxBbMt01LJ21fNA8UwiRYOmNxKQAoGiElJ1M4XCo0KVl1BmBrYVNsA3w3cAgiDDc4NAE4DXMwf19y",
		"statusMessage":"!!สัญญา...เราจะเดินจับมือไปด้วยกัน!! ตลอดไป"
	}
*/
?>
