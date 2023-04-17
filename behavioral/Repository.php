<?php

class Post
{
    public static function draft(PostId $id, string $title, string $text): Post
    {
        return new self(
            $id,
            PostStatus::fromString(PostStatus::STATE_DRAFT),
            $title,
            $text
        );
    }

    public static function fromState(array $state): Post
    {
        return new self(
            PostId::fromInt($state['id']),
            PostStatus::fromInt($state['statusId']),
            $state['title'],
            $state['text']
        );
    }

    private function __construct(
        private PostId $id,
        private PostStatus $status,
        private string $title,
        private string $text
    ) {
    }

    public function getId(): PostId
    {
        return $this->id;
    }

    public function getStatus(): PostStatus
    {
        return $this->status;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}

/**
 * Это идеальный пример объекта value, который идентифицируется только по его значению и
 * гарантированно является действительным при каждом создании экземпляра. Еще одно важное свойство объектов ценности
 * - это неизменяемость.
 *
 * Обратите также внимание на использование именованного конструктора (из Int), который добавляет небольшой
 * контекст при создании экземпляра.
 */
class PostId
{
    public static function fromInt(int $id): PostId
    {
        self::ensureIsValid($id);

        return new self($id);
    }

    private function __construct(private int $id)
    {
    }

    public function toInt(): int
    {
        return $this->id;
    }

    private static function ensureIsValid(int $id)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Invalid PostId given');
        }
    }
}

/**
 *  Like и Posted, это объект value, который содержит значение текущего статуса записи. Он может быть сконструирован
 * либо из строки, либо из int и способен проверять сам себя. Затем экземпляр может быть преобразован обратно в int или string.
 */
class PostStatus
{
    public const STATE_DRAFT_ID = 1;
    public const STATE_PUBLISHED_ID = 2;

    public const STATE_DRAFT = 'draft';
    public const STATE_PUBLISHED = 'published';

    private static array $validStates = [
        self::STATE_DRAFT_ID => self::STATE_DRAFT,
        self::STATE_PUBLISHED_ID => self::STATE_PUBLISHED,
    ];

    public static function fromInt(int $statusId)
    {
        self::ensureIsValidId($statusId);

        return new self($statusId, self::$validStates[$statusId]);
    }

    public static function fromString(string $status)
    {
        self::ensureIsValidName($status);
        $state = array_search($status, self::$validStates);

        if ($state === false) {
            throw new InvalidArgumentException('Invalid state given!');
        }

        return new self($state, $status);
    }

    private function __construct(private int $id, private string $name)
    {
    }

    public function toInt(): int
    {
        return $this->id;
    }

    /**
     * есть причина, по которой я избегаю использования __toString(), поскольку он работает вне стека в PHP
     * и, следовательно, не способен хорошо работать с исключениями
     */
    public function toString(): string
    {
        return $this->name;
    }

    private static function ensureIsValidId(int $status)
    {
        if (!in_array($status, array_keys(self::$validStates), true)) {
            throw new InvalidArgumentException('Invalid status id given');
        }
    }


    private static function ensureIsValidName(string $status)
    {
        if (!in_array($status, self::$validStates, true)) {
            throw new InvalidArgumentException('Invalid status name given');
        }
    }
}

/**
 * Этот класс расположен между уровнем сущностей (class Post) и уровнем объектов доступа (Persistence).
 *
 * Репозиторий инкапсулирует набор объектов, сохраняемых в хранилище данных, и операции, выполняемые над ними
 * обеспечение более объектно-ориентированного представления уровня сохраняемости
 *
 * Репозиторий также поддерживает цель достижения чистого разделения и односторонней зависимости
 * между уровнями отображения домена и данных
 */
class PostRepository
{
    public function __construct(private Persistence $persistence)
    {
    }

    public function generateId(): PostId
    {
        return PostId::fromInt($this->persistence->generateId());
    }

    public function findById(PostId $id): Post
    {
        try {
            $arrayData = $this->persistence->retrieve($id->toInt());
        } catch (OutOfBoundsException $e) {
            throw new OutOfBoundsException(sprintf('Post with id %d does not exist', $id->toInt()), 0, $e);
        }

        return Post::fromState($arrayData);
    }

    public function save(Post $post)
    {
        $this->persistence->persist([
            'id' => $post->getId()->toInt(),
            'statusId' => $post->getStatus()->toInt(),
            'text' => $post->getText(),
            'title' => $post->getTitle(),
        ]);
    }
}

interface Persistence
{
    public function generateId(): int;

    public function persist(array $data);

    public function retrieve(int $id): array;

    public function delete(int $id);
}

class InMemoryPersistence implements Persistence
{
    private array $data = [];
    private int $lastId = 0;

    public function generateId(): int
    {
        $this->lastId++;

        return $this->lastId;
    }

    public function persist(array $data)
    {
        $this->data[$this->lastId] = $data;
    }

    public function retrieve(int $id): array
    {
        if (!isset($this->data[$id])) {
            throw new OutOfBoundsException(sprintf('No data found for ID %d', $id));
        }

        return $this->data[$id];
    }

    public function delete(int $id)
    {
        if (!isset($this->data[$id])) {
            throw new OutOfBoundsException(sprintf('No data found for ID %d', $id));
        }

        unset($this->data[$id]);
    }
}

$repository = new PostRepository(new InMemoryPersistence());

print_r($repository->generateId()->toInt()); // 1

// $repository->findById(PostId::fromInt(42)); // No data found for ID 42

$postId = $repository->generateId();
$post = Post::draft($postId, 'Repository Pattern', 'Design Patterns PHP');
$repository->save($post);

$repository->findById($postId);

print_r($postId); // [id:PostId:private] => 2
print_r($repository->findById($postId)->getId()); // [id:PostId:private] => 2
print_r(PostStatus::STATE_DRAFT === $post->getStatus()->toString()); // 1
