<?php


namespace App\Helpers;

use Request;
use App\Models\LogActivity as LogActivityModel;


class LogActivity
{
    public static function addToLog($subject, $id_activite, $page)
    {		
    	$log = [];
    	$log['id_activite'] = $id_activite;
    	$log['page'] = $page;		
    	$log['type'] = 'Événement automatique';
    	$log['subject'] = $subject;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user_agent');
    	$log['user_id'] = auth()->check() ? auth()->user()->id : 1;
    	$log['user_email'] = auth()->check() ? auth()->user()->name : 0;
    	$log['user_societe'] = auth()->check() ? auth()->user()->societe : 0;
    	$log['profil'] = auth()->check() ? auth()->user()->profil : 0;
    	
    	LogActivityModel::create($log);
    }

    public static function logActivityLists()
    {
    	return LogActivityModel::latest()->paginate(30);
    }
}