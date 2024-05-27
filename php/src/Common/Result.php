<?php
declare(strict_types=1);

namespace Demo\App\Common;

use RuntimeException;
use Throwable;

final readonly class Result
{
    private bool $isSuccess;
    private ?object $data;
    private ?Throwable $error;
    private ?string $errorCode;

    private function __construct(bool $isSuccess, ?object $data = null, ?Throwable $error = null, ?string $errorCode = null)
    {
        $this->isSuccess = $isSuccess;
        $this->data = $data;
        $this->error = $error;
        $this->errorCode = $errorCode;
    }

    public static function success(?object $data = null): self
    {
        return new self(true, $data);
    }

    public static function failure(Throwable $error, string $errorCode = null): self
    {
        return new self(false, null, $error, $errorCode);
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function isFailure(): bool
    {
        return !$this->isSuccess;
    }

    /**
     * @throws Throwable
     * @return object
     */
    public function getOrThrow(): object
    {
        if ($this->isSuccess) {
            return $this->data;
        }

        throw $this->error;
    }

    public function exception(): ?Throwable
    {
        if ($this->isSuccess) {
            throw new RuntimeException('Result is successful, error is unavailable.');
        }

        return $this->error;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
}
