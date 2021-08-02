<?php


namespace App\Controllers;


use Phalcon\Mvc\Model;
use Phalcon\Paginator\Adapter\QueryBuilder;

class TransactionsController extends BaseInjectable
{

    const LIMIT = 20;

    public function createOrderAction(){

    }

    public function getTransactionList(string $userId){
        $offset         = @$this->_requestTypeQuery()['page'];
        $queryBuilder   = $this->modelsManager->createBuilder()
            ->columns(
                [
                    "order_id"      => "P.order_id",
                    "user_id"       => "P.user_id",
                    "book_id"       => "P.book_id",
                    "description"   => "P.description",
                    "created"       => "DATE_FORMAT(P.created, '%M %D, %Y')",
                    "payment_method"=> "P.payment_method",
                    //"created"       => "P.created",
                    "status"        => "P.status",
                    "amount"        => "P.amount",
                    "reference"     => "P.reference",
                    "image_url"     => "I.image_title",
                    "book_file"     => "B.filename",
                    "paid_at"       => "P.paid_at",
                    "customer_email"=> "P.customer_email",
                    "first_name"    => "P.first_name",
                    "last_name"     => "P.last_name",
                    "raw_data"      => "P.raw_data",
                    //"vote_average"  => $this->getAverageRating(2),
                    //"ratings"       => 10
                ]
            )
            ->from(['P' => 'App\Models\Orders'])
            ->where('P.user_id="'.$userId.'"')
            ->innerJoin('App\Models\BookImages', 'P.book_id = I.book_id', 'I')
            ->innerJoin('App\Models\BookFiles', 'P.book_id = B.book_id', 'B')
            ->orderBy('P.created DESC');

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
}