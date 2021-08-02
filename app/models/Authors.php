<?php


namespace App\Models;


class Authors extends BaseModel
{
    public function initialize(){
        $this->hasMany(
            "id",
            "App\\Models\\Books",
            "author_id",
            [
                "reusable"  => true,
                "alias"     => "Books"
            ]
        );
    }

    public function getBooks(){
        return $this->getRelated("Books");
    }

}