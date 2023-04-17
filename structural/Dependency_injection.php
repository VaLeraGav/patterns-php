<?php

// Разделение роли
class ControllerConfiguration
{
    private string $name;
    private string $action;

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param $name
     * @param $action
     */
    public function __construct(string $name, string $action)
    {
        $this->name = $name;
        $this->action = $action;
    }
}

class Controller
{
    private ControllerConfiguration $controllerConfiguration;

    /**
     * @param ControllerConfiguration $controllerConfiguration
     */
    public function __construct(ControllerConfiguration $controllerConfiguration)
    {
        $this->controllerConfiguration = $controllerConfiguration;
    }

    public function getConfiguration(): string
    {
        return $this->controllerConfiguration->getName() . '@' . $this->controllerConfiguration->getAction() . PHP_EOL;
    }
}

$controllerConfiguration = new ControllerConfiguration('Post', 'Index');
$controllerConfiguration2 = new ControllerConfiguration('User', 'Index');
$controller = new Controller($controllerConfiguration);
$controller2 = new Controller($controllerConfiguration2);
echo $controller->getConfiguration(); // Post@Index
echo $controller2->getConfiguration(); // User@Index


// ----------- 2 -----------

class DatabaseConfiguration
{
    public function __construct(
        private string $host,
        private int $port,
        private string $username,
        private string $password
    ) {
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

class DatabaseConnection
{
    public function __construct(private DatabaseConfiguration $configuration)
    {
    }

    public function getDsn(): string
    {
        // this is just for the sake of demonstration, not a real DSN
        // notice that only the injected config is used here, so there is
        // a real separation of concerns here

        return sprintf(
            '%s:%s@%s:%d',
            $this->configuration->getUsername(),
            $this->configuration->getPassword(),
            $this->configuration->getHost(),
            $this->configuration->getPort()
        );
    }
}

$config = new DatabaseConfiguration('localhost', 3306, 'domnikl', '1234');
print_r($config);

$connection = new DatabaseConnection($config);
echo $connection->getDsn(); // domnikl:1234@localhost:3306

