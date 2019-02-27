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

    public function getLiteratureGroupsHasBooks() {
        return $this->database->table('literature_groups_has_books');
    }

    public function getLiteratureGroupBook($id) {
        return $this->getLiteratureGroupsHasBooks()
            ->where('id', $id)->fetch();
    }

    public function reindexLiteratureGroupBooksOrder($ids) {
        for ($i = 0; $i < count($ids); $i++) {
            $this->getLiteratureGroupBook($ids[$i])
                ->update(['sort_order' => (count($ids) - 1) - $i]);
        }
    }

    public function assignToGroup($data)
    {
        $this->getLiteratureGroupsHasBooks()
            ->insert($data);
    }

    public function unassignFromGroup($literatureGroupsHasBooksId)
    {
        $this->getLiteratureGroupBook($literatureGroupsHasBooksId)->delete();
    }
}