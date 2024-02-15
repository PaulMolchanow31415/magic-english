<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('skills.glossary', fn(BreadcrumbTrail $trail) => $trail
    ->push('Глоссарий', route('skills.glossary')));

Breadcrumbs::for('skills.dictionary.show', fn(BreadcrumbTrail $trail, $category) => $trail
    ->parent('skills.glossary')
    ->push($category, route('skills.dictionary.show', ['category' => $category])));

Breadcrumbs::for('student.vocabularies.dashboard', fn(BreadcrumbTrail $trail) => $trail
    ->parent('skills.glossary')
    ->push('Добавленные слова', route('student.vocabularies.dashboard')));

Breadcrumbs::for('student.vocabularies.challenge', fn(BreadcrumbTrail $trail) => $trail
    ->parent('student.vocabularies.dashboard')
    ->push('Изучение слов', route('student.vocabularies.challenge')));

Breadcrumbs::for('skills.courses', fn(BreadcrumbTrail $trail) => $trail
    ->push('Курсы', route('skills.courses')));

Breadcrumbs::for('skills.course.show', fn(BreadcrumbTrail $trail, $name) => $trail
    ->parent('skills.courses')
    ->push($name, route('skills.course.show', ['name' => $name])));

Breadcrumbs::for('student.courses.dashboard', fn(BreadcrumbTrail $trail) => $trail
    ->parent('skills.courses')
    ->push('Изучаемые курсы', route('student.courses.dashboard')));

Breadcrumbs::for('skills.self-education', fn(BreadcrumbTrail $trail) => $trail
    ->push('Самоучитель', route('skills.self-education')));

Breadcrumbs::for('skills.lesson.show', fn(BreadcrumbTrail $trail, $number) => $trail
    ->parent('skills.self-education')
    ->push($number.' Урок', route('skills.lesson.show', ['number' => $number])));

Breadcrumbs::for('student.lessons.dashboard', fn(BreadcrumbTrail $trail) => $trail
    ->parent('skills.self-education')
    ->push('Изучаемые уроки', route('student.lessons.dashboard')));

