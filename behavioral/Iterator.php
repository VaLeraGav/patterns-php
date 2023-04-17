<?php

class WorkerList
{
    private array $list = [];
    private int $index = 0;

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @param int $index
     */
    public function setIndex(int $index): void
    {
        $this->index = $index;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     * @param array $list
     */
    public function setList(array $list): void
    {
        $this->list = $list;
    }

    public function getItem($key): ?Worker
    {
        if ($this->list[$key]) {
            return $this->list[$key];
        }
        return null;
    }

    public function next()
    {
        if ($this->index < count($this->list) - 1) {
            $this->index++;
        }
    }

    public function prev()
    {
        if ($this->index !== 0) {
            $this->index--;
        }
    }

    public function getByIndex(): Worker
    {
        return $this->list[$this->index];
    }

    public function refresh()
    {
        $this->index = 0;
    }
}

class Worker
{
    private string $name = '';

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}

$worker = new Worker('Boris');
$worker2 = new Worker('Bob');
$worker3 = new Worker('Kate');

$workerList = new  WorkerList();
$workerList->setList([$worker, $worker2, $worker3]);

$workerList->next();
$workerList->next();
$workerList->next();
var_dump($workerList->getByIndex()->getName()); // Kate

// ----------- 2 -----------

class Book
{
    public function __construct(private string $title, private string $author)
    {
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthorAndTitle(): string
    {
        return $this->getTitle() . ' by ' . $this->getAuthor();
    }
}

class BookList implements Countable, Iterator
{
    /**
     * @var Book[]
     */
    private array $books = [];
    private int $currentIndex = 0;

    public function addBook(Book $book)
    {
        $this->books[] = $book;
    }

    public function removeBook(Book $bookToRemove)
    {
        foreach ($this->books as $key => $book) {
            if ($book->getAuthorAndTitle() === $bookToRemove->getAuthorAndTitle()) {
                unset($this->books[$key]);
            }
        }

        $this->books = array_values($this->books);
    }

    public function count(): int
    {
        return count($this->books);
    }

    public function current(): Book
    {
        return $this->books[$this->currentIndex];
    }

    public function key(): int
    {
        return $this->currentIndex;
    }

    public function next()
    {
        $this->currentIndex++;
    }

    public function rewind()
    {
        $this->currentIndex = 0;
    }

    public function valid(): bool
    {
        return isset($this->books[$this->currentIndex]);
    }
}


$bookList = new BookList();
$bookList->addBook(new Book('Learning PHP Design Patterns', 'William Sanders'));
$bookList->addBook(new Book('Professional Php Design Patterns', 'Aaron Saray'));
$bookList->addBook(new Book('Clean Code', 'Robert C. Martin'));

$books = [];

foreach ($bookList as $book) {
    $books[] = $book->getAuthorAndTitle();
}

print_r($books);
//  [
//      'Learning PHP Design Patterns by William Sanders',
//      'Professional Php Design Patterns by Aaron Saray',
//      'Clean Code by Robert C. Martin',
//  ],


$book = new Book('Clean Code', 'Robert C. Martin');
$book2 = new Book('Professional Php Design Patterns', 'Aaron Saray');

$bookList = new BookList();
$bookList->addBook($book);
$bookList->addBook($book2);
$bookList->removeBook($book);

$books2 = [];
foreach ($bookList as $book) {
    $books2[] = $book->getAuthorAndTitle();
}

print_r($books2); //  Professional Php Design Patterns by Aaron Saray