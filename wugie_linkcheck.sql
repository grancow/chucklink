-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: sql.s17.vdl.pl
-- Czas wygenerowania: 08 Mar 2018, 19:14
-- Wersja serwera: 5.6.36
-- Wersja PHP: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `wugie_linkcheck`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `link`
--

CREATE TABLE IF NOT EXISTS `link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `link_site` text COLLATE utf8_polish_ci NOT NULL,
  `site` text COLLATE utf8_polish_ci NOT NULL,
  `linktype1_id` int(11) NOT NULL,
  `linktype2_id` int(11) NOT NULL,
  `linktype3_id` int(11) DEFAULT NULL,
  `link_date` date DEFAULT NULL,
  `link_cost` float DEFAULT NULL,
  `first_name` text COLLATE utf8_polish_ci,
  `last_name` text COLLATE utf8_polish_ci,
  `email` text COLLATE utf8_polish_ci,
  `phone` text COLLATE utf8_polish_ci,
  `info` longtext COLLATE utf8_polish_ci,
  `status` text COLLATE utf8_polish_ci,
  `aktualizacja` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `linktype1`
--

CREATE TABLE IF NOT EXISTS `linktype1` (
  `linktype1_id` int(11) NOT NULL AUTO_INCREMENT,
  `linktype1` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`linktype1_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `linktype2`
--

CREATE TABLE IF NOT EXISTS `linktype2` (
  `linktype2_id` int(11) NOT NULL AUTO_INCREMENT,
  `linktype2` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`linktype2_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `owner` int(11) NOT NULL,
  `site` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `email` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `first_name` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `last_name` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `firm` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `street` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `city` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `nip` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `added` date NOT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `email` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `surname` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `pass` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `role` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `state` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `added` date NOT NULL,
  `owner` int(11) NOT NULL,
  `activation_key` int(11) NOT NULL DEFAULT '0',
  `privileges` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_project`
--

CREATE TABLE IF NOT EXISTS `user_project` (
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
