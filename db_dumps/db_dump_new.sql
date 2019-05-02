-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Сен 04 2018 г., 08:06
-- Версия сервера: 10.1.31-MariaDB
-- Версия PHP: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `pr_v1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'Пользователи'),
(2, 'Администраторы');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(32) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `age` int(2) NOT NULL,
  `sex` int(1) NOT NULL,
  `money` bigint(32) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '1',
  `skype` varchar(64) DEFAULT NULL,
  `vk` varchar(64) DEFAULT NULL,
  `facebook` varchar(64) DEFAULT NULL,
  `default_avatar` smallint(1) NOT NULL DEFAULT '1',
  `tmp` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`, `name`, `age`, `sex`, `money`, `group_id`, `skype`, `vk`, `facebook`, `default_avatar`, `tmp`) VALUES
(1, 'root', 'b9be11166d72e9e3ae7fd407165e4bd2', 'root@mail.ru', 'Mister Mario', 19, 0, 1869040346, 1, 'mrmario', 'id22598953', 'id8592395892835', 0, ''),
(2, 'ivangr', '1f32aa4c9a1d2ea010adcf2348166a04', 'ivangrozniy@mail.ru', 'Иван Васильевич', 67, 0, 0, 1, NULL, NULL, NULL, 1, NULL),
(3, 'dad', 'b2ccf37a297384fd6d93783296edc0f0', 'leonid@mail.ru', 'Leonid Savich', 53, 0, 500, 2, 'ggghgghjg', 'id676767', '', 1, ''),
(4, 'rfrfhtrj', 'cd4ff32c6f4995455cb09fd1af02269b', 'rfrhtrj@mail.ru', 'Татьяна Савич', 48, 1, 9004142529760, 2, 'vhjkl', '9887', '23', 1, ''),
(5, 'sherlok', '65c0715da80d62df208c70e35675cc79', 'sherlok@mail.ru', 'Холмс Шерлок', 32, 0, 1268942626, 2, 'mholms', 'vk.com/mholms', '', 1, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
