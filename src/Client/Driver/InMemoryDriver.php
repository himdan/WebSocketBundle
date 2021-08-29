<?php declare(strict_types=1);

namespace Gos\Bundle\WebSocketBundle\Client\Driver;

trigger_deprecation('gos/web-socket-bundle', '3.11', 'The "%s" class is deprecated and will be removed in 4.0, use the new websocket authentication API instead.', InMemoryDriver::class);

/**
 * @deprecated to be removed in 4.0, use the new websocket authentication API instead
 */
final class InMemoryDriver implements DriverInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $elements = [];

    public function fetch(string $id): mixed
    {
        if (!$this->contains($id)) {
            return false;
        }

        return $this->elements[$id];
    }

    public function contains(string $id): bool
    {
        return isset($this->elements[$id]);
    }

    public function save(string $id, mixed $data, int $lifeTime = 0): bool
    {
        $this->elements[$id] = $data;

        return true;
    }

    public function delete(string $id): bool
    {
        unset($this->elements[$id]);

        return true;
    }

    public function clear(): void
    {
        $this->elements = [];
    }
}
