<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>
<p align="center">Based on Laravel 5.4</p>

## Реализовано

- Базовые действия с постами (создание, просмотр, редактирование, удаление). Таблицы Persons и Posts связаны с помощью *Eloquent: Relationships*.
- Заполнение таблиц данными с помощью Faker (100'000 строк в Posts и 100 строк в Persons)
- Фильтрация выводимых данных (*Eloquent: Query Scopes*)
- Настраиваемый постраничный вывод
- Вывод данных в JSON формате (тоже постранично)
- Отображение возникающих ошибок. Например, если пост по ссылке не найден, будет брошено исключение *HttpNotFoundException* (error 404) и показана страница с ошибкой

## *"Вопрос?" - Ответ*

- *"Как можно улучшить проект?"*
  - Перейти на VueJS. Это даст возможность удобнее работать с frontend. Обращаться к базе через API, реализованное на *Lumen* (он быстрее, чем *Laravel*)
- *"Почему я так не сделал?"*
  - Потому что этот framework я изучил на уровне теории и сейчас перешел к практике. Поэтому реализация этого не очень сложного задания могла занять намного больше времени.
- *"Почему так медленно?"*
  - За этот учебный год мне не приходилось вести разработку на *Laravel*. Большую часть времени я потратил на изучение *Android* (*IT School Samsung*). Не так давно я поверхностно изучил теорию *NodeJS* и углубился в изучение *VueJS* (с использованием сборщика *WebPack*). Поэтому я подзабыл некоторые вещи, но за эти 2 дня я повторил изученное
