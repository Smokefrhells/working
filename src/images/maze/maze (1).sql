-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 23 2017 г., 16:40
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
-- Структура таблицы `maze`
--

CREATE TABLE `maze` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `lvl` tinyint(2) UNSIGNED NOT NULL DEFAULT '1',
  `hp` smallint(5) UNSIGNED NOT NULL,
  `hp_max` smallint(5) UNSIGNED NOT NULL,
  `tip` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `kluch` tinyint(2) UNSIGNED NOT NULL,
  `maze` tinyint(2) UNSIGNED NOT NULL,
  `sunduk` tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  `lovushka` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `maze`
--
ALTER TABLE `maze`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `maze`
--
ALTER TABLE `maze`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
