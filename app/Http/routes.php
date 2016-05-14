<?php

Route::get( '/', function () {
		return view( 'app' );
} );

Route::post( 'oauth/access_token', function () {
		return Response::json( Authorizer::issueAccessToken() );
} );

Route::group( [ 'middleware' => 'oauth' ], function () {

		//Rota para os usuarios
		Route::get( '/user/authenticated', [ 'as' => 'user.authenticated', 'uses' => 'UserController@authenticated' ] );

		//Rota para os clientes
		Route::get( '/clients', [ 'as' => 'clients.show', 'uses' => 'ClientController@index' ] );
		Route::group( [ 'prefix' => 'clients' ], function () {
				Route::post( '/', [ 'as' => 'client.create', 'uses' => 'ClientController@store' ] );
				Route::get( '/{id}', [ 'as' => 'client.show', 'uses' => 'ClientController@show' ] );
				Route::put( '/{id}', [ 'as' => 'client.update', 'uses' => 'ClientController@update' ] );
				Route::delete( '/{id}', [ 'as' => 'client.delete', 'uses' => 'ClientController@destroy' ] );

		} );
		//Rota lista projetos
		Route::get( '/projects', [ 'as' => 'projects.show', 'uses' => 'ProjectController@index' ] );

		Route::group( [ 'prefix' => 'projects' ], function () {
				//Rota para os projetos
				Route::post( '/', [ 'as' => 'project.create', 'uses' => 'ProjectController@store' ] );
				Route::get( '/{id}', [ 'as' => 'project.show', 'uses' => 'ProjectController@show' ] );
				Route::put( '/{id}', [ 'as' => 'project.update', 'uses' => 'ProjectController@update' ] );
				Route::delete( '/{id}', [ 'as' => 'project.delete', 'uses' => 'ProjectController@destroy' ] );

				//Rota para as notas
				Route::get( '{projectId}/notes', [ 'as' => 'project.notes.show', 'uses' => 'ProjectNoteController@index' ] );
				Route::group( [ 'prefix' => 'notes' ], function () {
						Route::post( '{projectId}', [ 'as' => 'project.note.create', 'uses' => 'ProjectNoteController@store' ] );
						Route::get( '{noteId}', [ 'as' => 'project.note.show', 'uses' => 'ProjectNoteController@show' ] );
						Route::put( '/{projectId}/notes/{idNote}', [ 'as' => 'project.note.update', 'uses' => 'ProjectNoteController@update' ] );
						Route::delete( '/{projectId}/notes/{idNote}', [ 'as' => 'project.note.delete', 'uses' => 'ProjectNoteController@destroy' ] );
				} );

				//Rotas para a tasks
				Route::get( '{id}/tasks', [ 'as' => 'project.tasks.show', 'uses' => 'ProjectTaskController@index' ] );
				Route::group( [ 'prefix' => 'task' ], function () {
						Route::post( '/{projectId}', [ 'as' => 'project.task.create', 'uses' => 'ProjectTaskController@store' ] );
						Route::get( '/{taskId}/{projectId}', [ 'as' => 'project.task.show', 'uses' => 'ProjectTaskController@show' ] );
						Route::put( '/{taskId}/{projectId}', [ 'as' => 'project.task.update', 'uses' => 'ProjectTaskController@update' ] );
						Route::delete( '/{taskId}/{projectId}', [ 'as' => 'project.task.delete', 'uses' => 'ProjectTaskController@destroy' ] );
				} );

				//Rotas para members
				Route::get( '/{idProject}/members', [ 'as' => 'project.members.show', 'uses' => 'ProjectController@showMembers' ] );
				Route::group( [ 'prefix' => 'member' ], function () {
						Route::post( 'project/{idProject}', [ 'as' => 'project.member.add', 'uses' => 'ProjectController@addMember' ] );
						Route::delete( 'project/{idProject}/user/{idUser}', [ 'as' => 'project.member.delete', 'uses' => 'ProjectController@removeMember' ] );
						Route::get( 'project/{idProject}/user/{idUser}', [ 'as' => 'project.member.ismember', 'uses' => 'ProjectController@isMember' ] );
				} );

				//Rota para arquivos
				Route::get( '{id}/file', [ 'as' => 'project.file.list', 'uses' => 'ProjectFileController@index' ] );
				Route::get( 'file/{fileId}', [ 'as' => 'project.file.show', 'uses' => 'ProjectFileController@show' ] );
				Route::get( 'file/{fileId}/download', [ 'as' => 'project.file.download', 'uses' => 'ProjectFileController@showFile' ] );
				Route::post( '{id}/file', [ 'as' => 'project.file.add', 'uses' => 'ProjectFileController@store' ] );
				Route::put( 'file/{fileId}', [ 'as' => 'project.file.edit', 'uses' => 'ProjectFileController@update' ] );
				Route::delete( 'file/{fileId}', [ 'as' => 'project.file.delete', 'uses' => 'ProjectFileController@destroy' ] );
		} );

} );


/*****************************
 *  TESTE UNITÁRIOS
 *****************************/
Route::get( '/ola', function () {
		return 'Olá mundo!';
} );

//Desabilitar o CSRF para funcionar o teste (arquivo Kernel) mais neste curso já foi desabilitado
Route::post( '/post', function ( \Illuminate\Http\Request $request ) {
		$name  = $request->name;
		$idade = $request->idade;
		$email = $request->email;
		return response()->json( compact( 'name', 'idade', 'email' ) );
} );