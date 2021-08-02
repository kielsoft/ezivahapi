<?php


namespace App\Controllers;


use App\Models\Highlights;
use App\Models\History;
use App\Models\Orders;
use App\Models\Reviews;
use App\Models\Visits;
use Phalcon\Paginator\Adapter\AbstractAdapter;
use Phalcon\Paginator\Adapter\Model;
use Phalcon\Paginator\Adapter\NativeArray;
use Phalcon\Paginator\Adapter\QueryBuilder;

class BooksController extends BaseInjectable {

    const LIMIT = 20;

    /**
     * Get Book Items
     */
    //Randomly Display Books Explore
    public function getBookItems(){
        $haystack           = [];
        $offset             = @$this->_requestTypeQuery()['page'];
        $productsBuilder    = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "title"         => "P.title",
                    "category_id"   => "P.category_id",
                    "description"   => "P.overview",
                    "date_created"  => "DATE_FORMAT(P.date_created, '%M %D, %Y')",
                    "author_name"   => "CONCAT(A.first_name,' ', A.last_name)",
                    "author_id"     => "P.author_id",
                    "status"        => "P.status",
                    "price"         => "P.price",
                    "book_type"     => "P.book_type",
                    "image_url"     => "I.image_title",
                    "book_file"     => "B.filename",
                    "author_img"    => "A.profile_path",
                    "author_email"  => "A.email",
                    //"vote_average"  => $this->getAverageRating(2),
                    //"ratings"       => 10
                ]
            )
            ->addFrom('App\Models\Books', 'P')
            ->innerJoin('App\Models\BookImages', 'P.id = I.book_id', 'I')
            ->innerJoin('App\Models\Authors', 'P.author_id = A.id', 'A')
            ->innerJoin('App\Models\BookFiles', 'P.id = B.book_id', 'B')
            //->limit(SELF::LIMIT, $offset)
            //->orderBy('RAND()');
            ->orderBy('RAND()')
            ->getQuery()->execute();

        foreach($productsBuilder as $key => $value){
            $haystack[] = [
                "id"            => $value->id,
                "title"         => $value->title,
                "category_id"   => $value->category_id,
                "description"   => $value->description,
                "date_created"  => $value->date_created,
                "author_name"   => $value->author_name,
                "author_id"     => $value->author_id,
                "status"        => $value->status,
                "price"         => $value->price,
                "book_type"     => $value->book_type,
                "image_url"     => $value->image_url,
                "book_file"     => $value->book_file,
                "author_img"    => $value->author_img,
                "author_email"  => $value->author_email,
                "vote_average"  => $this->getAverageRating($value->id),
                "ratings"       => $this->getCountRatings($value->id)
            ];
        }

        $paginator  = new NativeArray(
            [
                "page"      => $offset,
                "limit"     => self::LIMIT,
                "data"      => $haystack
            ]
        );

        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "page"          => $paginator->getPaginate()->current,
                "results"       => $paginator->getPaginate()->items,
                "total_results" => $paginator->getPaginate()->total_items,
                "total_pages"   => $paginator->getPaginate()->total_pages,
            ]
        )->send();
    }

    /**
     * Get Recently Uploaded
     */
    //Get Recently Uploaded files by ID and Date
    public function getRecentBooks(){
        $haystack           = [];
        $offset             = @$this->_requestTypeQuery()['page'];
        $productsBuilder    = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "title"         => "P.title",
                    "category_id"   => "P.category_id",
                    "description"   => "P.overview",
                    "date_created"  => "DATE_FORMAT(P.date_created, '%M %D, %Y')",
                    "author_name"   => "CONCAT(A.first_name,' ', A.last_name)",
                    "author_id"     => "P.author_id",
                    "status"        => "P.status",
                    "price"         => "P.price",
                    "book_type"     => "P.book_type",
                    "image_url"     => "I.image_title",
                    "book_file"     => "B.filename",
                    "author_img"    => "A.profile_path",
                    "author_email"  => "A.email",
                    //"vote_average"  => $this->getAverageRating(2),
                    //"ratings"       => 10
                ]
            )
            ->addFrom('App\Models\Books', 'P')
            ->innerJoin('App\Models\BookImages', 'P.id = I.book_id', 'I')
            ->innerJoin('App\Models\Authors', 'P.author_id = A.id', 'A')
            ->innerJoin('App\Models\BookFiles', 'P.id = B.book_id', 'B')
            //->limit(SELF::LIMIT, $offset)
            //->orderBy('RAND()');
            ->orderBy('P.id DESC')
            ->getQuery()->execute();

        foreach($productsBuilder as $key => $value){
            $haystack[] = [
                "id"            => $value->id,
                "title"         => $value->title,
                "category_id"   => $value->category_id,
                "description"   => $value->description,
                "date_created"  => $value->date_created,
                "author_name"   => $value->author_name,
                "author_id"     => $value->author_id,
                "status"        => $value->status,
                "price"         => $value->price,
                "book_type"     => $value->book_type,
                "image_url"     => $value->image_url,
                "book_file"     => $value->book_file,
                "author_img"    => $value->author_img,
                "author_email"  => $value->author_email,
                "vote_average"  => $this->getAverageRating($value->id),
                "ratings"       => $this->getCountRatings($value->id)
            ];
        }

        $paginator  = new NativeArray(
            [
                "page"      => $offset,
                "limit"     => self::LIMIT,
                "data"      => $haystack
            ]
        );

        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "page"          => $paginator->getPaginate()->current,
                "results"       => $paginator->getPaginate()->items,
                "total_results" => $paginator->getPaginate()->total_items,
                "total_pages"   => $paginator->getPaginate()->total_pages,
            ]
        )->send();
    }

    /**
     * Get Popular Books
     */
    //Get Popular Books Return Highly Visted Books
    public function getPopularBooks(){
        $haystack           = [];
        $offset             = @$this->_requestTypeQuery()['page'];
        $productsBuilder    = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "title"         => "P.title",
                    "category_id"   => "P.category_id",
                    "description"   => "P.overview",
                    "date_created"  => "DATE_FORMAT(P.date_created, '%M %D, %Y')",
                    "author_name"   => "CONCAT(A.first_name,' ', A.last_name)",
                    "author_id"     => "P.author_id",
                    "status"        => "P.status",
                    "price"         => "P.price",
                    "book_type"     => "P.book_type",
                    "image_url"     => "I.image_title",
                    "book_file"     => "B.filename",
                    "author_img"    => "A.profile_path",
                    "author_email"  => "A.email",
                    "frequency"     => "COUNT(V.book_id)",
                    //"vote_average"  => $this->getAverageRating(2),
                    //"ratings"       => 10
                ]
            )
            ->addFrom('App\Models\Books', 'P')
            ->innerJoin('App\Models\BookImages', 'P.id = I.book_id', 'I')
            ->innerJoin('App\Models\Authors', 'P.author_id = A.id', 'A')
            ->innerJoin('App\Models\BookFiles', 'P.id = B.book_id', 'B')
            ->innerJoin('App\Models\Visits', 'V.book_id = P.id', 'V')
            ->groupBy("V.book_id")
            //->limit(SELF::LIMIT, $offset)
            ->orderBy('frequency DESC')
            //->orderBy('RAND()');
            //->orderBy('P.id DESC')
            ->getQuery()->execute();

        foreach($productsBuilder as $key => $value){
            $haystack[] = [
                "id"            => $value->id,
                "title"         => $value->title,
                "category_id"   => $value->category_id,
                "description"   => $value->description,
                "date_created"  => $value->date_created,
                "author_name"   => $value->author_name,
                "author_id"     => $value->author_id,
                "status"        => $value->status,
                "price"         => $value->price,
                "book_type"     => $value->book_type,
                "image_url"     => $value->image_url,
                "book_file"     => $value->book_file,
                "author_img"    => $value->author_img,
                "author_email"  => $value->author_email,
                "vote_average"  => $this->getAverageRating($value->id),
                "ratings"       => $this->getCountRatings($value->id),
                "frequency"     => $value->frequency,
            ];
        }

        $paginator  = new NativeArray(
            [
                "page"      => $offset,
                "limit"     => self::LIMIT,
                "data"      => $haystack
            ]
        );

        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "page"          => $paginator->getPaginate()->current,
                "results"       => $paginator->getPaginate()->items,
                "total_results" => $paginator->getPaginate()->total_items,
                "total_pages"   => $paginator->getPaginate()->total_pages,
            ]
        )->send();
    }

    /**
     * Get Authors List
     */
    //Get Authors List
    public function getAuthors(){
        $offset             = @$this->_requestTypeQuery()['page'];
        $productsBuilder    = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "name"          => "CONCAT(P.first_name,' ',P.last_name)",
                    "biography"     => "P.biography",
                    "nationality"   => "P.nationality",
                    "gender"        => "P.gender",
                    "adult"         => "P.gender",
                    "profile_path"  => "P.profile_path",
                    "known_for_department"  => "P.gender",
                    "email"         => "P.email",
                    "popularity"    => 6.03,
                ]
            )
            ->from(['P' => 'App\Models\Authors'])
            ->orderBy('RAND()');

        $paginator  = new QueryBuilder(
            [
                "page"      => $offset,
                "limit"     => self::LIMIT,
                "builder"   => $productsBuilder
            ]
        );

        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "page"          => $paginator->getCurrentPage(),
                "results"       => $paginator->getPaginate()->items,
                "total_results" => $paginator->getPaginate()->total_items,
                "total_pages"   => $paginator->getPaginate()->total_pages,
            ]
        )->send();
    }

    /**
     * @param $id
     */
    //Simple Data collection method
    //Get the Author's Details with books written
    public function getAuthorDetailsById($id){
        $getQuery   = $this->modelsManager->createBuilder()
            ->from(['r' => 'App\Models\Authors'])
            ->where('r.id = "'.$id.'"')
            ->getQuery()->getSingleResult();

        $getQueryResult = [
            "id"            => $getQuery->id,
            "name"          => ucwords($getQuery->first_name." ".$getQuery->last_name),
            "biography"     => $getQuery->biography,
            "nationality"   => $getQuery->nationality,
            "gender"        => $getQuery->gender,
            "profile_path"  => $getQuery->profile_path,
            //"books"         => $getQuery->getBooks(),
            "email"         => $getQuery->email,
            "known_for_department"  => "none",
            "adult"         => false,
            "popularity"    => 10
        ];

        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "results"       => $getQueryResult,
            ]
        )->send();
    }

    /**
     * @param $id
     */
    //Get Items By Category Id
    public function getItemsByCategory($id){
        $haystack           = [];
        $offset             = @$this->_requestTypeQuery()['page'];
        $productsBuilder    = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "title"         => "P.title",
                    "category_id"   => "P.category_id",
                    "description"   => "P.overview",
                    "date_created"  => "DATE_FORMAT(P.date_created, '%M %D, %Y')",
                    "author_name"   => "CONCAT(A.first_name,' ', A.last_name)",
                    "author_id"     => "P.author_id",
                    "status"        => "P.status",
                    "price"         => "P.price",
                    "book_type"     => "P.book_type",
                    "image_url"     => "I.image_title",
                    "book_file"     => "B.filename",
                    "author_img"    => "A.profile_path",
                    "author_email"  => "A.email",
                    //"vote_average"  => $this->getAverageRating(2),
                    //"ratings"       => 10
                ]
            )
            ->from(['P' => 'App\Models\Books'])
            ->where('P.category_id = '.$id)
            ->innerJoin('App\Models\BookImages', 'P.id = I.book_id', 'I')
            ->innerJoin('App\Models\Authors', 'P.author_id = A.id', 'A')
            ->innerJoin('App\Models\BookFiles', 'P.id = B.book_id', 'B')
            //->limit(SELF::LIMIT, $offset)
            //->orderBy('RAND()');
            ->orderBy('P.id DESC')
            ->getQuery()->execute();

        foreach($productsBuilder as $key => $value){
            $haystack[] = [
                "id"            => $value->id,
                "title"         => $value->title,
                "category_id"   => $value->category_id,
                "description"   => $value->description,
                "date_created"  => $value->date_created,
                "author_name"   => $value->author_name,
                "author_id"     => $value->author_id,
                "status"        => $value->status,
                "price"         => $value->price,
                "book_type"     => $value->book_type,
                "image_url"     => $value->image_url,
                "book_file"     => $value->book_file,
                "author_img"    => $value->author_img,
                "author_email"  => $value->author_email,
                "vote_average"  => $this->getAverageRating($value->id),
                "ratings"       => $this->getCountRatings($value->id)
            ];
        }

        $paginator  = new NativeArray(
            [
                "page"      => $offset,
                "limit"     => self::LIMIT,
                "data"      => $haystack
            ]
        );

        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "page"          => $paginator->getPaginate()->current,
                "results"       => $paginator->getPaginate()->items,
                "total_results" => $paginator->getPaginate()->total_items,
                "total_pages"   => $paginator->getPaginate()->total_pages,
            ]
        )->send();
    }

    /**
     *Default category list
     */
    //Get category lists
    public function getCategoryLists(){
        $query  = $this->modelsManager->createBuilder()
            ->from(['r' => 'App\Models\Categories']);
        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "results"       => $query->getQuery()->execute(),
            ]
        )->send();
    }

    /**
     * @param string $query
     * Query String Data Type
     */
    //Use this method to search for books title
    public function getSearchBookItems(string $query = ""){
        $haystack       = [];
        $offset         = @$this->_requestTypeQuery()['page'];
        $queryBuilder   = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "title"         => "P.title",
                    "category_id"   => "P.category_id",
                    "description"   => "P.overview",
                    "date_created"  => "DATE_FORMAT(P.date_created, '%M %D, %Y')",
                    "author_name"   => "CONCAT(A.first_name,' ', A.last_name)",
                    "author_id"     => "P.author_id",
                    "status"        => "P.status",
                    "price"         => "P.price",
                    "book_type"     => "P.book_type",
                    "image_url"     => "I.image_title",
                    "book_file"     => "B.filename",
                    "author_img"    => "A.profile_path",
                    "author_email"  => "A.email",
                    //"vote_average"  => $this->getAverageRating(2),
                    //"ratings"       => 10
                ]
            )
            ->from(['P' => 'App\Models\Books'])
            ->where('P.title LIKE :title:',['title' => '%'.$query.'%'])
            ->innerJoin('App\Models\BookImages', 'P.id = I.book_id', 'I')
            ->innerJoin('App\Models\Authors', 'P.author_id = A.id', 'A')
            ->innerJoin('App\Models\BookFiles', 'P.id = B.book_id', 'B')
            //->limit(SELF::LIMIT, $offset)
            //->orderBy('RAND()');
            ->orderBy('P.id DESC')
            ->getQuery()->execute();

        foreach($queryBuilder as $key => $value){
            $haystack[] = [
                "id"            => $value->id,
                "title"         => $value->title,
                "category_id"   => $value->category_id,
                "description"   => $value->description,
                "date_created"  => $value->date_created,
                "author_name"   => $value->author_name,
                "author_id"     => $value->author_id,
                "status"        => $value->status,
                "price"         => $value->price,
                "book_type"     => $value->book_type,
                "image_url"     => $value->image_url,
                "book_file"     => $value->book_file,
                "author_img"    => $value->author_img,
                "author_email"  => $value->author_email,
                "vote_average"  => $this->getAverageRating($value->id),
                "ratings"       => $this->getCountRatings($value->id)
            ];
        }

        $paginator  = new NativeArray(
            [
                "page"      => $offset,
                "limit"     => self::LIMIT,
                "data"      => $haystack
            ]
        );

        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "page"          => $paginator->getPaginate()->current,
                "results"       => $paginator->getPaginate()->items,
                "total_results" => $paginator->getPaginate()->total_items,
                "total_pages"   => $paginator->getPaginate()->total_pages,
            ]
        )->send();
    }

    /**
     * @param int $id
     * Data type Integer
     */
    //Get book details by book_id
    public function getBookDetailsById(int $id){
        $queryBuilder   = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "title"         => "P.title",
                    "category_id"   => "P.category_id",
                    "description"   => "P.overview",
                    "date_created"  => "DATE_FORMAT(P.date_created, '%M %D, %Y')",
                    "author_name"   => "CONCAT(A.first_name,' ', A.last_name)",
                    "author_id"     => "P.author_id",
                    "status"        => "P.status",
                    "price"         => "P.price",
                    "book_type"     => "P.book_type",
                    "image_url"     => "I.image_title",
                    "book_file"     => "B.filename",
                    "author_img"    => "A.profile_path",
                    "author_email"  => "A.email",
                    //"vote_average"  => $this->getAverageRating(2),
                    //"ratings"       => 10
                ]
            )
            ->from(['P' => 'App\Models\Books'])
            ->where('P.id = "'.$id.'"')
            ->innerJoin('App\Models\BookImages', 'P.id = I.book_id', 'I')
            ->innerJoin('App\Models\Authors', 'P.author_id = A.id', 'A')
            ->innerJoin('App\Models\BookFiles', 'P.id = B.book_id', 'B')
            ->getQuery()->getSingleResult();

        $haystack[] = [
            "id"            => $queryBuilder->id,
            "title"         => $queryBuilder->title,
            "category_id"   => $queryBuilder->category_id,
            "description"   => $queryBuilder->description,
            "date_created"  => $queryBuilder->date_created,
            "author_name"   => $queryBuilder->author_name,
            "author_id"     => $queryBuilder->author_id,
            "status"        => $queryBuilder->status,
            "price"         => $queryBuilder->price,
            "book_type"     => $queryBuilder->book_type,
            "image_url"     => $queryBuilder->image_url,
            "book_file"     => $queryBuilder->book_file,
            "author_img"    => $queryBuilder->author_img,
            "author_email"  => $queryBuilder->author_email,
            "vote_average"  => $this->getAverageRating($queryBuilder->id),
            "ratings"       => $this->getCountRatings($queryBuilder->id)
        ];

        $this->response->setJsonContent(
            [
                "status"    => "OK",
                "results"   => $haystack
            ]
        )->send();
    }

    /**
     * @param int $categoryId
     * Data type Integer
     */
    //Get Similar Books by using category id
    public function getSimilarBooksById(int $categoryId){
        $haystack       = [];
        $queryBuilder   = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "title"         => "P.title",
                    "category_id"   => "P.category_id",
                    "description"   => "P.overview",
                    "date_created"  => "DATE_FORMAT(P.date_created, '%M %D, %Y')",
                    "author_name"   => "CONCAT(A.first_name,' ', A.last_name)",
                    "author_id"     => "P.author_id",
                    "status"        => "P.status",
                    "price"         => "P.price",
                    "book_type"     => "P.book_type",
                    "image_url"     => "I.image_title",
                    "book_file"     => "B.filename",
                    "author_img"    => "A.profile_path",
                    "author_email"  => "A.email",
                    //"vote_average"  => $this->getAverageRating(2),
                    //"ratings"       => 10
                ]
            )
            ->from(['P' => 'App\Models\Books'])
            ->where('P.category_id = "'.$categoryId.'"')
            //->innerJoin('App\Models\BookImages','P.id = I.book_id', 'I')
            //->innerJoin('App\Models\Authors','P.author_id = A.id', 'A')
            ->innerJoin('App\Models\BookImages', 'P.id = I.book_id', 'I')
            ->innerJoin('App\Models\Authors', 'P.author_id = A.id', 'A')
            ->innerJoin('App\Models\BookFiles', 'P.id = B.book_id', 'B')
            ->orderBy('P.id DESC')
            ->limit(10)
            ->getQuery()
            ->execute();

        foreach($queryBuilder as $key => $value){
            $haystack[] = [
                "id"            => $value->id,
                "title"         => $value->title,
                "category_id"   => $value->category_id,
                "description"   => $value->description,
                "date_created"  => $value->date_created,
                "author_name"   => $value->author_name,
                "author_id"     => $value->author_id,
                "status"        => $value->status,
                "price"         => $value->price,
                "book_type"     => $value->book_type,
                "image_url"     => $value->image_url,
                "book_file"     => $value->book_file,
                "author_img"    => $value->author_img,
                "author_email"  => $value->author_email,
                "vote_average"  => $this->getAverageRating($value->id),
                "ratings"       => $this->getCountRatings($value->id)
            ];
        }

        $this->response->setJsonContent(
            [
                "status"    => "OK",
                "results"   => $haystack
            ]
        )->send();
    }

    /**
     * @param int $id
     * Get Reviews from other registered users
     */
    public function getBookReviews(int $id){
        $queryBuilder   = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "email"         => "R.email",
                    "description"   => "P.description",
                    "date_created"  => "DATE_FORMAT(P.created, '%M %D, %Y')",
                    "image_url"     => "R.image_url",
                    "author_name"   => "CONCAT(R.first_name,' ', R.last_name)",
                    "vote_average"  => "P.ratings",
                    "ratings"       => 0
                ]
            )
            ->from(['P' => 'App\Models\Reviews'])
            ->where('P.book_id = "'.$id.'"')
            ->innerJoin('App\Models\Register','P.register_id = R.user_id', 'R')
            ->orderBy('P.id DESC')
            ->limit(20)
            ->getQuery()
            ->execute();

        $this->response->setJsonContent(
            [
                "status"    => "OK",
                "results"   => $queryBuilder
            ]
        )->send();
    }

    /**
     * @param int $id
     * Get other books by Author Using AuthorID
     */
    public function getBooksListByAuthorId(int $id){
        $haystack       = [];
        $offset         = @$this->_requestTypeQuery()['page'];
        $queryBuilder   = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "title"         => "P.title",
                    "category_id"   => "P.category_id",
                    "description"   => "P.overview",
                    "date_created"  => "DATE_FORMAT(P.date_created, '%M %D, %Y')",
                    "author_name"   => "CONCAT(A.first_name,' ', A.last_name)",
                    "author_id"     => "P.author_id",
                    "status"        => "P.status",
                    "price"         => "P.price",
                    "book_type"     => "P.book_type",
                    "image_url"     => "I.image_title",
                    "book_file"     => "B.filename",
                    "author_img"    => "A.profile_path",
                    "author_email"  => "A.email",
                    //"vote_average"  => $this->getAverageRating(2),
                    //"ratings"       => 10
                ]
            )
            ->from(['P' => 'App\Models\Books'])
            ->where('P.author_id = '.$id)
            //->addFrom('App\Models\Books','P.author_id = '.$id, 'P')
            ->innerJoin('App\Models\BookImages', 'I.book_id = P.id', 'I')
            ->innerJoin('App\Models\Authors', 'A.id = "'.$id.'"', 'A')
            ->innerJoin('App\Models\BookFiles', 'P.id = B.book_id', 'B')
            ->orderBy('P.id DESC')
            ->getQuery()
            ->execute();

        foreach($queryBuilder as $key => $value){
            $haystack[] = [
                "id"            => $value->id,
                "title"         => $value->title,
                "category_id"   => $value->category_id,
                "description"   => $value->description,
                "date_created"  => $value->date_created,
                "author_name"   => $value->author_name,
                "author_id"     => $value->author_id,
                "status"        => $value->status,
                "price"         => $value->price,
                "book_type"     => $value->book_type,
                "image_url"     => $value->image_url,
                "book_file"     => $value->book_file,
                "author_img"    => $value->author_img,
                "author_email"  => $value->author_email,
                "vote_average"  => $this->getAverageRating($value->id),
                "ratings"       => $this->getCountRatings($value->id)
            ];
        }

        $paginator  = new NativeArray(
            [
                "page"      => $offset,
                "limit"     => self::LIMIT,
                "data"      => $haystack
            ]
        );

        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "page"          => $paginator->getPaginate()->current,
                "results"       => $paginator->getPaginate()->items,
                "total_results" => $paginator->getPaginate()->total_items,
                "total_pages"   => $paginator->getPaginate()->total_pages,
            ]
        )->send();
    }

    /**
     * Get Stories
     */
    public function getStoriesBook(){
        $offset         = @$this->_requestTypeQuery()['page'];
        $queryBuilder   = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "title"         => "P.story_title",
                    "category_id"   => "P.category_id",
                    "content"       => "P.story_content",
                    "date_created"  => "DATE_FORMAT(P.date_created, '%M %D, %Y %H:%i:%s')",
                    "price"         => "P.price",
                    "book_type"     => "P.story_type",
                    "image_url"     => "P.image_url",
                    "author_name"   => "CONCAT(A.first_name,' ', A.last_name)",
                    "excerpt"       => "P.story_excerpt",
                    "author_id"     => "P.author_id",
                    "vote_average"  => 5.35,
                    "ratings"       => 10
                ]
            )
            ->from(['P' => 'App\Models\Stories'])
            ->innerJoin('App\Models\Authors', 'P.author_id = A.id', 'A')
            ->orderBy('P.id DESC');

        $paginator  = new QueryBuilder(
            [
                "page"      => $offset,
                "limit"     => self::LIMIT,
                "builder"   => $queryBuilder
            ]
        );

        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "page"          => $paginator->getCurrentPage(),
                "results"       => $paginator->getPaginate()->items,
                "total_results" => $paginator->getPaginate()->total_items,
                "total_pages"   => $paginator->getPaginate()->total_pages,
            ]
        )->send();
    }

    /**
     * @param int $id
     * Get a Single Story Book
     */
    public function getSingleStoryByBook(int $id){
        $queryBuilder   = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "title"         => "P.story_title",
                    "category_id"   => "P.category_id",
                    "content"       => "P.story_content",
                    "date_created"  => "P.DATE_FORMAT(P.date_created, '%M %D, %Y')",
                    "price"         => "P.price",
                    "book_type"     => "P.story_type",
                    "image_url"     => "P.image_url",
                    "author_name"   => "CONCAT(A.first_name,' ', A.last_name)",
                    "excerpt"       => "P.story_excerpt",
                    "author_id"     => "P.author_id",
                    "vote_average"  => 5.35,
                    "ratings"       => 10
                ]
            )
            ->from(['P' => 'App\Models\Stories'])
            ->where('P.id = '.$id)
            ->innerJoin('App\Models\Authors', 'P.author_id = A.id', 'A')
            ->getQuery()->getSingleResult();

        $this->response->setJsonContent(
            [
                "status"    => "OK",
                "results"   => $queryBuilder
            ]
        )->send();
    }

    public function createHighlights(){
        $highlights = new Highlights();
        if($highlights->create($this->_requestTypeQuery()) != false){
            $this->response->setJsonContent(
                [
                    "status"    => "OK",
                    "results"   => $this->_requestTypeQuery()
                ]
            )->send();
        }
        else{
            $this->response->setJsonContent(
                [
                    "status"    => "ERROR",
                    "results"   => $this->_requestTypeQuery()
                ]
            )->send();
        }

    }

    public function submitComment(){
        $reviews    = new Reviews();
        if($reviews->create($this->_requestTypeQuery()) != false){
            $this->response->setJsonContent(
                [
                    "status"    => "OK",
                    "results"   => $this->_requestTypeQuery()
                ]
            )->send();
        }
        else{
            $this->response->setJsonContent(
                [
                    "status"    => "ERROR",
                    "results"   => $this->_requestTypeQuery()
                ]
            )->send();
        }

    }

    public function getBookHighLights(string $userId){
        $offset         = @$this->_requestTypeQuery()['page'];
        $queryBuilder   = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "P.id",
                    "book_id"       => "P.book_id",
                    "page_number"   => "P.page_number",
                    "content"       => "P.content",
                    "date"          => "DATE_FORMAT(P.date, '%M %D, %Y %H:%i:%s')",
                    "type"          => "P.type",
                    "page_id"       => "P.page_id",
                    "rangy"         => "P.rangy",
                    "uuid"          => "P.uuid",
                    "note"          => "P.note",
                    "user_id"       => "P.user_id",
                    "book_url"      => "P.book_url",
                ]
            )
            ->from(['P' => 'App\Models\Highlights'])
            ->where('P.user_id = "'.$userId.'"')
            ->orderBy('P.id DESC');

        $paginator  = new QueryBuilder(
            [
                "page"      => $offset,
                "limit"     => self::LIMIT,
                "builder"   => $queryBuilder
            ]
        );

        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "page"          => $paginator->getCurrentPage(),
                "results"       => $paginator->getPaginate()->items,
                "total_results" => $paginator->getPaginate()->total_items,
                "total_pages"   => $paginator->getPaginate()->total_pages,
            ]
        )->send();
    }

    /**
     * @param $bookId
     */
    //Get the numbers of download made
    public function getDownloadHitNumber(int $bookId){
        $visitDownload  = Visits::count(
            [
                "book_id = :book_id:",
                "bind"  => [
                    "book_id"   => $bookId
                ]
            ]
        );

        $this->response->setJsonContent($visitDownload)->send();
    }

    //Create the Numbers of downloads
    public function setBookDownloadVisit(){
        $visit  = new Visits();
        if($visit->create($this->_requestTypeQuery()) != false){
            $this->response->setJsonContent(
                [
                    "status"    => "OK",
                    "results"   => $this->_requestTypeQuery()
                ]
            )->send();
        }
        else{
            $this->response->setJsonContent(
                [
                    "status"    => "OK",
                    "results"   => implode(",", $visit->getMessages())
                ]
            )->send();
        }
    }

    /**
     * @param $book_id
     * @param $user_id
     * GET METHOD
     */
    //check if book already downloaded
    public function getAlreadyDownloadedBook($book_id, $user_id){
        $order  = Visits::findFirst(
            [
                "book_id = :book_id: AND user_id = :user_id:",
                "bind"  => ['book_id' => $book_id, 'user_id' => $user_id]
            ]
        );
        if($order != false){
            $this->response->setJsonContent(
                [
                    "status"    => "OK",
                    "results"   => $order->toArray()
                ]
            )->send();
        }
        else{
            $this->response->setJsonContent(
                [
                    "status"    => "OK",
                    "results"   => $this->_requestTypeQuery()
                ]
            )->send();
        }
    }

    //Create Orders Transactions
    public function setOrderPayment(){
        $order  = new Orders();
        if($order->create($this->_requestTypeQuery()) != false){
            $this->response->setJsonContent(
                [
                    "status"    => "OK",
                    "results"   => $this->_requestTypeQuery()
                ]
            )->send();
        }
        else{
            $this->response->setJsonContent(
                [
                    "status"    => "ERROR",
                    "results"   => implode(",", $order->getMessages())
                ]
            )->send();
        }
    }

    private function getAverageRating($id): float
    {
        $averageRating  = Reviews::average(
            [
                "column"        => "ratings",
                "conditions"    => "book_id = $id"
            ]
        );
        return floatval($averageRating);
    }

    private function getCountRatings($id){
        return Reviews::count(
            [
                "conditions"    => "book_id = '$id'"
            ]
        );
    }

    public function getActiveMessages(){
        $offset = @$this->_requestTypeQuery()['page'];
        $query  = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "id"            => "r.id",
                    "body"          => "r.body",
                    "title"         => "r.title",
                    "date_created"  => "DATE_FORMAT(r.date_created, '%M %D, %Y %H:%i:%s')",
                ]
            )
            ->from(['r' => 'App\Models\Messages'])->orderBy('r.id DESC');

        $paginator  = new QueryBuilder(
            [
                "page"      => $offset,
                "limit"     => self::LIMIT,
                "builder"   => $query
            ]
        );

        $this->response->setJsonContent(
            [
                "status"        => "OK",
                "page"          => $paginator->getPaginate()->current,
                "results"       => $paginator->getPaginate()->items,
                "total_results" => $paginator->getPaginate()->total_items,
                "total_pages"   => $paginator->getPaginate()->total_pages,
            ]
        )->send();
    }

    public function saveDownloads(){
        $requestType    = $this->_requestTypeQuery();
        $checkExisted   = Orders::findFirst("user_id = '".$requestType['user_id']
            ."' AND book_id = '".$requestType['book_id']."'");
        if($checkExisted == false) {
            $order = new Orders();
            if ($order->create($this->_requestTypeQuery())) {
                $this->response->setJsonContent(
                    [
                        "status"    => "OK",
                        "message"   => ""
                    ]
                )->send();
            }
            else {
                $this->response->setJsonContent(
                    [
                        "status"    => "ERROR",
                        "message"   => implode(", ", $order->getMessages())
                    ]
                )->send();
            }
        }
        else{
            $this->response->setJsonContent(
                [
                    "status"    => "ERROR",
                    "message"   => "File already existed"
                ]
            )->send();
        }
    }

}