INSERT INTO category (title, symbol_code)
VALUES ('Доски и лыжи', 'boards'),
       ('Крепления', 'attachment'),
       ('Ботинки', 'boots'),
       ('Одежда', 'clothing'),
       ('Инструменты', 'tools'),
       ('Разное', 'other');

INSERT INTO user (dt_registarion, email, name, password, contacts)
VALUES (NOW(), 'rand@mail.com', 'Rand', '12345', '12345'),
       (NOW(), 'met@mail.com', 'Met', '98765', '98765'),
       (NOW(), 'tom@mail.com', 'Tom', '98765', '98765');

INSERT INTO lot (dt_add, name, category, url_img, start_cost, dt_end, step_bet, user_id, winner_id, category_id)
VALUES (NOW(), '2014 Rossignol District Snowboard', 'Доски и лыжи', 'img/lot-1.jpg', 10999, DATE_ADD(NOW(), INTERVAL 1 DAY), 1000, 1, 3, 1),
('2021-08-23', 'DC Ply Mens 2016/2017 Snowboard', 'Доски и лыжи', 'img/lot-2.jpg', 159999, DATE_ADD(NOW(), INTERVAL 2 DAY), 10000, 2, 3, 1),
('2021-08-21', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления', 'img/lot-3.jpg', 8000, DATE_ADD(NOW(), INTERVAL 1 DAY), 500, 1, 3, 2),
('2021-08-24', 'Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки', 'img/lot-4.jpg', 10999, DATE_ADD(NOW(), INTERVAL 2 DAY), 1000, 1, 3, 3),
(DATE_ADD(NOW(), INTERVAL -1 HOUR), 'Куртка для сноуборда DC Mutiny Charocal', 'Одежда', 'img/lot-5.jpg', 7500, DATE_ADD(NOW(), INTERVAL 1 DAY), 500, 2, 3, 4),
('2021-08-22', 'Маска Oakley Canopy', 'Разное', 'img/lot-6.jpg', 5400, DATE_ADD(NOW(), INTERVAL 2 DAY), 100, 2, 3, 5);


INSERT INTO bet (dt_bet, sum, user_id, lot_id)
VALUES (NOW(), 12000, 2, 1), (DATE_ADD(NOW(), INTERVAL + 1 HOUR), 14000, 2, 1);

/*получить все категории*/
SELECT * FROM category;

/*получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории*/
SELECT name, start_cost, url_img, category.title FROM lot
LEFT JOIN category ON lot.category_id = category.id
WHERE dt_add > DATE_SUB(NOW(), INTERVAL 7 DAY);

/*показать лот по его ID. Получите также название категории, к которой принадлежит лот*/
SELECT name, category.title FROM lot
LEFT JOIN category ON lot.category_id = category.id
WHERE lot.id = 3;

/*обновить название лота по его идентификатору*/
UPDATE lot
SET name = 'Rossignol District Snowboard'
WHERE id = 1;

/*получить список ставок для лота по его идентификатору с сортировкой по дате*/
SELECT sum FROM bet
JOIN lot ON bet.lot_id = lot.id
WHERE lot.id = 1
ORDER BY dt_bet ASC;
