<?php

/**
 * Классический паттерн CoR объявляет для объектов, составляющих цепочку,
 * единственную роль – Обработчик. В нашем примере давайте проводить различие
 * между middleware и конечным обработчиком приложения, который выполняется,
 * когда запрос проходит через все объекты middleware.
 *
 * Базовый класс Middleware объявляет интерфейс для связывания объектов
 * middleware в цепочку.
 */
abstract class Middleware
{
    /**
     * @var Middleware
     */
    private $next;

    /**
     * Этот метод можно использовать для построения цепочки объектов middleware.
     */
    public function linkWith(Middleware $next): Middleware
    {
        $this->next = $next;

        return $next;
    }

    /**
     * Подклассы должны переопределить этот метод, чтобы предоставить свои
     * собственные проверки. Подкласс может обратиться к родительской реализации
     * проверки, если сам не в состоянии обработать запрос.
     */
    public function check(string $email, string $password): bool
    {
        if (!$this->next) {
            return true;
        }

        return $this->next->check($email, $password);
    }
}

/**
 * Это Конкретное Middleware проверяет, существует ли пользователь с указанными
 * учётными данными.
 */
class UserExistsMiddleware extends Middleware
{
    private $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function check(string $email, string $password): bool
    {
        if (!$this->server->hasEmail($email)) {
            echo "UserExistsMiddleware: This email is not registered!\n";

            return false;
        }

        if (!$this->server->isValidPassword($email, $password)) {
            echo "UserExistsMiddleware: Wrong password!\n";

            return false;
        }

        return parent::check($email, $password);
    }
}

/**
 * Это Конкретное Middleware проверяет, имеет ли пользователь, связанный с
 * запросом, достаточные права доступа.
 */
class RoleCheckMiddleware extends Middleware
{
    public function check(string $email, string $password): bool
    {
        if ($email === "admin@example.com") {
            echo "RoleCheckMiddleware: Hello, admin!\n";

            return true;
        }
        echo "RoleCheckMiddleware: Hello, user!\n";

        return parent::check($email, $password);
    }
}

/**
 * Это Конкретное Middleware проверяет, не было ли превышено максимальное число
 * неудачных запросов авторизации.
 */
class ThrottlingMiddleware extends Middleware
{
    private $requestPerMinute;

    private $request;

    private $currentTime;

    public function __construct(int $requestPerMinute)
    {
        $this->requestPerMinute = $requestPerMinute;
        $this->currentTime = time();
    }

    /**
     * Обратите внимание, что вызов parent::check можно вставить как в начале
     * этого метода, так и в конце.
     *
     * Это даёт значительно большую свободу действий, чем простой цикл по всем
     * объектам middleware. Например, middleware может изменить порядок
     * проверок, запустив свою проверку после всех остальных.
     */
    public function check(string $email, string $password): bool
    {
        if (time() > $this->currentTime + 60) {
            $this->request = 0;
            $this->currentTime = time();
        }

        $this->request++;

        if ($this->request > $this->requestPerMinute) {
            echo "ThrottlingMiddleware: Request limit exceeded!\n";
            die();
        }

        return parent::check($email, $password);
    }
}

/**
 * Это класс приложения, который осуществляет реальную обработку запроса. Класс
 * Сервер использует паттерн CoR для выполнения набора различных промежуточных
 * проверок перед запуском некоторой бизнес-логики, связанной с запросом.
 */
class Server
{
    private $users = [];

    /**
     * @var Middleware
     */
    private $middleware;

    /**
     * Клиент может настроить сервер с помощью цепочки объектов middleware.
     */
    public function setMiddleware(Middleware $middleware): void
    {
        $this->middleware = $middleware;
    }

    /**
     * Сервер получает email и пароль от клиента и отправляет запрос авторизации
     * в middleware.
     */
    public function logIn(string $email, string $password): bool
    {
        if ($this->middleware->check($email, $password)) {
            echo "Server: Authorization has been successful!\n";

            // Выполняем что-нибудь полезное для авторизованных пользователей.

            return true;
        }

        return false;
    }

    public function register(string $email, string $password): void
    {
        $this->users[$email] = $password;
    }

    public function hasEmail(string $email): bool
    {
        return isset($this->users[$email]);
    }

    public function isValidPassword(string $email, string $password): bool
    {
        return $this->users[$email] === $password;
    }
}

/**
 * Клиентский код.
 */
$server = new Server();
$server->register("admin@example.com", "admin_pass");
$server->register("user@example.com", "user_pass");

// Все middleware соединены в цепочки. Клиент может построить различные
// конфигурации цепочек в зависимости от своих потребностей.
$middleware = new ThrottlingMiddleware(2);
$middleware
    ->linkWith(new UserExistsMiddleware($server))
    ->linkWith(new RoleCheckMiddleware());

// Сервер получает цепочку из клиентского кода.
$server->setMiddleware($middleware);

// ...

do {
    echo "\nEnter your email:\n";
    $email = readline();
    echo "Enter your password:\n";
    $password = readline();
    $success = $server->logIn($email, $password);
} while (!$success);



//  2
class Blank
{
    public const MINIMAL_SQUARE = 1;
    public function __construct(
        public string $material,
        public ?string $color,
        public float $square,
        public float $temperature,
    ) {}
}

abstract class Operator
{
    private ?Operator $next = null;

    public function setNextOperator(Operator $nextOperator): Operator
    {
        $this->next = $nextOperator;
        return $nextOperator;
    }

    public function action(Blank $blank): bool
    {
        if(is_null($this->next)){
            return true;
        }
        return $this->next->action($blank);
    }
}

class HeaterOperator extends Operator
{
    public function __construct(private readonly float $temperature)
    {
    }

    public function action(Blank $blank): bool
    {
        if(!$blank->material == 'steel')
        {
            echo 'Материал этого типа нельзя греть' . PHP_EOL;
            return false;
        }

        if($blank->temperature > 1500){
            echo 'Заготовка расплавилась!' . PHP_EOL;
            return false;
        }

        echo 'Нагреваем металл на ' . $this->temperature . ' градусов' . PHP_EOL;
        $blank->temperature += $this->temperature;

        return parent::action($blank);
    }
}

class ColorOperator extends Operator
{
    public function __construct(private readonly string $color)
    {
    }

    public function action(Blank $blank): bool
    {
        if(!is_null($blank->color)){
            echo 'Заготовка уже покрашена! Брак!' . PHP_EOL;
            return false;
        }
        echo 'Красим заготовку в ' . $this->color . ' цвет' . PHP_EOL;
        $blank->color = $this->color;
        return parent::action($blank);
    }
}

class CutterOperator extends Operator
{
    public function __construct(private readonly float $squareSize)
    {
    }

    public function action(Blank $blank): bool
    {
        if($blank->square < $this->squareSize + Blank::MINIMAL_SQUARE){
            echo 'Нельзя отрезать так много. Болванка будет испорчена!' . PHP_EOL;
            return false;
        }
        echo 'Режем ' . $this->squareSize . PHP_EOL;
        $blank->square -= $this->squareSize;
        return parent::action($blank);
    }
}

class Conveyor
{
    public function __construct(private Blank $blank, private Operator $operator)
    {
    }

    public function createDetail():void
    {
        if($this->operator->action($this->blank)){
            echo 'Изделие готово!' . PHP_EOL;
            return;
        }

        echo 'Не получилось создать изделие!' . PHP_EOL;
    }
}

/**
 * Клиентский код
 */
$blank = new Blank(material: 'steel',color: null,square: 100,temperature: 20);

$cutter = new CutterOperator(20);
$heater = new HeaterOperator(50);
$colorizer = new ColorOperator('red');

$cutter->setNextOperator($heater)->setNextOperator($colorizer); // цепочка обязанностей
$conveyor = new Conveyor($blank, $cutter);
$conveyor->createDetail();
