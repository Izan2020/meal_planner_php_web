<?php

namespace Data\Model;

class InsertParams {
    public function __construct(
        protected string $title,
        protected int $recipeId
    ) {}
    public function getTitle(): string {
        return $this->title;
    }

    public function getRecipeId(): int {
        return $this->recipeId;
    }
}