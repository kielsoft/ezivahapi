<?php
/**
 * Created by PhpStorm.
 * User: Theophilus
 * Date: 12/10/2018
 * Time: 11:43 PM
 */

use Phalcon\Mvc\Micro\Collection as MicroCollection;

$getApiId   = $apiKeyToken['appKeyId'];
$getApiKey  = $apiKeyToken['appKeySecret'];
$queryApiId = '';
$queryApiKey= '';


try {

    //Books Endpoint
    $booksCollection    = new MicroCollection();

    $booksCollection->setHandler('App\Controllers\BooksController', true);

    $booksCollection->setPrefix('/books');

    $booksCollection->get('/', 'getBookItems');

    $booksCollection->get('/{id:[0-9]+}', 'getBookDetailsById');

    $booksCollection->get('/latest', 'getRecentBooks');

    $booksCollection->get('/popular', 'getPopularBooks');

    $booksCollection->get('/messages', 'getActiveMessages');

    $booksCollection->get('/category/{category_id:[0-9]+}', 'getItemsByCategory');

    $booksCollection->get('/{id:[0-9]+}/reviews', 'getBookReviews');

    $booksCollection->get('/author/{id:[0-9]+}', 'getBooksListByAuthorId');

    $booksCollection->get('/writers/{id:[0-9]+}', 'getAuthorDetailsById');

    $booksCollection->get('/categories', 'getCategoryLists');

    $booksCollection->get('/{category_id:[0-9]+}/similar', 'getSimilarBooksById');

    $booksCollection->get('/search/{query:[a-z\-]+}', 'getSearchBookItems');

    $booksCollection->get('/authors', 'getAuthors');

    $booksCollection->get('/stories', 'getStoriesBook');

    $booksCollection->get('/stories/{id:[0-9]+}', 'getSingleStoryByBook');

    $booksCollection->get('/{id:[a-zA-Z0-9\_]+}/gethighlights', 'getBookHighLights');

    $booksCollection->get('/download/{book_id:[0-9]+}/{user_id}', 'getAlreadyDownloadedBook');

    $booksCollection->get('/getdownloads/{book_id:[0-9]+}', 'getDownloadHitNumber');

    //POST API CALLS
    $booksCollection->post('/savehighlights', 'createHighlights');

    $booksCollection->post('/comment', 'submitComment');

    $booksCollection->post('/payment', 'setOrderPayment');

    $booksCollection->post('/visited', 'setBookDownloadVisit');

    $booksCollection->post('/saveddownloads', 'saveDownloads');


    $app->mount($booksCollection);

    //Create Transaction API
    $transactionsCollections    = new MicroCollection();
    $transactionsCollections->setHandler('App\Controllers\TransactionsController', true);

    $transactionsCollections->setPrefix('/transactions');

    $transactionsCollections->get('/{id:[a-zA-Z0-9\_]+}', 'getTransactionList');

    $transactionsCollections->post('/create', 'createOrderAction');

    $app->mount($transactionsCollections);


    //Register API
    $registerCollection   = new MicroCollection();
    $registerCollection->setHandler('App\Controllers\RegisterController', true);

    $registerCollection->setPrefix('/register');

    $registerCollection->post('/create', 'createRegister');

    $registerCollection->post('/settoken', 'setDeviceToken');

    $app->mount($registerCollection);

    //Login API
    $loginCollection   = new MicroCollection();
    $loginCollection->setHandler('App\Controllers\LoginController', true);

    $loginCollection->setPrefix('/login');

    $loginCollection->post('/', 'checkUserSubmitParams');

    $app->mount($loginCollection);

    $app->notFound(function()use ($app){
        throw new Exception('URI not found: '.$app->request->getMethod().' '.$app->request->getURI());
    });

}
catch (Exception $ex) {
    $app->response->setJsonContent(
        [
            "status"    => "ERROR",
            "message"   => $ex->getMessage()
        ]
    );
    $app->response->send();
}


//var_dump($apiKeyToken); exit;