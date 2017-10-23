<?php
	Route::group(['prefix' => config('adminamazing.path').'/accesslog', 'middleware' => ['web','CheckAccess']], function() {
		Route::get('/', 'selfreliance\AccessLog\AccessLogController@index')->name('AdminAccessLog');
		Route::get('/delete/{id}', 'selfreliance\AccessLog\AccessLogController@destroy')->name('AdminAccessLogDestroy');
	});