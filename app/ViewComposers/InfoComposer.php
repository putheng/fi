<?php

namespace App\ViewComposers;

use App\Models\Info;
use Illuminate\View\View;

class InfoComposer
{
	public function compose(View $view)
    {
    	$infos = Info::get();

        $view->with('infos', $infos);
    }
}