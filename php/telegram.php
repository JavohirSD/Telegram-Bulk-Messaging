<?php
header('Content-Type: application/json; charset=utf-8');
$json[0] = '{
    "ok": false,
    "error_code": 400,
    "description": "Bad Request: chat not found"
}';

$json[1] = '{
    "ok": false,
    "error_code": 403,
    "description": "Forbidden: bot was blocked by the user"
}';

$json[2] = '{
    "ok": true,
    "result": {
        "message_id": 2043,
        "from": {
            "id": 2010521012,
            "is_bot": true,
            "first_name": "GREEN CARD 2023 ✅",
            "username": "DVFormBot"
        },
        "chat": {
            "id": 1993158340,
            "first_name": "Javohir SD",
            "username": "JavohirSD",
            "type": "private"
        },
        "date": 1633192024,
        "text": "1234"
    }
}';

 

echo $json[rand(0,2)];