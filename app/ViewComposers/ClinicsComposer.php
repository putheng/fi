<?php

namespace App\ViewComposers;

use App\Models\Clinic;
use App\Models\ClinicsService;
use Illuminate\View\View;

class ClinicsComposer
{
	public function compose(View $view)
    {
    	$recommendeds = Clinic::where('is_enabled', 1)->take(10)->orderBy('id', 'desc')->get();

    	$notrecommendeds = Clinic::where('is_enabled', 0)->take(10)->orderBy('id', 'desc')->get();

        $view->with('recommendeds', $recommendeds);

        $view->with('notrecommendeds', $notrecommendeds);

        
        $services = ClinicsService::select('id', 'service_desc_lang1', 'service_desc_lang2')
        	->groupBy('service_desc_lang1')
        	->get();
            
        $view->with('services', $services);

    }
}