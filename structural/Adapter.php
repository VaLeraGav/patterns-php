<?php

interface NativeWorker
{
    public function countSalary(): int;
}

interface OutsourceWorker
{
    public function countSalaryByHour($hours): int;
}

class NativeDeveloper implements NativeWorker
{
    public function countSalary(): int
    {
        return 3000 * 20;
    }
}

class OutsourceDeveloper implements OutsourceWorker
{
    public function countSalaryByHour($hours): int
    {
        return $hours * 300;
    }
}

class OutsourceWorkerAdapter implements NativeWorker
{
    private OutsourceWorker $outsourceWorker;

    public function __construct(OutsourceWorker $outsourceWorker)
    {
        $this->outsourceWorker = $outsourceWorker;
    }

    public function countSalary(): int
    {
        return $this->outsourceWorker->countSalaryByHour(40);
    }
}

$nativeDeveloper = new NativeDeveloper();
echo $nativeDeveloper->countSalary(); // 60000

$outsourceDeveloper = new OutsourceDeveloper();
echo $outsourceDeveloper->countSalaryByHour(40); //12000

$outsourceWorkerAdapter = new OutsourceWorkerAdapter($outsourceDeveloper);

echo $outsourceWorkerAdapter->countSalary(); // 12000

// ----------- 2 -----------

interface Book
{
    public function turnPage();

    public function open();

    public function getPage(): int;
}

class PaperBook implements Book
{
    private int $page;

    public function open(): void
    {
        $this->page = 1;
    }

    public function turnPage(): void
    {
        $this->page++;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}

interface EBook
{
    public function unlock();

    public function pressNext();

    /**
     * возвращает текущую страницу и общее количество страниц, например [10, 100] - страница 10 из 100
     *
     * @return int[]
     */
    public function getPage(): array;
}

/**
 * Вот этот адаптер. Обратите внимание, что в нем реализована книга,
 * таким образом, вам не нужно изменять код клиента, который использует книгу
 */
class EBookAdapter implements Book
{
    public function __construct(protected EBook $eBook)
    {
    }

    /**
     * Этот класс выполняет надлежащий перевод с одного интерфейса на другой.
     */
    public function open()
    {
        $this->eBook->unlock();
    }

    public function turnPage()
    {
        $this->eBook->pressNext();
    }

    /**
     * обратите внимание на адаптированное поведение здесь: eBook::getPage() вернет два целых числа, но Book
     * поддерживает только средство получения текущей страницы, поэтому мы адаптируем поведение здесь
     */
    public function getPage(): int
    {
        return $this->eBook->getPage()[0];
    }
}

/**
 * это адаптированный класс. В производственном коде это может быть класс из другого пакета, какой-нибудь код поставщика.
 * Обратите внимание, что он использует другую схему именования, и реализация делает нечто подобное, но другим способом
 */
class Kindle implements EBook
{
    private int $page = 1;
    private int $totalPages = 100;

    public function pressNext()
    {
        $this->page++;
    }

    public function unlock()
    {
    }

    /**
     * возвращает текущую страницу и общее количество страниц, например [10, 100] - страница 10 из 100
     *
     * @return int[]
     */
    public function getPage(): array
    {
        return [$this->page, $this->totalPages];
    }
}

$book = new PaperBook();
$book->open();
$book->turnPage();
echo $book->getPage(); // 2

$kindle = new Kindle();
$kindle->unlock();
$kindle->pressNext();
print_r($kindle->getPage());
// [0] => 2
// [1] => 100

$kindle2 = new Kindle();
$book2 = new EBookAdapter($kindle2);
$book2->open();
$book2->turnPage();
$book2->turnPage();
echo $book2->getPage(); // 3