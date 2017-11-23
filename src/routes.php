<?php
	Route::group(['prefix' => config('adminamazing.path').'/accesslog', 'middleware' => ['web','CheckAccess']], function() {
		Route::get('/', 'selfreliance\AccessLog\AccessLogController@index')->name('AdminAccessLog');
		Route::post('/delete', 'selfreliance\AccessLog\AccessLogController@destroy')->name('AdminAccessLogDestroy');
	});