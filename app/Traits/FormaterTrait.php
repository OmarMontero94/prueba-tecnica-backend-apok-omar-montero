<?php
namespace App\Traits;

use NumberFormatter;

trait FormaterTrait {

    public function titleTranslation($id, $lang = "en"){
        $formater = new NumberFormatter( $lang, NumberFormatter::SPELLOUT );
        return $formater->format($id);

    }

}