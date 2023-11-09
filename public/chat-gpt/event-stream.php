<?php
require_once("../../configs/config.php");
require_once("../../configs/function.php");
checkLogin();

use Orhanerday\OpenAi\OpenAi;

const ROLE = "role";
const CONTENT = "content";
const USER = "user";
const SYS = "system";
const ASSISTANT = "assistant";

$open_ai_key = OPENAI_API_KEY;
$open_ai = new OpenAi($open_ai_key);

$chat_room_id = $_GET['chat_room_id'];



// get latest message of user
$results = $Database->get_row("SELECT * FROM message_chatbot_room where MaRoom = '" . $chat_room_id . "' and Role = 'user' ORDER BY ThoiGian desc limit 1");


$opts = [
    'model' => 'gpt-3.5-turbo',
    'messages' => $history,
    'temperature' => 1.0,
    'max_tokens' => 2000,
    'frequency_penalty' => 0,
    'presence_penalty' => 0,
    'stream' => true
];

header('Content-type: text/event-stream');
header('Cache-Control: no-cache');



$txt = "";
$complete = $open_ai->chat($opts, function ($curl_info, $data) use (&$txt) {

    if ($obj = json_decode($data) and $obj->error->message != "") {
        error_log(json_encode($obj->error->message));
    } else {

        echo $data;
        $clean = str_replace("data: ", "", $data);
        $arr = json_decode($clean, true);
        if ($data != "data: [DONE]\n\n" and isset($arr["choices"][0]["delta"]["content"])) {
            $txt .= $arr["choices"][0]["delta"]["content"];
        }
    }

    echo PHP_EOL;
    ob_flush();
    flush();
    return strlen($data);
});
