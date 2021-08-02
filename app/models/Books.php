<?php


namespace App\Models;


class Books extends BaseModel
{

    public function initialize(){
        $this->hasOne(
            "author_id",
            "App\\Models\\Authors",
            "id",
            [
                "reusable"  => true,
                "alias"     => "Authors"
            ]
        );

        $this->hasOne(
            "id",
            "App\\Models\\BookImages",
            "book_id",
            [
                "reusable"  => true,
                "alias"     => "BookImages"
            ]
        );

        $this->hasOne(
            "id",
            "App\\Models\\BookFiles",
            "book_id",
            [
                "reusable"  => true,
                "alias"     => "BookFiles"
            ]
        );

        $this->hasMany(
            "id",
            "App\\Models\\Highlights",
            "book_id",
            [
                "reusable"  => true,
                "alias"     => "Highlights"
            ]
        );

        $this->hasMany(
            "id",
            "App\\Models\\MyLibrary",
            "book_id",
            [
                "reusable"  => true,
                "alias"     => "MyLibrary"
            ]
        );
    }

    public function getAuthors(){
        return $this->getRelated("Authors");
    }

    public function getMyLibrary(){
        return $this->getRelated("MyLibrary");
    }

    public function getBookImages(){
        return $this->getRelated("BookImages");
    }

    public function getBookFiles(){
        return $this->getRelated("BookFiles");
    }

    public function getHighlights(){
        return $this->getRelated("Highlights");
    }

}