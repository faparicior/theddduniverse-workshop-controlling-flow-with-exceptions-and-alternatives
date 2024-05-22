<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

final class Description
{
    public function __construct(private string $value)
    {
        $this->validate($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    private function validate(string $value): void
    {
        if (mb_strlen($value) === 0) {
            throw new \InvalidArgumentException('Description empty');
        }

        if (mb_strlen($value) > 200) {
            throw new \InvalidArgumentException(
                sprintf('Description has more than 200 characters: Has %d characters', mb_strlen($value))
            );
        }
    }
}
