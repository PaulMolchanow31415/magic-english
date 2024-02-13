<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('skills.glossary', fn(BreadcrumbTrail $trail) => $trail
    ->push('Глоссарий', route('skills.glossary')));

Breadcrumbs::for('skills.dictionary.show', fn(BreadcrumbTrail $trail, string $category) => $trail
    ->parent('skills.glossary')
    ->push($category, route('skills.dictionary.show', ['category' => $category])));

Breadcrumbs::for('student.vocabularies.dashboard', fn(BreadcrumbTrail $trail) => $trail
    ->parent('skills.glossary')
    ->push('Добавленные слова', route('student.vocabularies.dashboard')));

Breadcrumbs::for('student.vocabularies.challenge', fn(BreadcrumbTrail $trail) => $trail
    ->parent('student.vocabularies.dashboard')
    ->push('Изучение слов', route('student.vocabularies.challenge')));
