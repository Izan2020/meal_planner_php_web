<?php

namespace Data\Model;
class SearchParams {
    public function __construct(
        protected string $diet,
        protected int $calories,
        protected string $intolerances,
    ) {}

    public function getDiet(): string {
        return $this->diet;
    }

    public function getCalories(): int {
        return $this->calories;
    }

    public function getIntolerances(): string {
        return $this->intolerances;
    }
}