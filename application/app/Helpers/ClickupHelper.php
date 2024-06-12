<?php
namespace App\Helpers;
use Auth;


class ClickupHelper
{

    public static function clickupTicket($title,$priority,$message)
    {
        if ($priority == 'low') {
            $priority = '4';
        }else if($priority == 'medium'){
            $priority = '2';
        }else{
            $priority = '1';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.clickup.com/api/v2/list/901602520265/task',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'
        {
          "name": "'.$title.'",
          "description": "'.$message.'",
          "assignees": [
            183
          ],
           "tags": [
            "support ticket"
          ],
          "status": "backlog",
          "priority":"'.$priority.'",
          "due_date": "'.time().'",
          "due_date_time": false,
          "time_estimate": 8640000,
          "start_date": "'.time().'",
          "start_date_time": false,
          "notify_all": true,
          "parent": null,
          "links_to": null
        }',
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: '.env('API_KEY')
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public static function clickupComment($ticket_id,$message)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.clickup.com/api/v2/task/'.$ticket_id.'/comment',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "comment_text": "'.$message.'",
          "assignee": 183,
          "notify_all": true,
          "custom_task_ids" : true,
          "team_id": 123
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: '.env('API_KEY')
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    private function clickupTaskList()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.clickup.com/api/v2/list/901602520265/task',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.env('API_KEY')
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }   

    public static function ClickupTicketStatusUpdate($ticket_id,$status)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.clickup.com/api/v2/task/'.$ticket_id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'PUT',
          CURLOPT_POSTFIELDS =>'{
            "status": "'.$status.'",
            "archived": false
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: '.env('API_KEY')
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}