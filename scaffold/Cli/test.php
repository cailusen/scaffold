<?php

include dirname(dirname(__DIR__)) . "/vendor/autoload.php";
use Scaffold\model\WeixinAppInfo;
use Scaffold\model\WeixinFollow;
use Scaffold\model\ChildApp;

$uid = 4;
$fp = fopen('./export.csv', "w");

$followIds = WeixinAppInfo::select('follow_id', [
	"uid" => $uid,
	"LIMIT" => [0, 30],
	"GROUP" => 'follow_id'
]);

$users = WeixinFollow::select('*', [
	"id" => $followIds
]);
foreach ($users as $user) {
	var_dump($user);die;
	fputcsv($fp, [
		'昵称', '子应用数', '最后打开时间', '最后打开应用', '创建时间', '-'
	]);
	$apps = WeixinAppInfo::select('*', [
		'uid' => $uid,
		'follow_id' => $user['id'],
		"ORDER" => ["last_open_time" => "DESC"]
	]);
	fputcsv($fp, [
		$user['nickname'],
		count($apps),
		date("Y-m-d H:i:s", $apps[0]['last_open_time']),
		ChildApp::get('app_name', ['user_id' => $uid, 'appid' => $apps[0]['cid']]),
		date('Y-m-d H:i:s', $user['create_time']),
		''
	]);
	foreach ($apps as $app) {
		fputcsv($fp, [
			'', '子应用名称', '使用时长', '完成进度', '激活时间', '最后打开时间'
		]);
		fputcsv($fp, [
			'',
			ChildApp::get('app_name', ['user_id' => $uid, 'appid' => $apps[0]['cid']]),
			(int)$app['used_time'] . " 分钟",
			$app['use_progress'] . "%",
			date("Y-m-d H:i:s", $app['activate_time']),
			date("Y-m-d H:i:s", $app['last_open_time']),
		]);
	}


}

fclose($fp);
