<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Demo\App\Advertisement\Domain\Errors\ProblemHashingPasswordError;
use Demo\App\Common\Result;
use Exception;
use SensitiveParameter;

final readonly class Password
{

    private function __construct(
        #[SensitiveParameter]
        private string $value
    )
    {
    }

    /**
     * @throws Exception
     */
    public static function fromPlainPassword(
        #[SensitiveParameter]
        string $password)
    : Result
    {
        $hash = password_hash($password, PASSWORD_ARGON2I);
        if(null === $hash || false === $hash) {
            return ProblemHashingPasswordError::build();
        }
        return Result::success(new self($hash));
    }

    public static function fromEncryptedPassword(
        #[SensitiveParameter]
        string $password
    ): Result
    {
        return Result::success(new self($password));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isValidatedWith(
        #[SensitiveParameter]
        string $password
    ): bool
    {
        $hashSpecs = password_get_info($this->value);

        if(null === $hashSpecs['algo']) {
            return $this->value === md5($password);
        }

        return password_verify($password, $this->value);
    }
}
