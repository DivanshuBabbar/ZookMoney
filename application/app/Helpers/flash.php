<?php
use App\Models\Setting;
function general_setting($key='')
{
    $value = '';
    if($key)
    {
        $setting = Setting::where(['key'=>$key])->first();
        $value = isset($setting->value) ? $setting->value:'';
    }
    return $value;
}
function flash($message, $level = 'info'){
	session()->flash('flash_message', $message);
	session()->flash('flash_message_level', $level);
}

if ( ! function_exists('localDate')) {
    /**
     * Format a date to the users local timezone with an optional format
     * @param \Carbon\Carbon|string $date
     * @param string $format
     *
     * @return mixed
     */
    function localDate($date, $format = 'd M Y') {
        if ( ! $date instanceof Carbon\Carbon) {
            $date = new \Carbon\Carbon($date);
        }

        $date->setTimezone('Asia/Kolkata');
        
        return $date->format($format);
    }
}