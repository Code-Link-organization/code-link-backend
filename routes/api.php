<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\SignupController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Home\PostController;
use App\Http\Controllers\Api\Home\CommentController;
use App\Http\Controllers\Api\Home\LikesController;
use App\Http\Controllers\Api\Home\ShareController;
use App\Http\Controllers\Api\Individuals\ProfileController;
use App\Http\Controllers\Api\Individuals\UserController;
use App\Http\Controllers\Api\Teams\TeamController;
use App\Http\Controllers\Api\Teams\JoinRequestController;
use App\Http\Controllers\Api\Teams\InviteRequestController;
use App\Http\Controllers\Api\Dashboard\TrackController;
use App\Http\Controllers\Api\Dashboard\MentorController;
use App\Http\Controllers\Api\Dashboard\CourseController;
use App\Http\Controllers\Api\Dashboard\CommunityController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\NotificationController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// --------------------------------- Auth Controllers -----------------------------------------

Route::prefix('user')->group(function () {

    Route::post('/signup', SignupController::class); //guest

    Route::group(['controller' => EmailVerificationController::class], function () {
        Route::post('/send-mail', 'sendEmail'); 
        Route::post('/check-code', 'verifyEmail');
    });

    Route::controller(LoginController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/logout', 'logout'); //auth
        });

        Route::post('/login', 'login'); //guest
    });

});

Route::group(['prefix'=>'user','controller'=>ResetPasswordController::class],function(){
    Route::post('/reset-password','resetPassword')-> middleware('auth:sanctum'); //auth
});

// --------------------------------- Team Controller ------------------------------------------

Route::group(['prefix' => 'teams', 'middleware' => ['auth:sanctum'], 'controller' => TeamController::class], function () {
    Route::get('/', 'index');
    Route::get('/show/{id}', 'showTeam');
    Route::post('/create', 'storeTeam');
    Route::post('/edit/{id}', 'updateTeam');
    Route::post('/delete/{id}', 'destroyTeam');
    Route::post('/leave/{teamId}', 'leaveTeam');
    Route::post('/remove-member/{teamId}/{userId}', 'removeMember'); //Leader only can remove member
    Route::get('/user-teams', 'showUserTeams');
    Route::get('/leader-teams', 'showLeaderTeams');
});

// --------------------------------- JoinRequest Controller -----------------------------------

Route::group(['prefix' => 'join-requests', 'middleware' => ['auth:sanctum'],'controller' => JoinRequestController::class], function () {
    Route::get('/', 'getUserJoinRequests');
    Route::get('/{teamId}', 'getLeaderJoinRequests');
    Route::post('/join/{teamId}', 'joinTeam');

    // Accept and reject join requests - Leader
    Route::post('/accept-join/{id}', 'acceptJoinRequest');
    Route::post('/reject-join/{id}', 'rejectJoinRequest');
    
      // Remove Request
      Route::post('/remove-join-request/{id}', 'removeJoinRequest'); //User
});

// --------------------------------- InviteRequest Controller -----------------------------------

Route::group(['prefix' => 'invite-requests', 'middleware' => ['auth:sanctum'],'controller' => InviteRequestController::class], function () {
    Route::get('/', 'getInviteRequests');
    Route::post('/invite/{teamId}', 'inviteTeam');

    // Accept and reject invite requests - User
    Route::post('/accept-invite/{id}', 'acceptInviteRequest');
    Route::post('/reject-invite/{id}', 'rejectInviteRequest');

      // Remove Request
      Route::post('/remove-invite-request/{id}','removeInviteRequest'); //Leader
});

// --------------------------------- Post Controller ---------------------------------------------

Route::group(['prefix' => 'posts', 'middleware' => ['auth:sanctum'],'controller' => PostController::class], function () {
    Route::get('/', 'getPosts');
    Route::post('/create', 'createPost');
    Route::get('/show/{id}', 'showPost');
    Route::post('/edit/{id}', 'editPost');
    Route::post('/delete/{id}', 'deletePost');
    Route::get('/user/{id}', 'getUserPosts');
});

// --------------------------------- Comment Controller ------------------------------------------

Route::group(['prefix' => 'posts/{post}/comments', 'middleware' => ['auth:sanctum'],'controller' => CommentController::class], function () {
    Route::get('/', 'showAllComments');
    Route::post('/create', 'createComment');
    Route::post('/edit/{comment}', 'editComment');
    Route::get('/delete/{comment}', 'deleteComment');
});
// --------------------------------- Likes Controller ------------------------------------------

Route::group(['prefix' => 'posts/{post}', 'middleware' => ['auth:sanctum'],'controller' => LikesController::class], function () {
    Route::get('/likes', 'getLikesForPost');
    Route::post('/like', 'likePost');
});
// --------------------------------- Share Controller ------------------------------------------

Route::group(['middleware' => ['auth:sanctum'],'controller' => ShareController::class], function () {
    Route::post('posts/{post}/share', 'sharePost');
    Route::post('shares/{share}', 'removeShare');
});

// --------------------------------- Profile Controller -------------------------------------------

Route::group(['prefix' => 'profile', 'middleware' => ['auth:sanctum'], 'controller' => ProfileController::class], function () {
    Route::post('/edit-profile/{id}', 'updateProfile');
    Route::post('/edit-info/{id}', 'updatePersonalInfo');
    Route::post('/delete-account/{id}', 'destroyAccount');
});

// --------------------------------- User Controller -------------------------------------------

Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum'], 'controller' => UserController::class], function () {
    Route::get('/', 'getAllUsers');
    Route::get('/show/{id}', 'getUserById');
});


// --------------------------------- Track Controller ------------------------------------------

Route::group(['prefix' => 'tracks', 'middleware' => ['auth:sanctum'], 'controller' => TrackController::class], function () {
    Route::get('/', 'index');
    Route::post('/create', 'createTrack');
    Route::get('/show/{id}', 'showTrack');
    Route::post('/edit/{id}', 'editTrack');
    Route::post('/delete/{id}', 'destroyTrack');
});

// --------------------------------- Search Controller ------------------------------------------

Route::group(['prefix' => 'search', 'middleware' => ['auth:sanctum'], 'controller' => SearchController::class], function () {
    Route::get('/post/{id}', 'searchPost');
    Route::get('/team//{id}', 'searchTeam');
    Route::get('/mentor/{id}', 'searchMentor');
    Route::get('/community/{id}', 'searchCommunity');
    Route::get('/userprofile/{id}', 'searchUesrprofile');
    Route::get('/myprofile/{id}', 'searchMyprofile');
    Route::get('/chat/{id}', 'searchChat');
});

// --------------------------------- Mentor Controller ------------------------------------------

Route::group(['prefix' => 'mentors', 'middleware' => ['auth:sanctum'], 'controller' => MentorController::class], function () {
    Route::get('/', 'index');
    Route::post('/create', 'createMentor');
    Route::get('/show/{id}', 'showMentor');
    Route::post('/edit/{id}', 'editMentor');
    Route::post('/delete/{id}', 'destroyMentor');
});

// --------------------------------- Course Controller ------------------------------------------

Route::group(['prefix' => 'courses', 'middleware' => ['auth:sanctum'], 'controller' => CourseController::class], function () {
    Route::get('/', 'index');
    Route::post('/create', 'createCourse');
    Route::get('/show/{id}', 'showCourse');
    Route::post('/edit/{id}', 'editCourse');
    Route::post('/delete/{id}', 'destroyCourse');
});

// --------------------------------- Community Controller ------------------------------------------

Route::group(['prefix' => 'communities', 'middleware' => ['auth:sanctum'], 'controller' => CommunityController::class], function () {
    Route::get('/', 'index');
    Route::post('/create', 'createCommunity');
    Route::get('/show/{id}', 'showCommunity');
    Route::post('/edit/{id}', 'editCommunity');
    Route::post('/joinCommunity/{id}', 'joinCommunity');
    Route::post('/leaveCommunity/{id}', 'leaveCommunity');
    Route::post('/sendInvitation/{id}', 'sendInvitation');
    Route::post('/delete/{id}', 'destroyCommunity');
});

// --------------------------------- Notification Controller ------------------------------------------
Route::group(['prefix' => 'notifications', 'middleware' => ['auth:sanctum'], 'controller' => NotificationController::class], function () {
    Route::get('/team-requests', 'getUserNotifications');
});
