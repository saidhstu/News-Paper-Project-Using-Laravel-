<?php

//Laravel and globals
Route::get('/', 'IndexController@index')->name('index');
Auth::routes();

//Account
Route::get('/account', 'AccountController@index')->name('account');
Route::get('/account/modify', 'AccountController@modify')->name('accountModify');
Route::post('/account/modify', 'AccountController@modifySubmit')->name('accountModifySubmit');
Route::get('/account/password', 'AccountController@modifyPassword')->name('accountModifyPassword');
Route::post('/account/password', 'AccountController@modifyPasswordSubmit')->name('accountModifyPasswordSubmit');
Route::get('/account/integration', 'AccountController@integration')->name('accountIntegration');
Route::get('/account/integration/bizzmail', 'IntegrationController@BizzMail')->name('accountIntegrationBizzMail');
Route::post('/account/integration/bizzmail/submit', 'IntegrationController@BizzMailSubmit')->name('accountIntegrationBizzMailSubmit');

//Feeds
Route::get('/feeds', 'FeedsController@index')->name('feeds');
Route::get('/feeds/list', 'FeedsController@feedList')->name('feedsList');
Route::get('/feeds/list/info/{id}', 'FeedsController@feedInfo')->name('feedsInfo');
Route::get('/feeds/add', 'FeedsController@addFeed')->name('feedsAdd');
Route::post('/feeds/add', 'FeedsController@addFeedSubmit')->name('feedsAddSubmit');
Route::get('/feeds/add/parsed/{address}', 'FeedsController@addFeedParsed')->name('feedsAddParsed');
Route::post('/feeds/add/parsed', 'FeedsController@addFeedParsedSubmit')->name('feedsAddParsedSubmit');
Route::get('/feeds/info/public/{id}', 'FeedsController@feedSetPublic')->name('feedInfoPublic');
Route::get('/feeds/delete/{id}', 'FeedsController@deleteFeed')->name('feedsDelete');
Route::get('/feeds/modify/{id}', 'FeedsController@modifyFeed')->name('feedsModify');
Route::post('/feeds/modifySubmit/{id}', 'FeedsController@modifyFeedSubmit')->name('feedsModifySubmit');
Route::get('/feeds/report/{id}', 'FeedsController@reportFeed')->name('feedsReport');

//Home and search page
Route::get('/search/{portal}/{page}/{category?}/{search?}', 'IndexController@search')->name('indexSearch');
Route::post('/integration/bizzmail/submit', 'IntegrationController@BizzMailSearchSubmit')->name('integrationBizzMailSubmit');
Route::get('/feeder/{portal}/{page}/{feeder}', 'IndexController@feeder')->name('indexFeeder');

//Admin pages
Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/admin/category', 'AdminController@category')->name('adminCategory');
Route::post('/admin/category/submit', 'AdminController@categorySubmit')->name('adminCategorySubmit');
Route::get('/admin/category/add', 'AdminController@categoryAdd')->name('adminCategoryAdd');
Route::post('/admin/category/add/submit', 'AdminController@categoryAddSubmit')->name('adminCategoryAddSubmit');
Route::get('/admin/category/info/{id}', 'AdminController@categoryInfo')->name('adminCategoryInfo');
Route::get('/admin/category/delete/{id}', 'AdminController@categoryDelete')->name('adminCategoryDelete');
Route::get('/admin/portal', 'AdminController@portal')->name('adminPortal');
Route::get('/admin/portal/add', 'AdminController@portalAdd')->name('adminPortalAdd');
Route::post('/admin/portal/add/submit', 'AdminController@portalAddSubmit')->name('adminPortalAddSubmit');
Route::get('/admin/portal/info/{id}', 'AdminController@portalInfo')->name('adminPortalInfo');
Route::get('/admin/portal/info/addFeed/{id}', 'AdminController@portalInfoAddFeed')->name('adminPortalInfoAddFeed');
Route::post('/admin/portal/info/addFeed/submit/{id}', 'AdminController@portalInfoAddFeedSubmit')->name('adminPortalInfoAddFeedSubmit');
Route::get('/admin/portal/info/deleteFeed/{portalId}/{feedId}', 'AdminController@portalInfoDeleteFeed')->name('adminPortalInfoDeleteFeed');
Route::get('/admin/bizzfeed/add', 'AdminController@bizzFeedAdd')->name('adminBizzFeedAdd');
Route::get('/admin/users', 'AdminController@users')->name('adminUsers');
Route::get('/admin/users/{id}', 'AdminController@userInfo')->name('adminUserInfo');
Route::post('/admin/user/role/{id}', 'AdminController@userRoleChange')->name('adminUserRoleChange');
Route::get('/admin/user/delete/{id}', 'AdminController@userDelete')->name('adminUserDelete');

//Fake feed articles
Route::get('/admin/fake/add', 'FakeController@add')->name('adminFakeAdd');
Route::post('/admin/fake/add/submit', 'FakeController@addSubmit')->name('adminFakeAddSubmit');
Route::get('/admin/fake/edit/{id}', 'FakeController@edit')->name('adminFakeEdit');
Route::post('/admin/fake/edit/submit/{id}', 'FakeController@editSubmit')->name('adminFakeEditSubmit');
Route::get('/admin/fake/addArticle/{feedId}', 'FakeController@addArticle')->name('adminFakeAddArticle');
Route::post('/admin/fake/addArticle/submit', 'FakeController@addArticleSubmit')->name('adminFakeAddArticleSubmit');
Route::get('/admin/fake/articleManage', 'FakeController@article')->name('fakeFeedArticleManage');
Route::get('/article/{guid}', 'FakeController@article')->name('fakeFeedArticle');
Route::get('/admin/fake/manageArticle/{id?}', 'FakeController@manage')->name('adminFakeEditArticle');
Route::get('/admin/fake/editArticle/{id}', 'FakeController@editArticle')->name('adminFakeEditArticleReal');
Route::post('/admin/fake/editArticleSubmit/{id}', 'FakeController@editArticleSubmit')->name('adminFakeEditArticleSubmit');
Route::get('/admin/fake/deleteArticle/{id}', 'FakeController@deleteArticle')->name('adminFakeEditArticleDelete');
Route::get('/image/fake/editArticleImage/{id}', 'FakeController@editImage')->name('adminFakeEditArticleImage');
Route::get('/admin/fake/deleteArticleImage/{id}', 'FakeController@editArticleImageDelete')->name('adminFakeEditArticleImageDelete');
Route::post('/admin/fake/editArticleImageSubmit/{id}', 'FakeController@editArticleImageSubmit')->name('adminFakeEditArticleImageReal');

//BizzFeed
Route::get('/bizzfeed/creator', 'BizzFeedController@creator')->name('bizzFeedCreator');
Route::post('/bizzfeed/creator/action', 'BizzFeedController@creatorAction');
Route::post('/bizzfeed/items/', 'BizzFeedController@getItems');