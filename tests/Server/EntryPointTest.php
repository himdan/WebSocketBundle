<?php declare(strict_types=1);

namespace Gos\Bundle\WebSocketBundle\Tests\Server;

use Gos\Bundle\WebSocketBundle\Server\App\Registry\ServerRegistry;
use Gos\Bundle\WebSocketBundle\Server\EntryPoint;
use Gos\Bundle\WebSocketBundle\Server\Type\ServerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group legacy
 */
class EntryPointTest extends TestCase
{
    /**
     * @var ServerRegistry
     */
    private $serverRegistry;

    /**
     * @var EntryPoint
     */
    private $entryPoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serverRegistry = new ServerRegistry();

        $this->entryPoint = new EntryPoint($this->serverRegistry);
    }

    public function testTheFirstServerIsLaunchedWhenNoNameIsGiven(): void
    {
        $serverName = 'default';
        $host = 'localhost';
        $port = 8080;
        $profile = false;

        $server = $this->createMock(ServerInterface::class);
        $server->expects(self::once())
            ->method('getName')
            ->willReturn($serverName);

        $server->expects(self::once())
            ->method('launch')
            ->with($host, $port, $profile);

        $this->serverRegistry->addServer($server);

        $this->entryPoint->launch(null, $host, $port, $profile);
    }

    public function testTheNamedServerIsLaunched(): void
    {
        $serverName = 'default';
        $host = 'localhost';
        $port = 8080;
        $profile = false;

        $server = $this->createMock(ServerInterface::class);
        $server->expects(self::once())
            ->method('getName')
            ->willReturn($serverName);

        $server->expects(self::once())
            ->method('launch')
            ->with($host, $port, $profile);

        $this->serverRegistry->addServer($server);

        $this->entryPoint->launch($serverName, $host, $port, $profile);
    }

    public function testAServerIsNotLaunchedWhenTheRegistryIsEmpty(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('There are no servers registered to launch.');

        $serverName = 'default';
        $host = 'localhost';
        $port = 8080;
        $profile = false;

        $this->entryPoint->launch(null, $host, $port, $profile);
    }

    public function testAServerIsNotLaunchedWhenTheNamedServerIsNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown server test, available servers are [ default ]');

        $host = 'localhost';
        $port = 8080;
        $profile = false;

        $server = $this->createMock(ServerInterface::class);
        $server->expects(self::once())
            ->method('getName')
            ->willReturn('default');

        $this->serverRegistry->addServer($server);

        $this->entryPoint->launch('test', $host, $port, $profile);
    }
}
