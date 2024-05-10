<?php
namespace App\Traits;

use NumberFormatter;
use Carbon\Carbon;

trait FormaterTrait {

    public function titleTranslation($id, $lang = "en"){
        $formater = new NumberFormatter( $lang, NumberFormatter::SPELLOUT );
        return $formater->format($id);

    }

    public function timemezoneShift($timestamp, $timezone = "America/Caracas"){
        if($timezone == "America/Caracas"){
            return $timestamp;
        }
        else{
            return Carbon::parse($timestamp)->timezone($timezone)->toDateTimeString();
        }
        
    }

}