<?php

header('Content-type: application/json');

include 'config.php';

$action = strval($_REQUEST['action']);
$username = strval($_REQUEST['username']);
$period = empty($_REQUEST['period']) && strval($_REQUEST['period']) != "0" ? -1 : intval($_REQUEST['period']);
$periodName = strval($_REQUEST['periodName']);
$score = intval($_REQUEST['score']);
$key = strval($_REQUEST['key']);

$username = substr(strval($username), 0, 256);
$score = $score < 0 ? 0 : $score;
$score = $score > 99999 ? 99999 : $score;
$periodName = substr($periodName, 0, 256);

// simple api
switch($action) {
    case 'submit': {
        $hiScore = submit($conn, $username, $score);
        echo json_encode($hiScore);
        break;
    }
    case 'switch-period': {
        // HIT use cronjob everyweek
        // [POST] curl -d "action=switch-period&key=$RESET_KEY" -X POST https://domain./com/api/hjs/hjs.php
        // [GET] curl https://domain./com/api/hjs/hjs.php?action=switch-period&key=$RESET_KEY
        // var_dump($key, $RESET_KEY, $key != $RESET_KEY);
        if ($key != $RESET_KEY) {
            http_response_code(403);
            die();
        }
        $lastPeriod = switchPeriod($conn, $periodName);
        echo json_encode($lastPeriod);
        break;
    }
    case 'get-leaderboard': {
        $data = getLeaderboard($conn, $period, 10);
        echo json_encode($data);
        break;
    }
    default: {
        http_response_code(404);
        die();
        break;
    }
}

function submit($conn, $username, $score) {
    if (empty($username)) {
        http_response_code(400);
        die();
    }
    $period = getLastPeriod($conn);

    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO leaderboard (id, score, period) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE score = GREATEST(score, ?), last_updated = NOW();");
    $stmt->bind_param('siii', $username, $score, $period, $score);
    $stmt->execute();
    $stmt->close();
    
    $hiScore = 0;
    // get latest and return it
    if ($stmt = $conn->prepare('SELECT score FROM leaderboard WHERE period = ? AND id = ?')) {
        $stmt->bind_param('is', $period, $username);
        $stmt->execute();
        
    	$stmt->bind_result($hiScore);
    	$stmt->fetch();

    	$stmt->close();
    }
    
    $lastScore = (object) ["username" => $username, "score" => $hiScore];
    return $lastScore;
}

function getLastPeriod($conn) {
	$period = null;

    if ($stmt = $conn->prepare('SELECT period FROM period WHERE end is null')) {
    	$stmt->execute();

    	$stmt->bind_result($period);
    	$stmt->fetch();

    	$stmt->close();
    }
    
    if (is_null($period)) {
        switchPeriod($conn, '');
        $period = getLastPeriod($conn);
    }

    return $period;
}

function switchPeriod($conn, $periodName) {
    // prepare and bind
    $stmt = $conn->prepare("UPDATE `period` SET `end` = NOW() WHERE `end` is null;");
    $stmt->execute();
	$stmt->close();

	$period = null;

    if ($stmt = $conn->prepare('SELECT period FROM period ORDER BY period DESC')) {
    	$stmt->execute();

    	$stmt->bind_result($period);
    	$stmt->fetch();

    	$stmt->close();
    }
    
    if (is_null($period)) {
        $period = 0;        
    } else {
    	$period++;
    }
    
    $periodName = empty($periodName) ? ('Period ' . $period) : $periodName;
    
    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO period (period, name) VALUES (?, ?)");
    $stmt->bind_param('is', $period, $periodName);
    $stmt->execute();
	$stmt->close();
	
	return $period;
}

function getLeaderboard($conn, $period, $limit) {
    $limit = $limit < 0 ? 0 : $limit;
    if ($period < 0) $period = getLastPeriod($conn);

    if ($stmt = $conn->prepare('SELECT * FROM leaderboard WHERE period = ? ORDER BY score DESC, last_updated DESC')) {
        $stmt->bind_param('i', $period);
    	$stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
    	$stmt->close();
    	
    	return $data;
    }
    
    return array();
}