<?php

namespace App\Models;

use Nette;

class BookManager
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function getBooks()
    {
        return $this->database->table('books');
    }

    public function getBookValuePairs()
    {
        return $this->getBooks()->fetchPairs('id', 'title');
    }

    public function getBook($id)
    {
        return $this->getBooks()->get($id);
    }

    public function createBook($data)
    {
        $this->getBooks()->insert($data);
    }

    public function updateBook($id, $data)
    {
        $this->getBook($id)->update($data);
    }

    public function deleteBook($id)
    {
        $this->getBook($id)->delete();
    }

    public function assignToGroup($data)
    {
        $this->database->table('literature_groups_has_books')
            ->insert($data);
    }
}