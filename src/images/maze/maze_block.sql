-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 23 2017 г., 16:41
-- Версия сервера: 5.6.33-79.0-log
-- Версия PHP: 5.6.30-pl0-gentoo

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `flimflix21_grad`
--

-- --------------------------------------------------------

--
-- Структура таблицы `maze_block`
--

CREATE TABLE `maze_block` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `block` tinyint(2) UNSIGNED NOT NULL,
  `block2` tinyint(2) UNSIGNED NOT NULL,
  `open` tinyint(1) UNSIGNED NOT NULL,
  `money` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  `opit` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  `lovushka` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `hp` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `maze_block`
--
ALTER TABLE `maze_block`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id_user`,`block`,`block2`) USING BTREE;

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `maze_block`
--
ALTER TABLE `maze_block`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
