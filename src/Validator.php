<?php
// Collects error messages for form input. Each check returns $this so calls can be chained.

class Validator
{
    private array $messages = [];

    public function required(string $value, string $label): self
    {
        if (trim($value) === '') {
            $this->add("$label is required.");
        }
        return $this;
    }

    public function maxLength(string $value, int $max, string $label): self
    {
        if (mb_strlen($value) > $max) {
            $this->add("$label must be $max characters or fewer.");
        }
        return $this;
    }

    public function oneOf(string $value, array $allowed, string $label): self
    {
        if (!in_array($value, $allowed, true)) {
            $this->add("$label is not valid.");
        }
        return $this;
    }

    public function add(string $message): self
    {
        $this->messages[] = $message;
        return $this;
    }

    public function passes(): bool
    {
        return $this->messages === [];
    }

    public function errors(): array
    {
        return $this->messages;
    }
}
