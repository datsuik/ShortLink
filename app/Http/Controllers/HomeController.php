<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Link;
use Carbon\Carbon;


class HomeController extends Controller {
 

	public function index() {
		$links = Link::latest()->get();

		return view('home', [
			'links' => $links
		]);
	}
	



	public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'link' => 'required|url'
        ], [
            'link.required' => 'Введите ссылку',
            'link.url'      => 'Неверный формат url'
        ]);

        if ($validator->fails()) {
            return redirect('/')->withErrors($validator)->withInput();
        }

        $link = Link::create([
    		'link'           => $request->link,
		 	'token'          => Link::uniqueStr(),
		 	'url_limit'      => $request->limit,
		 	'url_limit_left' => $request->limit,
		 	'url_time'       => $request->time
		]);

		return redirect()->route('home')->with('success', 'Ссылка добавлена');
	}




	public function link($token) {
        $link = Link::where('token', $token)->first();
 
        if ($link) {
	        $maxTime = Carbon::parse($link->created_at)->addHours($link->url_time);

	        if (Carbon::now()->gt($maxTime)) {
				abort(404);
	        }
	        if ($link->url_limit !== 0) {
		        if ($link->url_limit_left < 1) {  
		 			abort(404);
		 		} 	  
		 		$link->url_limit_left--;
	 			$link->save();      	
	        }

	 		return redirect($link->link);
        } 
    }	


}
