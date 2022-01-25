<?php
use Illuminate\Support\Facades\Storage;

function logWriter($data, $border = null)
{
    $sourceFuncInfo = debug_backtrace()[1];
    $borderStr = '';
    if ($border !== null || isset($border)) {
        Log::info($border);
    }
    if (isset($sourceFuncInfo['class'])) {
        Log::info('Class : ' . $sourceFuncInfo['class'] . ', Function : ' . $sourceFuncInfo['function'] . ', ', [$data]);
    } else {
        Log::info('Function : ' . $sourceFuncInfo['function'] . ', ', [$data]);
    }
    if ($border !== null || isset($border)) {
        Log::info($border);
    }
}

function OptionDataMix_ip($res){
    $data_result = [];
    foreach ($res as $row) {
        array_push($data_result, $row->ip);
    }
    return $data_result;
}

function OptionDataMix_subscriber($res){
    $data_result = [];
    foreach ($res as $row) {
        array_push($data_result, $row->subscriber);
    }
    return $data_result;
}

function OptionDataMix_name($res){
    $data_result = [];
    foreach ($res as $row) {
        array_push($data_result, $row->name);
    }
    return $data_result;
}
