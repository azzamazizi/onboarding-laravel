CREATE TABLE `users` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `email` varchar(255),
  `password` varchar(255),
  `avatar` varchar(255),
  `is_admin` boolean,
  `created_at` datetime,
  `updated_at` datetime,
  `deleted_at` datetime
);

CREATE TABLE `movies` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` varchar(255),
  `overview` text,
  `poster` varchar(255),
  `play_until` datetime,
  `created_at` datetime,
  `updated_at` datetime,
  `deleted_at` datetime
);

CREATE TABLE `movie_tags` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `movie_id` bigint NOT NULL,
  `tag_id` bigint NOT NULL,
  `created_at` datetime,
  `updated_at` datetime,
  `deleted_at` datetime
);

CREATE TABLE `tags` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(100),
  `created_at` datetime,
  `updated_at` datetime,
  `deleted_at` datetime
);

CREATE TABLE `studios` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `studio_number` integer,
  `seat_capacity` integer,
  `created_at` datetime,
  `updated_at` datetime,
  `deleted_at` datetime
);

CREATE TABLE `movie_schedules` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `movie_id` bigint NOT NULL,
  `studio_id` bigint NOT NULL,
  `start_time` varchar(255),
  `end_time` varchar(255),
  `price` double,
  `date` datetime,
  `created_at` datetime,
  `updated_at` datetime,
  `deleted_at` datetime
);

CREATE TABLE `orders` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `payment_method` enum('cash','debit'),
  `total_item_price` double,
  `created_at` datetime,
  `updated_at` datetime,
  `deleted_at` datetime
);

CREATE TABLE `order_items` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `order_id` bigint NOT NULL,
  `movie_schedule_id` bigint NOT NULL,
  `qty` integer,
  `price` double,
  `sub_total_price` double,
  `snapshots` json,
  `created_at` datetime,
  `updated_at` datetime,
  `deleted_at` datetime
);

ALTER TABLE `movie_tags` ADD FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`);

ALTER TABLE `movie_tags` ADD FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);

ALTER TABLE `movie_schedules` ADD FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`);

ALTER TABLE `movie_schedules` ADD FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`);

ALTER TABLE `orders` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `order_items` ADD FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

ALTER TABLE `order_items` ADD FOREIGN KEY (`movie_schedule_id`) REFERENCES `movie_schedules` (`id`);


-- enum('cash','debit')