<?php
	
	
	Route::group(['prefix' => '/api/v1/ahmadfatoni', 'middleware' => ['throttle:120,1']], function() {
		Route::resource('apigeneratorcontroller', 'ahmadfatoni\apigenerator\controllers\api\PetitionController');
		Route::resource('signaturecontroller', 'ahmadfatoni\apigenerator\controllers\api\signaturecontroller');


		
		Route::post('petitions/{id}/attachments', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@addAttachment');
		Route::resource('signatures', 'ahmadfatoni\apigenerator\controllers\api\SignatureController');
		Route::get('apigeneratorcontroller/{id}/form', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@getpetitionspendingsignatures');
		Route::post('conversations/{model}/start', 'ahmadfatoni\apigenerator\controllers\api\ConversationController@startconversation');
		Route::resource('conversations', 'ahmadfatoni\apigenerator\controllers\api\ConversationController');
		Route::post('conversations/{id}/update', 'ahmadfatoni\apigenerator\controllers\api\ConversationController@updateconversationtaskactivity');
		Route::post('conversations/my', 'ahmadfatoni\apigenerator\controllers\api\ConversationController@myconversation');
		Route::post('apigeneratorcontroller/{id}/sign', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@sign');
		Route::delete('apigeneratorcontroller/{id}', 'ahmadfatoni\apigenerator\Controllers\api\PetitionController@destroy');
    	Route::post('apigeneratorcontroller/{id}', 'ahmadfatoni\apigenerator\Controllers\api\PetitionController@processingPetition');
		Route::resource('apigeneratorcontroller/{id}/signatures', 'ahmadfatoni\apigenerator\controllers\api\SignatureController');
		//Route::get('apigeneratorcontroller/{id}/signatures/count', 'ahmadfatoni\apigenerator\controllers\api\SignatureController@countSignatures');
		Route::get('apigeneratorcontroller/{id}', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@countPetition');
		Route::get('/petitions/search', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@search')->name('petitions.search');
		Route::get('/closedpetitions/search', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@searchClosed')->name('closedpetitions.search');
		Route::get('apigeneratorcontroller/{id}/signature-count', 'ahmadfatoni\apigenerator\controllers\api\SignatureController@countSignatures');
		Route::get('apigeneratorcontroller/{id}/count-week', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@getTotalNumberOfPetitionsPerWeek');
		
    Route::put('apigeneratorcontroller/{id}/reason', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@reason');
	Route::put('apigeneratorcontroller/{id}/action', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@actionRequired');
	Route::post('signaturecontroller/{id}/sign', 'ahmadfatoni\apigenerator\controllers\api\signaturecontroller@sign');
	Route::get('apigeneratorcontroller/{id}/openpetition-count', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@countOpenPetitions');
	Route::get('apigeneratorcontroller/{id}/closedpetition-count', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@countClosedPetitions');
	Route::get('/petitions/{id}', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@show')->name('petition.show');
    Route::get('apigeneratorcontroller/{id}/comments-count', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@showComments');
	Route::get('apigeneratorcontroller/{id}/active-count', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@countActivePetitions');
    Route::get('apigeneratorcontroller/{id}/withdraw-count', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@showWithdraw');
	 Route::get('apigeneratorcontroller/{id}/document', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@retrieveAttachment');
	 Route::get('apigeneratorcontroller/{id}/petition', 'ahmadfatoni\apigenerator\controllers\api\PetitionController@retrievePetition');
	//auth0
	Route::get('google/login', 'ahmadfatoni\apigenerator\controllers\api\GoogleAuthController@redirectToGoogle')->name('google.login');
Route::get('google/callback', 'ahmadfatoni\apigenerator\controllers\api\GoogleAuthController@handleGoogleCallback');

	
	/*Route::get('/apigeneratorcontroller', function () {
		$petitionTitle = 'Test Petition';
		Mail::to('mabailuvhani@gmail.com')->send(new \AhmadFatoni\ApiGenerator\Models\PetitionCreated($petitionTitle));
		return 'Email sent successfully!';
	});*/
		 
	});