<?php

$text = $_POST['sentence'];

$text = str_replace(array("\r\n", "\r", "\n"), '', $text);

$keyword_number = 3;
$summary_number = 3;

$minutes_data = array(
	'text' => $text,
	'keywords_num' => $keyword_number,
	'sent_limit' => $summary_number
);

$minutes_url = '' . '?' . http_build_query($minutes_data);

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$minutes_url);
curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$minutes_result_json = curl_exec($ch);
curl_close($ch);

$minutes_result_data = json_decode($minutes_result_json,true);

$send_summary['summary'] = array("");
$send_summary['keywords'] = array("");

if(isset($minutes_result_data['summary']))
{
	foreach((array)$minutes_result_data['summary'] as $value)
	{
		$send_summary['summary'][] = $value;
	}
}
else
{
	for($i=0;$i<3;$i++)
	{
		$send_summary['summary'][] = "null";
	}
}

if( count($send_summary['summary']) == 2 )
{
	for($i=0;$i<2;$i++)
	{
		$send_summary['summary'][] = "null";
	}
}
else if( count($send_summary['summary']) == 3 )
{
	$send_summary['summary'][] = "null";

}

for($i=0;$i<$keyword_number;$i++)
{
	if(isset($minutes_result_data['keywords'][$i]))
	{
		foreach($minutes_result_data['keywords'][$i] as $key => $value)
		{
			$send_summary['keywords'][] = $key;
		}
	}
	else
	{
		$send_summary['keywords'][] = "null";
	}
}

$summary_split = array_splice($send_summary['summary'],0,1);
$keyword_split = array_splice($send_summary['keywords'],0,1);

$send_summary_json = json_encode($send_summary,JSON_UNESCAPED_UNICODE);

echo $send_summary_json;

?>
