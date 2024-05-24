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

    public function isError(): bool
    {
        return !$this->isSuccess;
    }

    /**
     * @throws RuntimeException
     * @return object
     */
    public function unwrap(): object
    {
        if (!$this->isSuccess) {
            throw new RuntimeException('Result is not successful, data is unavailable.');
        }

        return $this->data;
    }

    public function getError(): ?Throwable
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
