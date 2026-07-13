<?php

namespace App\Tests\Unit\Auth;

use PHPUnit\Framework\TestCase;
use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\AuthRequest;
use App\Auth\AuthResponse;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

class TestAuth extends TestCase
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var AuthRepository
     */
    private $authRepository;

    protected function setUp(): void
    {
        $this->authRepository = Mockery::mock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn([
            'id' => 1,
            'username' => $username,
            'password' => $password,
        ]);

        $this->authRepository->shouldReceive('verifyPassword')->with($password, $password)->andReturn(true);

        $authRequest = new AuthRequest($username, $password);
        $authResponse = $this->authService->login($authRequest);

        $this->assertInstanceOf(AuthResponse::class, $authResponse);
        $this->assertEquals($username, $authResponse->getUsername());
        $this->assertTrue($authResponse->isLoggedIn());
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn(null);

        $authRequest = new AuthRequest($username, $password);
        $authResponse = $this->authService->login($authRequest);

        $this->assertInstanceOf(AuthResponse::class, $authResponse);
        $this->assertFalse($authResponse->isLoggedIn());
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn(null);

        $this->authRepository->shouldReceive('createUser')->with([
            'username' => $username,
            'password' => $password,
        ])->andReturn([
            'id' => 1,
            'username' => $username,
            'password' => $password,
        ]);

        $authRequest = new AuthRequest($username, $password);
        $authResponse = $this->authService->register($authRequest);

        $this->assertInstanceOf(AuthResponse::class, $authResponse);
        $this->assertEquals($username, $authResponse->getUsername());
        $this->assertTrue($authResponse->isLoggedIn());
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn([
            'id' => 1,
            'username' => $username,
            'password' => $password,
        ]);

        $authRequest = new AuthRequest($username, $password);
        $authResponse = $this->authService->register($authRequest);

        $this->assertInstanceOf(AuthResponse::class, $authResponse);
        $this->assertFalse($authResponse->isLoggedIn());
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}


This test file covers the following scenarios:

1. Successful login with a valid username and password.
2. Failed login with an invalid username or password.
3. Successful registration with a new username and password.
4. Failed registration when the username already exists.

Each test method uses Mockery to mock the `AuthRepository` class, allowing us to control the behavior of the database connections. The `AuthService` class is then tested with the mocked repository.