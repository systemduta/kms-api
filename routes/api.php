<?php

use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CrossfunctionController;
use App\Http\Controllers\Api\JadwalVhsController;
use App\Http\Controllers\Api\LampiranController;
use App\Http\Controllers\Api\MateriVHsController;
use App\Http\Controllers\Api\SOPController;
use App\Http\Controllers\Api\ZoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
|
|
*/

/**
 * PENJELASAN FUNGSI MASING MASING ROUTE ADA DI DALAM CONTROLLER 
 * SILAHKAN BUKA DI app/Http/COntrollers/Api/namacontroller
 * 
 * contoh: 
 *  Route::post('login', 'UserController@login');  -> berarti nama controller nya adalah UserController dengan method login
 * 
 * Cara membuat user menjadi admin => buka file CARA UPDATE ADMIN.txt
 */

Route::group([
    'namespace' => 'Api',
], function () {
    /**PAS maesa one */
    Route::post('user_pas','Pas\GetPasController@index');

    /**PAS maesa one */
    
    //mengatur login user web
    Route::post('login', 'UserController@login');
    //mengatur login user dari mobile
    Route::post('login_mobile', 'MobileController@login_mobile');

    //prefix digunakan untuk menambah "/web" di url api (untuk memisahkan antara web dan mobile)
    Route::group([
        'prefix' => 'web'
    ], function () {
        //set permisssion user
        Route::resource('permission','PermissionController');
        Route::get('permissionAll','PermissionController@showAll');
        //untuk menambahkan user
        Route::post('register', 'UserController@register');
        //untuk memperoleh data perusahaan saat ini
        Route::get('get_company', 'CompanyController@index');
        //unutk memperoleh data divisi/organization saat ini
        Route::get('get_organization', 'OrganizationController@index');
        // Route::resource('books','BookController');  ini blm digunakan

        //download-file
        Route::get('download1', 'DownloadController@download1');


        /**middleware digunakan untuk pembatas. yang berarti hanya admin yang sudah login yang dapat masuk ke route ini 
         **/
        Route::group(['middleware' => 'auth:api'], function () {
            // Route::resource('setadmin','SetAdminController');

            //uservhscontroller 
            Route::resource('uservhs', 'UserVhsController');
            Route::get('listuser/{id}', 'UserVhsController@listUser');
            Route::put('updatelistuser/{id}', 'UserVhsController@updateList');
            Route::get('detailuser/{id}', 'UserVhsController@detailUser');

            //jadwaluservhs //NOTE blm ada perubahan query
            Route::resource('jadwalvhsuser', 'JadwalUserVhsController');
            Route::get('getcompany', 'JadwalUserVhsController@getCompany');
            Route::get('indexpermit/{id}', 'JadwalUserVhsController@indexpermit');
            Route::post('showuser', 'JadwalUserVhsController@showUser');
            Route::post('setuser', 'JadwalUserVhsController@setUser');
            Route::get('indexdetail/{id}', 'JadwalUserVhsController@indexDetail');
            Route::get('showdetail/{id}', 'JadwalUserVhsController@showSingle');

            //quota admin anak perusahaan
            Route::resource('jadwaladminap', 'JadwalAdminAPController');
            Route::get('showusercom/{id}', 'JadwalAdminAPController@showUser');

            //quotaaps admin pusat
            Route::resource('quotaap', 'QuotaapController');
            Route::get('getJadwal/{id}', 'QuotaapController@getJadwal');
            Route::get('getAll/{id}', 'QuotaapController@getAll');
            Route::put('updateSingle/{id}', 'QuotaapController@singleUpdate');

            Route::get('get_user', 'UserController@index');    //get list user
            Route::get('detail_admin', 'UserController@details');
            Route::get('detail_user/{id}', 'UserController@detailsUser');
            Route::put('update_user/{id}', 'UserController@update');
            Route::delete('delete_user/{id}', 'UserController@delete');
            Route::post('logout', 'UserController@logout');
            Route::post('updatepass','UserController@resetPassword');
            // Route::get('organization/list_by_company', 'OrganizationController@get_organization_by_company');

            //division
            Route::resource('organization', 'OrganizationController');
            Route::get('organizationcompany', 'OrganizationController@organization_company');

            //companies
            Route::resource('companies', 'CompanyController');
            Route::get('getcompany/{id}', 'CompanyController@getCompany');
            Route::post('getdetailcompany', 'CompanyController@getDetail');

            Route::get('detail_event/{id}', 'EventController@detailsEvent');
            Route::get('detail_course/{id}', 'CourseController@detailsCourse');
            Route::get('get_question/{id}', 'TestController@index');

            Route::post('store_question', 'TestController@store');

            Route::post('store_cross', 'CrossfunctionController@store');
            Route::get('getcross/{id}', 'CrossfunctionController@index');
            Route::delete('delete_cross/{id}', 'CrossfunctionController@destroy');

            Route::delete('delete_question/{id}', 'TestQuestionController@destroy');
            Route::get('detail_question/{id}', 'TestQuestionController@show');
            Route::put('update_question/{id}', 'TestQuestionController@update');

            Route::delete('delete_answer/{id}', 'TestAnswerController@destroy');
            Route::put('update_answer/{id}', 'TestAnswerController@update');
            Route::get('detail_answer/{id}', 'TestAnswerController@show');

            Route::get('get_book', 'BookController@index');
            Route::get('detail_book/{id}', 'BookController@show');
            Route::post('create', 'BookController@store');
            Route::put('update_book/{id}', 'BookController@update');
            Route::delete('delete_book/{id}', 'BookController@destroy');

            // Route::get('get_sop', 'Api/SOPController@index');
            // Route::post('create_sop', 'SOPController@store');

            //sop
            Route::resource('sop', 'SOPController');
            Route::get('get_sop', 'SOPController@sop');
            Route::get('sop_status/{id}', 'SOPController@status');
            Route::get('getsopcompany', 'SOPController@getall');
            Route::get('getsoporganization/{id}', 'SOPController@getOrg');

            //course
            Route::resource('lampiran', 'LampiranController');
            Route::get('lamp_status/{id}', 'LampiranController@status');
            Route::get('showscore/{id}','CourseController@showscore');

            Route::resource('crossfunction', 'CrossfunctionController');
            Route::get('cross_status/{id}', 'CrossfunctionController@status');


            Route::get('golongan/list_by_company', 'GolonganController@get_golongan_by_company');
            Route::resource('golongan', 'GolonganController');
            Route::resource('course', 'CourseController');

            //newcourse
            Route::get('newIndexCourse','CourseController@newIndex');
            Route::get('newAllCourse/{id}','CourseController@allcourse');
                //softskill
            Route::post('addsoftskill','CourseController@storesofskill');
            Route::get('detailsoftskill/{id}', 'CourseController@detailssoftskill');
            Route::get('showsoftskill/{id}', 'CourseController@showsoftskill');
            Route::put('updatesoftskill/{id}', 'CourseController@updatesoftskill');

            //download
            Route::get('downcourse/{id}', [CourseController::class, 'coursedown']);
            Route::get('downsop/{id}', [SOPController::class, 'sopdown']);
            Route::get('downcf/{id}', [CrossfunctionController::class, 'cfdown']);
            Route::get('downlamp/{id}', [LampiranController::class, 'lampdown']);
            Route::get('downmateri/{id}', [MateriVHsController::class, 'downloadfile']);

            Route::get('leaderboard/exam_result', 'LeaderboardController@exam_result');
            Route::resource('leaderboard', 'LeaderboardController');
            Route::resource('event', 'EventController');
            Route::resource('test', 'TestController');
            Route::resource('vhs', 'VhsController');
            Route::resource('splash_screen', 'SplashScreenController');


            // Route::resource('jadwal','JadwalVhsController');
            Route::get('get_jadwal', [JadwalVhsController::class, 'sop_all']);
            Route::post('store_jadwal', [JadwalVhsController::class, 'store']);
            Route::delete('jadwal/{id}', [JadwalVhsController::class, 'destroy']);
            Route::get('show_jadwal/{id}', [JadwalVhsController::class, 'show']);
            Route::put('update_jadwal/{id}', [JadwalVhsController::class, 'update']);
            Route::get('copyjadwal/{id}', 'JadwalVhsController@copyJadwal');

            //zoom
            Route::resource('zoom', 'ZoomController');
            Route::put('update_zoom/{id}', 'ZoomController@update');
            Route::get('getvhs', [ZoomController::class, 'getvhs']);

            //materivhs
            Route::resource('materivhs', 'MateriVHsController');
            Route::put('update_materi/{id}', 'MateriVHsController@update');
            Route::get('materidetail/{id}', 'MateriVHsController@indexSplit');

            //questionvhs
            Route::resource('questionvhs', 'QuestionVhsController');
            Route::get('listmateri', 'QuestionVhsController@listMateriVhs');
            Route::get('indexmat/{id}', 'QuestionVhsController@indexMateri');
            Route::get('indexdet/{id}', 'QuestionVhsController@indexDetail');

            //answervhs
            // Route::resource('answervhs','AnswerVhsController');
            // Route::get('getanswervhs/{id}','AnswerVhsController@getAnswer');
            // Route::get('getsingleanswer/{id}','AnswerVhsController@getSingleAnswer');

            //userscorevhs
            Route::resource('userscorevhs', 'UserScoreVhsController');
            Route::get('materi_sc/{id}', 'UserScoreVhsController@materi');
            Route::get('quest_sc/{id}', 'UserScoreVhsController@question');
            Route::post('answer_sc', 'UserScoreVhsController@answer');
            // Route::get('getuserpercompany/{id}','UserScoreVhsController@getUserPerCompany');

            //dashboard            
            Route::resource('dashbrd', 'DashboardController');

            //certi_vhs
            Route::resource('vhscerti', 'VhsCertiController');

            //deletOrg
            Route::get('deleteorg/{id}', 'OrganizationController@deleteOrg');

            //profile
            Route::resource('profile', 'ProfileAdminController');
            Route::put('updateprofile', 'ProfileAdminController@updateself');

            //message
            Route::resource('message','MessageController');

            /************* Awal part PAS ******************/
              /** setting pas */
                //pas master controller
                        Route::get('pas_master_3p','Pas\MasterController@index_3p');
                        Route::get('pas_master_company','Pas\MasterController@index_company');
                        Route::get('pas_master_division/{id}','Pas\MasterController@index_division');
                        Route::post('pas_master_employee','Pas\MasterController@index_employee');
                        Route::post('pas_master_index','Pas\MasterController@index');
                        Route::get('pas_master_all_dimensis','Pas\MasterController@all_dimensi');
                        Route::get('pas_master_all_kpi','Pas\MasterController@all_kpi');
                        Route::get('pas_master_all_ind','Pas\MasterController@all_ind');
                        Route::post('pas_getkpi','Pas\MasterController@getKpi');

                //pas dimensi
                    //People
                        Route::resource('pas_dimensi','Pas\Pengaturan\People\DimensiController');
                        Route::get('index_per_3p/{id}','Pas\Pengaturan\People\DimensiController@index_per_3p');
                    
                    //Process
                        Route::resource('pas_process_dimensi','Pas\Pengaturan\Process\DimensiController');
                        Route::get('index_process_per_3p/{id}','Pas\Pengaturan\Process\DimensiController@index_per_3p');

                    //Performance
                        Route::get('index_performance_per_3p/{id}','Pas\Pengaturan\Performance\DimensiController@index_per_3p');
                        Route::resource('pas_performance_dimensi','Pas\Pengaturan\Performance\DimensiController');
                
                //pas kpi
                    //People
                        Route::resource('pas_kpi','Pas\Pengaturan\People\KPIController');
                        Route::get('index_per_dimensi/{id}','Pas\Pengaturan\People\KPIController@index_per_dimensi');
                    
                    //Process
                        Route::resource('pas_process_kpi','Pas\Pengaturan\Process\KPIController');
                        //index_per_company&divisi
                        Route::post('index_process_kpi','Pas\Pengaturan\Process\KPIController@index');
                    //performance
                        Route::resource('pas_performance_kpi','Pas\Pengaturan\Performance\KPIController');
                        Route::post('index_performance_kpi','Pas\Pengaturan\Performance\KPIController@index');

                //pas indikator Penilaian
                    //People
                        Route::resource('pas_indpenilaian','Pas\Pengaturan\People\IndPenilaianController');
                        Route::get('index_per_kpi/{id}','Pas\Pengaturan\People\IndPenilaianController@index_per_kpi');
                    
                    //Process
                        Route::resource('pas_process_indpenilaian','Pas\Pengaturan\Process\IndPenilaianController');
                        //index per 3p,kpi,company,divisi
                        Route::post('index_process_indpenilaian','Pas\Pengaturan\Process\IndPenilaianController@index');
                    //performance
                        Route::post('index_performance_indpenilaian','Pas\Pengaturan\Performance\IndPenilaianController@index');
                        Route::resource('pas_performance_indpenilaian','Pas\Pengaturan\Performance\IndPenilaianController');
                        

              /**akhir setting pas */

              /**awal penilaian pas */
                    Route::post('pas_final_insert','Pas\MasterController@finalsave');
                //People 
                    Route::get('pas_people_1','Pas\Penilaian\PeopleController@people');
                    Route::post('pas_people_2','Pas\Penilaian\PeopleController@getInd');
                    Route::post('pas_people_3','Pas\Penilaian\PeopleController@store'); // store data
                    Route::post('pas_people_4','Pas\Penilaian\PeopleController@show'); //show data 
                //Process
                    Route::post('pas_proses_1','Pas\Penilaian\ProcessController@process');
                    Route::post('pas_proses_2','Pas\Penilaian\ProcessController@store');
                //Performance
                    Route::post('pas_performance_1','Pas\Penilaian\PerformanceController@performance');
                    Route::post('pas_performance_2','Pas\Penilaian\PerformanceController@store');
              /**akhir penilaian pas */

              /**awal edit penilaian pas */
                    Route::post('pas_edit_1','Pas\Penilaian\Edit\EditController@show');
                    Route::post('pas_edit_2','Pas\Penilaian\Edit\EditController@update');
                    Route::post('pas_edit_3','Pas\Penilaian\Edit\EditController@delete');
                    Route::post('pas_edit_4','Pas\Penilaian\Edit\EditController@showPerDate'); //untuk liat user pas per bulan
                    Route::post('pas_edit_5','Pas\Penilaian\Edit\EditController@cekData');
              /**akhir edit penilaian pas */

            /************* Akhir part PAS ******************/
        });
    });

    Route::group([
        'prefix' => 'mobile'
    ], function () {
        Route::resource('books', 'BookController');
        Route::get('splash_screen', 'SplashScreenController@index');

        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('firebase_token', 'MobileController@firebase_token');
            Route::get('user_course', 'MobileController@user_course');
            Route::get('detail_user', 'MobileController@details');
            Route::post('logout', 'MobileController@logout');
            // Route::get('course_list','MobileController@course_list'); Sementara ndak di pakai
            Route::post('course_list_dashboard', 'MobileController@course_list_dashboard');

            // Route::post('accept_sop','MobileController@accept_sop');
            Route::get('/sop_status/{id}', 'MobileController@sop_status');
            Route::get('sop_detail/{id}', 'MobileController@sop_detail');
            Route::get('sop_list', 'MobileController@sop_list');
            Route::get('lampiran', 'MobileController@lampiran');

            Route::get('sop_download/{id}', 'MobileController@downFileSop');
            Route::get('lampiran_download/{id}', 'MobileController@downFileLampiran');
            Route::get('cross_download/{id}', 'MobileController@downFileCross');

            Route::get('leaderboards', 'MobileController@leaderboards');
            Route::get('calendar', 'MobileController@get_calendar');
            Route::post('calendar', 'MobileController@post_calendar');
            Route::get('course_detail/{id}', 'MobileController@course_detail');
            Route::post('accept_course', 'MobileController@accept_course');
            Route::post('submit_question', 'MobileController@submit_question');
            Route::post('submit_answer', 'MobileController@submit_answer');
            Route::post('change_password', 'MobileController@change_password');
            Route::get('vhs', 'MobileController@list_vhs');
            Route::get('vhs_dashboard', 'VhsController@index');



            //all
            Route::post('getvhs', 'MobileController@getVhs');
            Route::post('confirmpickip', 'MobileController@confirmPickUp');
            Route::post('getvhsmateri', 'MobileController@getMateri');
            Route::post('getvhsdetail', 'MobileController@getVhsDetail');
            Route::post('getvhsquestion', 'MobileController@getVhsQuestion');
            Route::post('getvhsquestiondetail', 'MobileController@getVhsQuestionDetail');
            Route::post('setanswervhs', 'MobileController@setAnswerVhs');
            Route::post('getotheranswers', 'MobileController@getOtherAnswers');
            Route::post('getvhspending', 'MobileController@getVhsPending');



            //vhs_class
            // Route::post('getvhsclass','MobileController@getVhsClass');
            // Route::post('getvhsclassdetail','MobileController@getVhsClassDetail');
            // Route::post('confirmpickipclass','MobileController@confirmPickUpClass');
            // Route::post('getvhsclassquestion','MobileController@getVhsClassQuestion');
            // Route::post('setanswervhsclass','MobileController@setAnswerVhsClass');
            // Route::post('getvhsclasspending','MobileController@getVhsClassPending');


            //vhs_academy
            // Route::post('getvhsacademy','MobileController@getVhsAcademy');
            // Route::post('getvhsacademydetail','MobileController@getVhsClassDetail');
            // Route::post('confirmpickipacademy','MobileController@confirmPickUpAcademy');
            // Route::post('getvhsacademyquestion','MobileController@getVhsAcademyQuestion');
            // Route::post('setanswervhsacademy','MobileController@setAnswerVhsAcademy');
            // Route::post('getvhsacademypending','MobileController@getVhsAcademyPending');

            //serti vhs
            // Route::get('course_detail/{id}','MobileController@course_detail');
            Route::get('sertifall', 'MobileController@getSerti');
        });
    });
});
