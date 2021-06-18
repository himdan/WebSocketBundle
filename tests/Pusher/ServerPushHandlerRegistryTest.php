<?php declare(strict_types=1);

namespace Gos\Bundle\WebSocketBundle\Tests\Pusher;

use Gos\Bundle\WebSocketBundle\Pusher\ServerPushHandlerInterface;
use Gos\Bundle\WebSocketBundle\Pusher\ServerPushHandlerRegistry;
use Gos\Bundle\WebSocketBundle\Server\App\PushableWampServerInterface;
use PHPUnit\Framework\TestCase;
use React\EventLoop\LoopInterface;

/**
 * @group legacy
 */
class ServerPushHandlerRegistryTest extends TestCase
{
    /**
     * @var ServerPushHandlerRegistry
     */
    private $registry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = new ServerPushHandlerRegistry();
    }

    public function testPushHandlersAreAddedToTheRegistry(): void
    {
        $handler = new class() implements ServerPushHandlerInterface {
            public function handle(LoopInterface $loop, PushableWampServerInterface $app): void
            {
                // no-op
            }

            public function close(): void
            {
                // no-op
            }

            public function setName(string $name): void
            {
                // no-op
            }

            public function getName(): string
            {
                return 'test';
            }
        };

        $this->registry->addPushHandler($handler);

        self::assertSame($handler, $this->registry->getPushHandler($handler->getName()));
        self::assertContains($handler, $this->registry->getPushers());
        self::assertTrue($this->registry->hasPushHandler($handler->getName()));
    }

    public function testRetrievingAHandlerFailsIfTheNamedHandlerDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('A push handler named "main" has not been registered.');

        $handler = new class() implements ServerPushHandlerInterface {
            public function handle(LoopInterface $loop, PushableWampServerInterface $app): void
            {
                // no-op
            }

            public function close(): void
            {
                // no-op
            }

            public function setName(string $name): void
            {
                // no-op
            }

            public function getName(): string
            {
                return 'test';
            }
        };

        $this->registry->addPushHandler($handler);

        $this->registry->getPushHandler('main');
    }
}
