-- phpMyAdmin SQL Dump
-- version 2.11.9.6
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 10 2009 г., 20:39
-- Версия сервера: 5.0.67
-- Версия PHP: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `radius`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bandwidth`
--

CREATE TABLE IF NOT EXISTS `bandwidth` (
  `bw_id` int(10) unsigned NOT NULL auto_increment,
  `bandwidth_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`bw_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `bandwidth`
--

INSERT INTO `bandwidth` (`bw_id`, `bandwidth_name`) VALUES
(1, 'bw-unlim'),
(2, 'bw-256'),
(3, 'bw-512'),
(4, 'bw-1024'),
(5, 'bw-1536'),
(6, 'bw-2048'),
(7, 'bw-3072'),
(8, 'bw-4096'),
(9, 'bw-5120');

-- --------------------------------------------------------

--
-- Структура таблицы `flows`
--

CREATE TABLE IF NOT EXISTS `flows` (
  `TimeStamp` int(10) unsigned NOT NULL,
  `Owner` varchar(50) NOT NULL,
  `SrcIp` varchar(15) NOT NULL,
  `SrcPort` int(10) unsigned NOT NULL,
  `DstIp` varchar(15) NOT NULL,
  `DstPort` int(10) unsigned NOT NULL,
  `Bytes` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `flows`
--

INSERT INTO `flows` (`TimeStamp`, `Owner`, `SrcIp`, `SrcPort`, `DstIp`, `DstPort`, `Bytes`) VALUES
(1256828581, 'test', '10.10.1.60', 2540, '63.245.209.93', 80, 803),
(1256828581, 'test', '212.58.226.139', 80, '10.10.1.60', 2542, 700),
(1256828581, 'test', '10.10.1.60', 2542, '212.58.226.139', 80, 785),
(1256828581, 'test', '63.245.209.93', 80, '10.10.1.60', 2540, 804),
(1256828581, 'test', '212.58.251.197', 80, '10.10.1.60', 2544, 95485),
(1256828581, 'test', '10.10.1.60', 2544, '212.58.251.197', 80, 2850),
(1256828581, 'test', '212.58.251.197', 80, '10.10.1.60', 2544, 40),
(1256828581, 'test', '10.10.1.60', 2540, '63.245.209.93', 80, 80),
(1256828581, 'test', '10.10.1.60', 2542, '212.58.226.139', 80, 80),
(1256828581, 'test', '63.245.209.93', 80, '10.10.1.60', 2540, 80),
(1256828581, 'test', '212.58.226.139', 80, '10.10.1.60', 2542, 80),
(1256828581, 'test', '74.125.87.138', 80, '10.10.1.60', 2546, 1058),
(1256828581, 'test', '10.10.1.60', 2546, '74.125.87.138', 80, 1112),
(1256828581, 'test', '10.10.1.60', 2548, '74.125.99.27', 80, 3998),
(1256828581, 'test', '74.125.99.27', 80, '10.10.1.60', 2548, 9673),
(1256828581, 'test', '10.10.1.60', 2548, '74.125.99.27', 80, 80),
(1256828581, 'test', '74.125.99.27', 80, '10.10.1.60', 2548, 80),
(1256828581, 'test', '10.10.1.60', 2546, '74.125.87.138', 80, 80),
(1256828581, 'test', '74.125.87.138', 80, '10.10.1.60', 2546, 80),
(1256828581, 'test', '217.73.200.222', 80, '10.10.1.60', 2556, 128),
(1256828581, 'test', '10.10.1.60', 2556, '217.73.200.222', 80, 766),
(1256828581, 'test', '10.10.1.60', 2552, '87.250.251.69', 80, 1751),
(1256828581, 'test', '10.10.1.60', 2552, '87.250.251.69', 80, 80),
(1256828581, 'test', '10.10.1.60', 2554, '87.250.251.69', 80, 3452),
(1256828581, 'test', '10.10.1.60', 2554, '87.250.251.69', 80, 80),
(1256828581, 'test', '217.73.200.222', 80, '10.10.1.60', 2556, 518),
(1256828586, 'test', '217.73.200.222', 80, '10.10.1.60', 2582, 606),
(1256828586, 'test', '10.10.1.60', 2582, '217.73.200.222', 80, 717),
(1256828586, 'test', '10.10.1.60', 2576, '213.180.204.190', 80, 1128),
(1256828586, 'test', '213.180.204.190', 80, '10.10.1.60', 2576, 483),
(1256828586, 'test', '10.10.1.60', 2552, '87.250.251.69', 80, 40),
(1256828586, 'test', '10.10.1.60', 2574, '93.158.134.61', 80, 752),
(1256828586, 'test', '87.250.251.69', 80, '10.10.1.60', 2552, 5797),
(1256828586, 'test', '93.158.134.61', 80, '10.10.1.60', 2574, 2788),
(1256828586, 'test', '10.10.1.60', 2578, '87.250.251.69', 80, 1757),
(1256828586, 'test', '10.10.1.60', 2578, '87.250.251.69', 80, 80),
(1256828586, 'test', '213.180.204.61', 80, '10.10.1.60', 2572, 2566),
(1256828586, 'test', '10.10.1.60', 2572, '213.180.204.61', 80, 943),
(1256828586, 'test', '10.10.1.60', 2554, '87.250.251.69', 80, 40),
(1256828586, 'test', '87.250.251.61', 80, '10.10.1.60', 2562, 79719),
(1256828586, 'test', '87.250.251.61', 80, '10.10.1.60', 2560, 12323),
(1256828586, 'test', '87.250.251.61', 80, '10.10.1.60', 2564, 15803),
(1256828586, 'test', '213.180.204.90', 80, '10.10.1.60', 2568, 798),
(1256828586, 'test', '213.180.204.90', 80, '10.10.1.60', 2566, 4208),
(1256828586, 'test', '87.250.251.69', 80, '10.10.1.60', 2554, 79140),
(1256828586, 'test', '10.10.1.60', 2568, '213.180.204.90', 80, 2247),
(1256828586, 'test', '10.10.1.60', 2566, '213.180.204.90', 80, 3128),
(1256828586, 'test', '77.88.21.61', 80, '10.10.1.60', 2570, 12746),
(1256828586, 'test', '77.88.21.61', 80, '10.10.1.60', 2558, 22331),
(1256828586, 'test', '10.10.1.60', 2570, '77.88.21.61', 80, 1885),
(1256828586, 'test', '10.10.1.60', 2558, '77.88.21.61', 80, 2632),
(1256828586, 'test', '10.10.1.60', 2562, '87.250.251.61', 80, 3188),
(1256828586, 'test', '10.10.1.60', 2560, '87.250.251.61', 80, 1571),
(1256828586, 'test', '10.10.1.60', 2564, '87.250.251.61', 80, 1611),
(1256828586, 'test', '217.73.200.222', 80, '10.10.1.60', 2582, 40),
(1256828586, 'test', '10.10.1.60', 2574, '93.158.134.61', 80, 80),
(1256828591, 'test', '87.250.251.69', 80, '10.10.1.60', 2580, 84558),
(1256828591, 'test', '10.10.1.60', 2580, '87.250.251.69', 80, 2266),
(1256828591, 'test', '10.10.1.60', 2576, '213.180.204.190', 80, 1085),
(1256828591, 'test', '213.180.204.190', 80, '10.10.1.60', 2576, 355),
(1256828591, 'test', '10.10.1.60', 2578, '87.250.251.69', 80, 40),
(1256828591, 'test', '87.250.251.69', 80, '10.10.1.60', 2578, 5797),
(1256828591, 'test', '10.10.1.60', 2564, '87.250.251.61', 80, 80),
(1256828591, 'test', '77.88.21.61', 80, '10.10.1.60', 2591, 12798),
(1256828591, 'test', '10.10.1.60', 2591, '77.88.21.61', 80, 1441),
(1256828591, 'test', '10.10.1.60', 2562, '87.250.251.61', 80, 80),
(1256828591, 'test', '77.88.21.61', 80, '10.10.1.60', 2590, 5299),
(1256828591, 'test', '10.10.1.60', 2590, '77.88.21.61', 80, 1282),
(1256828591, 'test', '77.88.21.61', 80, '10.10.1.60', 2592, 3989),
(1256828591, 'test', '10.10.1.60', 2592, '77.88.21.61', 80, 1280),
(1256828591, 'test', '10.10.1.60', 2560, '87.250.251.61', 80, 80),
(1256828591, 'test', '10.10.1.60', 2558, '77.88.21.61', 80, 80),
(1256828591, 'test', '10.10.1.60', 2570, '77.88.21.61', 80, 80),
(1256828591, 'test', '77.88.21.61', 80, '10.10.1.60', 2589, 18042),
(1256828591, 'test', '10.10.1.60', 2589, '77.88.21.61', 80, 1002),
(1256828591, 'test', '10.10.1.60', 2588, '87.250.251.61', 80, 2109),
(1256828591, 'test', '87.250.251.61', 80, '10.10.1.60', 2588, 82429),
(1256828591, 'test', '213.180.204.61', 80, '10.10.1.60', 2572, 40),
(1256828591, 'test', '10.10.1.60', 2588, '87.250.251.61', 80, 80),
(1256828591, 'test', '87.250.251.69', 80, '10.10.1.60', 2580, 40),
(1256828591, 'test', '93.158.134.61', 80, '10.10.1.60', 2574, 80),
(1256828591, 'test', '77.88.21.61', 80, '10.10.1.60', 2592, 40),
(1256828591, 'test', '77.88.21.61', 80, '10.10.1.60', 2570, 80),
(1256828591, 'test', '77.88.21.61', 80, '10.10.1.60', 2590, 40),
(1256828591, 'test', '77.88.21.61', 80, '10.10.1.60', 2591, 40),
(1256828591, 'test', '77.88.21.61', 80, '10.10.1.60', 2589, 40),
(1256828600, 'test', '77.88.21.61', 80, '10.10.1.60', 2558, 80),
(1256828600, 'test', '87.250.251.61', 80, '10.10.1.60', 2562, 80),
(1256828600, 'test', '87.250.251.61', 80, '10.10.1.60', 2560, 80),
(1256828600, 'test', '87.250.251.61', 80, '10.10.1.60', 2564, 80),
(1256828600, 'test', '10.10.1.60', 2568, '213.180.204.90', 80, 80),
(1256828600, 'test', '10.10.1.60', 2566, '213.180.204.90', 80, 80),
(1256828600, 'test', '87.250.251.61', 80, '10.10.1.60', 2588, 80),
(1256828600, 'test', '10.10.1.60', 2576, '213.180.204.190', 80, 80),
(1256828600, 'test', '217.73.200.222', 80, '10.10.1.60', 2598, 606),
(1256828600, 'test', '10.10.1.60', 2598, '217.73.200.222', 80, 722),
(1256828600, 'test', '213.180.204.90', 80, '10.10.1.60', 2568, 80),
(1256828600, 'test', '213.180.204.90', 80, '10.10.1.60', 2566, 80),
(1256828600, 'test', '10.10.1.60', 2594, '87.250.251.69', 80, 1771),
(1256828600, 'test', '10.10.1.60', 2594, '87.250.251.69', 80, 80),
(1256828600, 'test', '213.180.204.190', 80, '10.10.1.60', 2576, 80),
(1256828600, 'test', '217.73.200.222', 80, '10.10.1.60', 2598, 40),
(1256828600, 'test', '10.10.1.60', 2606, '213.180.204.90', 80, 1122),
(1256828600, 'test', '87.250.251.61', 80, '10.10.1.60', 2604, 2768),
(1256828600, 'test', '10.10.1.60', 2604, '87.250.251.61', 80, 760),
(1256828600, 'test', '213.180.204.90', 80, '10.10.1.60', 2606, 443),
(1256828600, 'test', '77.88.21.61', 80, '10.10.1.60', 2610, 2175),
(1256828600, 'test', '213.180.204.190', 80, '10.10.1.60', 2612, 443),
(1256828600, 'test', '10.10.1.60', 2610, '77.88.21.61', 80, 652),
(1256828600, 'test', '10.10.1.60', 2594, '87.250.251.69', 80, 40),
(1256828600, 'test', '10.10.1.60', 2596, '87.250.251.69', 80, 2114),
(1256828600, 'test', '87.250.251.69', 80, '10.10.1.60', 2594, 5797),
(1256828600, 'test', '87.250.251.69', 80, '10.10.1.60', 2596, 73980),
(1256828600, 'test', '87.250.251.61', 80, '10.10.1.60', 2602, 3936),
(1256828600, 'test', '10.10.1.60', 2602, '87.250.251.61', 80, 880),
(1256828600, 'test', '10.10.1.60', 2596, '87.250.251.69', 80, 80),
(1256828607, 'test', '10.10.1.60', 2604, '87.250.251.61', 80, 80),
(1256828607, 'test', '10.10.1.60', 2612, '213.180.204.190', 80, 1105),
(1256828607, 'test', '10.10.1.60', 2600, '87.250.251.61', 80, 2230),
(1256828607, 'test', '87.250.251.61', 80, '10.10.1.60', 2600, 52506),
(1256828607, 'test', '10.10.1.60', 2600, '87.250.251.61', 80, 80),
(1256828607, 'test', '10.10.1.60', 2614, '87.250.251.69', 80, 834),
(1256828607, 'test', '10.10.1.60', 2614, '87.250.251.69', 80, 80),
(1256828607, 'test', '10.10.1.60', 2610, '77.88.21.61', 80, 80),
(1256828607, 'test', '10.10.1.60', 2608, '213.180.204.90', 80, 1821),
(1256828607, 'test', '213.180.204.90', 80, '10.10.1.60', 2608, 4013),
(1256828607, 'test', '217.73.200.222', 80, '10.10.1.60', 2620, 606),
(1256828607, 'test', '10.10.1.60', 2620, '217.73.200.222', 80, 717),
(1256828607, 'test', '10.10.1.60', 2616, '87.250.251.69', 80, 1833),
(1256828607, 'test', '10.10.1.60', 2616, '87.250.251.69', 80, 80),
(1256828607, 'test', '87.250.251.61', 80, '10.10.1.60', 2602, 40),
(1256828607, 'test', '87.250.251.61', 80, '10.10.1.60', 2604, 80),
(1256828607, 'test', '87.250.251.69', 80, '10.10.1.60', 2596, 80),
(1256828607, 'test', '87.250.251.61', 80, '10.10.1.60', 2600, 40),
(1256828607, 'test', '77.88.21.61', 80, '10.10.1.60', 2610, 80),
(1256828607, 'test', '217.73.200.222', 80, '10.10.1.60', 2620, 40),
(1256828607, 'test', '10.10.1.60', 2618, '87.250.251.69', 80, 2661),
(1256828607, 'test', '10.10.1.60', 2624, '77.88.21.14', 80, 128),
(1256828607, 'test', '77.88.21.14', 80, '10.10.1.60', 2624, 128),
(1256828607, 'test', '10.10.1.60', 2618, '87.250.251.69', 80, 80),
(1256828607, 'test', '10.10.1.60', 2618, '87.250.251.69', 80, 80),
(1256828607, 'test', '10.10.1.60', 2618, '87.250.251.69', 80, 80),
(1256828607, 'test', '10.10.1.60', 2606, '213.180.204.90', 80, 1017),
(1256828607, 'test', '10.10.1.60', 2608, '213.180.204.90', 80, 1566),
(1256828607, 'test', '213.180.204.190', 80, '10.10.1.60', 2612, 355),
(1256828607, 'test', '10.10.1.60', 2616, '87.250.251.69', 80, 40),
(1256828613, 'test', '213.180.204.90', 80, '10.10.1.60', 2606, 355),
(1256828613, 'test', '213.180.204.90', 80, '10.10.1.60', 2608, 3040),
(1256828613, 'test', '217.73.200.222', 80, '10.10.1.60', 2630, 128),
(1256828613, 'test', '10.10.1.60', 2630, '217.73.200.222', 80, 775),
(1256828613, 'test', '10.10.1.60', 2612, '213.180.204.190', 80, 1000),
(1256828613, 'test', '87.250.251.69', 80, '10.10.1.60', 2616, 5837),
(1256828613, 'test', '87.250.251.69', 80, '10.10.1.60', 2614, 2920),
(1256828613, 'test', '87.250.251.61', 80, '10.10.1.60', 2622, 5786),
(1256828613, 'test', '10.10.1.60', 2622, '87.250.251.61', 80, 915),
(1256828613, 'test', '10.10.1.60', 2626, '87.250.251.69', 80, 1824),
(1256828613, 'test', '10.10.1.60', 2626, '87.250.251.69', 80, 80),
(1256828613, 'test', '10.10.1.60', 2626, '87.250.251.69', 80, 80),
(1256828613, 'test', '10.10.1.60', 2624, '77.88.21.14', 80, 40),
(1256828613, 'test', '213.180.204.90', 80, '10.10.1.60', 2606, 355),
(1256828613, 'test', '217.73.200.222', 80, '10.10.1.60', 2630, 518),
(1256828613, 'test', '87.250.251.69', 80, '10.10.1.60', 2618, 78063),
(1256828613, 'test', '10.10.1.60', 2606, '213.180.204.90', 80, 1181),
(1256828613, 'test', '87.250.251.61', 80, '10.10.1.60', 2622, 40),
(1256828613, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 3141),
(1256828613, 'test', '10.10.1.60', 2652, '77.88.21.14', 80, 812),
(1256828613, 'test', '77.88.21.14', 80, '10.10.1.60', 2652, 237),
(1256828613, 'test', '10.10.1.60', 2652, '77.88.21.14', 80, 80),
(1256828613, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 80),
(1256828613, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 80),
(1256828613, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 80),
(1256828613, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 80),
(1256828613, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 80),
(1256828613, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 80),
(1256828613, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 80),
(1256828613, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 80),
(1256828617, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 80),
(1256828617, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 80),
(1256828617, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 80),
(1256828617, 'test', '10.10.1.60', 2612, '213.180.204.190', 80, 1164),
(1256828617, 'test', '77.88.21.61', 80, '10.10.1.60', 2650, 2010),
(1256828617, 'test', '213.180.204.190', 80, '10.10.1.60', 2612, 355),
(1256828617, 'test', '87.250.251.61', 80, '10.10.1.60', 2638, 7324),
(1256828617, 'test', '10.10.1.60', 2638, '87.250.251.61', 80, 923),
(1256828617, 'test', '87.250.251.61', 80, '10.10.1.60', 2634, 4058),
(1256828617, 'test', '217.73.200.222', 80, '10.10.1.60', 2658, 606),
(1256828617, 'test', '10.10.1.60', 2634, '87.250.251.61', 80, 1503),
(1256828617, 'test', '10.10.1.60', 2658, '217.73.200.222', 80, 716),
(1256828617, 'test', '87.250.251.61', 80, '10.10.1.60', 2634, 80),
(1256828617, 'test', '10.10.1.60', 2636, '87.250.251.61', 80, 3835),
(1256828617, 'test', '10.10.1.60', 2632, '77.88.21.61', 80, 2351),
(1256828617, 'test', '87.250.251.61', 80, '10.10.1.60', 2636, 79443),
(1256828617, 'test', '77.88.21.61', 80, '10.10.1.60', 2632, 7136),
(1256828617, 'test', '10.10.1.60', 2650, '77.88.21.61', 80, 761),
(1256828617, 'test', '87.250.251.69', 80, '10.10.1.60', 2626, 5877),
(1256828617, 'test', '10.10.1.60', 2608, '213.180.204.90', 80, 3359),
(1256828617, 'test', '10.10.1.60', 2654, '87.250.251.69', 80, 1783),
(1256828617, 'test', '10.10.1.60', 2654, '87.250.251.69', 80, 80),
(1256828617, 'test', '10.10.1.60', 2654, '87.250.251.69', 80, 80),
(1256828617, 'test', '10.10.1.60', 2650, '77.88.21.61', 80, 80),
(1256828617, 'test', '10.10.1.60', 2606, '213.180.204.90', 80, 1022),
(1256828617, 'test', '87.250.251.61', 80, '10.10.1.60', 2634, 40),
(1256828617, 'test', '87.250.251.61', 80, '10.10.1.60', 2638, 40),
(1256828617, 'test', '213.180.204.90', 80, '10.10.1.60', 2606, 355),
(1256828617, 'test', '217.73.200.222', 80, '10.10.1.60', 2658, 40),
(1256828617, 'test', '10.10.1.60', 2628, '87.250.251.69', 80, 40),
(1256828619, 'test', '87.250.251.69', 80, '10.10.1.60', 2628, 127341),
(1256828619, 'test', '77.88.21.61', 80, '10.10.1.60', 2648, 5818),
(1256828619, 'test', '77.88.21.61', 80, '10.10.1.60', 2646, 8164),
(1256828619, 'test', '77.88.21.61', 80, '10.10.1.60', 2644, 5718),
(1256828619, 'test', '77.88.21.61', 80, '10.10.1.60', 2642, 8540),
(1256828619, 'test', '77.88.21.61', 80, '10.10.1.60', 2640, 9340),
(1256828619, 'test', '10.10.1.60', 2648, '77.88.21.61', 80, 1224),
(1256828619, 'test', '10.10.1.60', 2646, '77.88.21.61', 80, 2338),
(1256828619, 'test', '10.10.1.60', 2644, '77.88.21.61', 80, 1784),
(1256828619, 'test', '10.10.1.60', 2642, '77.88.21.61', 80, 2364),
(1256828619, 'test', '10.10.1.60', 2640, '77.88.21.61', 80, 2358),
(1256828619, 'test', '213.180.204.90', 80, '10.10.1.60', 2608, 7460),
(1256828619, 'test', '10.10.1.60', 2612, '213.180.204.190', 80, 1005),
(1256828619, 'test', '213.180.204.190', 80, '10.10.1.60', 2612, 355),
(1256828619, 'test', '87.250.251.61', 80, '10.10.1.60', 2664, 2652),
(1256828619, 'test', '87.250.251.69', 80, '10.10.1.60', 2654, 5837),
(1256828619, 'test', '87.250.251.69', 80, '10.10.1.60', 2656, 100310),
(1256828619, 'test', '10.10.1.60', 2664, '87.250.251.61', 80, 765),
(1256828619, 'test', '10.10.1.60', 2656, '87.250.251.69', 80, 2554),
(1256828619, 'test', '77.88.21.61', 80, '10.10.1.60', 2632, 6131),
(1256828619, 'test', '87.250.251.61', 80, '10.10.1.60', 2660, 5326),
(1256828619, 'test', '10.10.1.60', 2632, '77.88.21.61', 80, 1269),
(1256828619, 'test', '10.10.1.60', 2660, '87.250.251.61', 80, 961),
(1256828619, 'test', '10.10.1.60', 2648, '77.88.21.61', 80, 80),
(1256828619, 'test', '10.10.1.60', 2656, '87.250.251.69', 80, 80),
(1256828619, 'test', '10.10.1.60', 2646, '77.88.21.61', 80, 80),
(1256828619, 'test', '10.10.1.60', 2642, '77.88.21.61', 80, 80),
(1256828619, 'test', '87.250.251.61', 80, '10.10.1.60', 2662, 5090),
(1256828619, 'test', '10.10.1.60', 2662, '87.250.251.61', 80, 868),
(1256828619, 'test', '10.10.1.60', 2644, '77.88.21.61', 80, 80);

-- --------------------------------------------------------

--
-- Структура таблицы `hourlystatin`
--

CREATE TABLE IF NOT EXISTS `hourlystatin` (
  `id` bigint(10) NOT NULL auto_increment,
  `date` varchar(10) NOT NULL default '',
  `hour` varchar(10) NOT NULL default '',
  `owner` varchar(150) NOT NULL default '',
  `HTTP` bigint(21) NOT NULL default '0',
  `HTTPS` bigint(21) NOT NULL default '0',
  `SSH` bigint(21) NOT NULL default '0',
  `ICQ` bigint(21) NOT NULL default '0',
  `SMTP` bigint(21) NOT NULL default '0',
  `SSMTP` bigint(21) NOT NULL default '0',
  `POP3` bigint(21) NOT NULL default '0',
  `POP3S` bigint(21) NOT NULL default '0',
  `IMAP` bigint(21) NOT NULL default '0',
  `IMAPS` bigint(21) NOT NULL default '0',
  `DNS` bigint(21) NOT NULL default '0',
  `Other` bigint(21) NOT NULL default '0',
  `All` bigint(21) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `hourlystatin`
--


-- --------------------------------------------------------

--
-- Структура таблицы `hourlystatout`
--

CREATE TABLE IF NOT EXISTS `hourlystatout` (
  `id` bigint(10) NOT NULL auto_increment,
  `date` varchar(10) NOT NULL default '',
  `hour` varchar(10) NOT NULL default '',
  `owner` varchar(150) NOT NULL default '',
  `HTTP` bigint(21) NOT NULL default '0',
  `HTTPS` bigint(21) NOT NULL default '0',
  `SSH` bigint(21) NOT NULL default '0',
  `ICQ` bigint(21) NOT NULL default '0',
  `SMTP` bigint(21) NOT NULL default '0',
  `SSMTP` bigint(21) NOT NULL default '0',
  `POP3` bigint(21) NOT NULL default '0',
  `POP3S` bigint(21) NOT NULL default '0',
  `IMAP` bigint(21) NOT NULL default '0',
  `IMAPS` bigint(21) NOT NULL default '0',
  `DNS` bigint(21) NOT NULL default '0',
  `Other` bigint(21) NOT NULL default '0',
  `All` bigint(21) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `hourlystatout`
--


-- --------------------------------------------------------

--
-- Структура таблицы `radcheck`
--

CREATE TABLE IF NOT EXISTS `radcheck` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(64) NOT NULL default '',
  `attribute` varchar(32) NOT NULL default '',
  `op` char(2) NOT NULL default '==',
  `value` varchar(253) NOT NULL default '',
  `name` varchar(40) default NULL,
  `lastname` varchar(40) default NULL,
  `allow_tcp_port` text NOT NULL,
  `allow_udp_port` text NOT NULL,
  `limit` bigint(20) NOT NULL default '0',
  `out_limit` int(3) NOT NULL default '20',
  `bandwidth` int(10) unsigned default NULL,
  `limit_type` enum('limited','unlimited') NOT NULL default 'limited',
  `status` enum('working','blocked','limit_expire','local_only','deleted') NOT NULL default 'working',
  `bonus` bigint(20) NOT NULL default '0',
  `admin` char(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`(32))
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `radcheck`
--

INSERT INTO `radcheck` (`id`, `username`, `attribute`, `op`, `value`, `name`, `lastname`, `allow_tcp_port`, `allow_udp_port`, `limit`, `out_limit`, `bandwidth`, `limit_type`, `status`, `bonus`, `admin`) VALUES
(1, 'test', 'Cleartext-Password', ':=', 'test123', '', '', '{ 80 }', '{ 53 }', 68157440, 20, 3, 'limited', 'working', 0, '1'),
(2, 'Mihan', 'Cleartext-Password', ':=', '123', '', '', '{ 80 }', '{ 53 }', 20971520, 20, 3, 'limited', 'working', 0, '0'),
(7, 'test123', 'Cleartext-Password', ':=', '1', 'name', 'lastname', '*', '*', 104857600, 30, 3, 'limited', 'working', 0, '0'),
(6, 'test888', 'Cleartext-Password', ':=', '1', 'Ð¸Ð¼Ñ', 'Ñ„Ð°Ð¼Ð¸Ð»Ð¸Ñ', '{ 80 }', '{ 53 }', 0, 0, 5, 'unlimited', 'local_only', 0, '0');

-- --------------------------------------------------------

--
-- Структура таблицы `radgroupcheck`
--

CREATE TABLE IF NOT EXISTS `radgroupcheck` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `groupname` varchar(64) NOT NULL default '',
  `attribute` varchar(32) NOT NULL default '',
  `op` char(2) NOT NULL default '==',
  `value` varchar(253) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `groupname` (`groupname`(32))
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `radgroupcheck`
--


-- --------------------------------------------------------

--
-- Структура таблицы `radgroupreply`
--

CREATE TABLE IF NOT EXISTS `radgroupreply` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `groupname` varchar(64) NOT NULL default '',
  `attribute` varchar(32) NOT NULL default '',
  `op` char(2) NOT NULL default '=',
  `value` varchar(253) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `groupname` (`groupname`(32))
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `radgroupreply`
--

INSERT INTO `radgroupreply` (`id`, `groupname`, `attribute`, `op`, `value`) VALUES
(1, 'users', 'Framed-Protocol', ':=', 'PPP'),
(2, 'users', 'Framed-IP-Netmask', ':=', '255.255.255.255');

-- --------------------------------------------------------

--
-- Структура таблицы `radpostauth`
--

CREATE TABLE IF NOT EXISTS `radpostauth` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(64) NOT NULL default '',
  `pass` varchar(64) NOT NULL default '',
  `reply` varchar(32) NOT NULL default '',
  `authdate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=138 ;

--
-- Дамп данных таблицы `radpostauth`
--

INSERT INTO `radpostauth` (`id`, `username`, `pass`, `reply`, `authdate`) VALUES
(1, 'test', '', 'Access-Accept', '2009-07-10 17:35:09'),
(2, 'test', '', 'Access-Accept', '2009-07-10 18:40:14'),
(3, 'test', '', 'Access-Accept', '2009-07-10 19:09:20'),
(4, 'test', '', 'Access-Accept', '2009-07-13 11:28:10'),
(5, 'test', '', 'Access-Accept', '2009-07-13 11:34:40'),
(6, 'test', 'test1', 'Access-Accept', '2009-07-13 11:35:41'),
(7, 'test', '', 'Access-Accept', '2009-07-13 11:49:55'),
(8, 'test', '', 'Access-Accept', '2009-07-13 11:57:30'),
(9, 'test', '', 'Access-Accept', '2009-07-13 14:25:02'),
(10, 'test', '', 'Access-Accept', '2009-07-14 14:14:01'),
(11, 'test', '', 'Access-Accept', '2009-07-14 14:20:16'),
(12, 'test', '', 'Access-Accept', '2009-07-15 18:09:21'),
(13, 'test', '', 'Access-Accept', '2009-07-15 18:18:53'),
(14, 'test', '', 'Access-Accept', '2009-07-16 11:26:31'),
(15, 'test', '', 'Access-Accept', '2009-07-20 11:07:40'),
(16, 'test', '', 'Access-Accept', '2009-07-20 11:14:40'),
(17, 'test', '', 'Access-Accept', '2009-07-20 11:18:29'),
(18, 'test', '', 'Access-Accept', '2009-07-20 12:49:20'),
(19, 'test', '', 'Access-Accept', '2009-07-21 11:35:20'),
(20, 'test', '', 'Access-Accept', '2009-07-22 11:06:20'),
(21, 'test', '', 'Access-Accept', '2009-07-22 12:18:36'),
(22, 'test', '', 'Access-Accept', '2009-07-22 18:22:40'),
(23, 'test', '', 'Access-Accept', '2009-07-22 18:27:10'),
(24, 'test', '', 'Access-Accept', '2009-07-22 18:31:35'),
(25, 'test', '', 'Access-Accept', '2009-07-23 10:50:29'),
(26, 'test', '', 'Access-Accept', '2009-07-23 11:38:44'),
(27, 'test', '', 'Access-Accept', '2009-07-23 11:40:02'),
(28, 'test', '', 'Access-Accept', '2009-07-23 13:51:41'),
(29, 'test', '', 'Access-Accept', '2009-07-23 17:29:43'),
(30, 'test', '', 'Access-Accept', '2009-07-23 18:13:33'),
(31, 'test', '', 'Access-Accept', '2009-07-24 10:47:15'),
(32, 'test', '', 'Access-Accept', '2009-07-24 13:48:20'),
(33, 'test', '', 'Access-Accept', '2009-07-24 15:12:36'),
(34, 'test', '', 'Access-Accept', '2009-07-24 15:14:06'),
(35, 'test', '', 'Access-Accept', '2009-07-24 15:18:00'),
(36, 'test', '', 'Access-Accept', '2009-07-24 15:23:35'),
(37, 'test', '', 'Access-Accept', '2009-07-24 15:24:59'),
(38, 'test', '', 'Access-Accept', '2009-07-24 15:26:19'),
(39, 'test', '', 'Access-Accept', '2009-07-24 15:27:23'),
(40, 'test', '', 'Access-Accept', '2009-07-24 16:10:24'),
(41, 'test', '', 'Access-Accept', '2009-07-24 16:15:02'),
(42, 'test', '', 'Access-Accept', '2009-07-24 16:35:24'),
(43, 'test', '', 'Access-Accept', '2009-07-24 16:39:01'),
(44, 'test', '', 'Access-Accept', '2009-07-24 16:48:05'),
(45, 'test', '', 'Access-Accept', '2009-07-24 16:50:55'),
(46, 'test', '', 'Access-Accept', '2009-07-24 16:52:41'),
(47, 'test', '', 'Access-Accept', '2009-07-24 16:54:41'),
(48, 'test', '', 'Access-Accept', '2009-07-24 18:55:13'),
(49, 'test', '', 'Access-Accept', '2009-07-27 10:28:32'),
(50, 'test', '', 'Access-Accept', '2009-07-27 11:27:14'),
(51, 'test', '', 'Access-Accept', '2009-07-27 11:30:22'),
(52, 'test', '', 'Access-Accept', '2009-07-27 11:31:43'),
(53, 'Mihan', '', 'Access-Accept', '2009-07-28 12:50:17'),
(54, 'Mihan', '', 'Access-Accept', '2009-07-28 12:54:03'),
(55, 'Mihan', '', 'Access-Accept', '2009-07-29 12:44:25'),
(56, 'Mihan', '', 'Access-Accept', '2009-07-30 12:58:37'),
(57, 'Mihan', '', 'Access-Accept', '2009-07-30 16:38:40'),
(58, 'Mihan', '', 'Access-Accept', '2009-07-31 14:40:05'),
(59, 'Mihan', '', 'Access-Accept', '2009-08-05 13:03:31'),
(60, 'Mihan', '', 'Access-Accept', '2009-08-06 16:17:47'),
(61, 'Mihan', '', 'Access-Accept', '2009-08-07 20:28:54'),
(62, 'Mihan', '', 'Access-Accept', '2009-08-10 12:08:56'),
(63, 'Mihan', '', 'Access-Accept', '2009-08-10 13:39:59'),
(64, 'test', '', 'Access-Accept', '2009-08-10 17:59:51'),
(65, 'Mihan', '', 'Access-Accept', '2009-08-11 12:48:58'),
(66, 'Mihan', '', 'Access-Accept', '2009-08-12 10:59:43'),
(67, 'Mihan', '', 'Access-Accept', '2009-08-12 13:21:55'),
(68, 'Mihan', '', 'Access-Accept', '2009-08-12 16:34:50'),
(69, 'Mihan', '', 'Access-Accept', '2009-08-12 19:28:23'),
(70, 'Mihan', '', 'Access-Accept', '2009-08-12 20:29:26'),
(71, 'Mihan', '', 'Access-Accept', '2009-08-12 21:07:59'),
(72, 'Mihan', '', 'Access-Accept', '2009-08-12 21:46:32'),
(73, 'Mihan', '', 'Access-Accept', '2009-08-12 22:25:05'),
(74, 'Mihan', '', 'Access-Accept', '2009-08-12 23:03:38'),
(75, 'Mihan', '', 'Access-Accept', '2009-08-12 23:42:11'),
(76, 'Mihan', '', 'Access-Accept', '2009-08-13 00:20:44'),
(77, 'Mihan', '', 'Access-Accept', '2009-08-13 00:59:18'),
(78, 'Mihan', '', 'Access-Accept', '2009-08-13 01:37:51'),
(79, 'Mihan', '', 'Access-Accept', '2009-08-13 02:16:24'),
(80, 'Mihan', '', 'Access-Accept', '2009-08-13 02:54:57'),
(81, 'Mihan', '', 'Access-Accept', '2009-08-13 03:33:30'),
(82, 'Mihan', '', 'Access-Accept', '2009-08-13 04:19:33'),
(83, 'Mihan', '', 'Access-Accept', '2009-08-13 04:58:06'),
(84, 'Mihan', '', 'Access-Accept', '2009-08-13 05:36:39'),
(85, 'Mihan', '', 'Access-Accept', '2009-08-13 06:15:12'),
(86, 'Mihan', '', 'Access-Accept', '2009-08-13 06:53:45'),
(87, 'Mihan', '', 'Access-Accept', '2009-08-13 07:32:18'),
(88, 'Mihan', '', 'Access-Accept', '2009-08-13 08:33:21'),
(89, 'Mihan', '', 'Access-Accept', '2009-08-13 09:11:54'),
(90, 'Mihan', '', 'Access-Accept', '2009-08-13 09:50:27'),
(91, 'Mihan', '', 'Access-Accept', '2009-08-13 10:51:14'),
(92, 'Mihan', '', 'Access-Accept', '2009-08-13 11:49:26'),
(93, 'Mihan', '', 'Access-Accept', '2009-08-13 13:05:29'),
(94, 'Mihan', '', 'Access-Accept', '2009-08-13 13:48:27'),
(95, 'test', '', 'Access-Accept', '2009-08-19 13:11:21'),
(96, 'Mihan', '', 'Access-Accept', '2009-08-25 13:09:55'),
(97, 'test', '', 'Access-Accept', '2009-08-27 18:31:23'),
(98, 'Mihan', '', 'Access-Accept', '2009-08-28 13:46:16'),
(99, 'Mihan', '', 'Access-Accept', '2009-08-28 15:32:19'),
(100, 'test', '', 'Access-Accept', '2009-08-28 17:32:59'),
(101, 'Mihan', '', 'Access-Accept', '2009-08-31 13:08:49'),
(102, 'Mihan', '', 'Access-Accept', '2009-08-31 14:54:52'),
(103, 'test', '', 'Access-Accept', '2009-09-16 14:01:02'),
(104, 'Mihan', '', 'Access-Accept', '2009-09-23 14:03:37'),
(105, 'Mihan', '', 'Access-Accept', '2009-09-23 14:49:40'),
(106, 'Mihan', '', 'Access-Accept', '2009-09-23 15:28:13'),
(107, 'Mihan', '', 'Access-Accept', '2009-09-23 16:29:16'),
(108, 'Mihan', '', 'Access-Accept', '2009-09-24 15:22:49'),
(109, 'Mihan', '', 'Access-Accept', '2009-09-24 17:23:52'),
(110, 'Mihan', '', 'Access-Accept', '2009-09-24 19:20:54'),
(111, 'test', '', 'Access-Accept', '2009-09-28 11:36:27'),
(112, 'test', '', 'Access-Accept', '2009-09-28 11:45:13'),
(113, 'test', '', 'Access-Accept', '2009-09-29 11:35:27'),
(114, 'test', '', 'Access-Accept', '2009-09-29 11:51:45'),
(115, 'test', '', 'Access-Accept', '2009-09-29 11:55:40'),
(116, 'test', '', 'Access-Accept', '2009-09-29 12:05:59'),
(117, 'test', '', 'Access-Accept', '2009-09-29 13:32:26'),
(118, 'test', '', 'Access-Accept', '2009-09-30 10:26:48'),
(119, 'test', '', 'Access-Accept', '2009-09-30 10:48:51'),
(120, 'test', '', 'Access-Accept', '2009-09-30 11:22:31'),
(121, 'test', '', 'Access-Accept', '2009-09-30 11:40:49'),
(122, 'test', '', 'Access-Accept', '2009-09-30 15:14:17'),
(123, 'test', '', 'Access-Accept', '2009-09-30 15:38:54'),
(124, 'test', '', 'Access-Accept', '2009-10-02 12:20:19'),
(125, 'test', '', 'Access-Accept', '2009-10-05 10:57:55'),
(126, 'test', '', 'Access-Accept', '2009-10-08 19:25:29'),
(127, 'test', '', 'Access-Accept', '2009-10-16 12:27:48'),
(128, 'test', '', 'Access-Accept', '2009-10-16 15:25:05'),
(129, 'test', '', 'Access-Accept', '2009-10-19 15:27:10'),
(130, 'test', '', 'Access-Accept', '2009-10-19 15:40:46'),
(131, 'test', '', 'Access-Accept', '2009-10-19 15:42:19'),
(132, 'test', '', 'Access-Accept', '2009-10-21 12:57:30'),
(133, 'test', '', 'Access-Accept', '2009-10-21 13:04:38'),
(134, 'test', '', 'Access-Accept', '2009-10-21 13:29:01'),
(135, 'test', '', 'Access-Accept', '2009-10-21 13:31:37'),
(136, 'test', '', 'Access-Accept', '2009-10-21 13:34:53'),
(137, 'test', '', 'Access-Accept', '2009-10-28 14:45:54');

-- --------------------------------------------------------

--
-- Структура таблицы `radreply`
--

CREATE TABLE IF NOT EXISTS `radreply` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(64) NOT NULL default '',
  `attribute` varchar(32) NOT NULL default '',
  `op` char(2) NOT NULL default '=',
  `value` varchar(253) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`(32))
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `radreply`
--

INSERT INTO `radreply` (`id`, `username`, `attribute`, `op`, `value`) VALUES
(1, 'test', 'Framed-IP-Address', ':=', '10.10.1.60'),
(2, 'Mihan', 'Framed-IP-Address', ':=', '10.10.1.61'),
(7, 'test123', 'Framed-IP-Address', ':=', '10.10.1.62'),
(6, 'test888', 'Framed-IP-Address', ':=', '10.10.1.63');

-- --------------------------------------------------------

--
-- Структура таблицы `radusergroup`
--

CREATE TABLE IF NOT EXISTS `radusergroup` (
  `username` varchar(64) NOT NULL default '',
  `groupname` varchar(64) NOT NULL default '',
  `priority` int(11) NOT NULL default '1',
  KEY `username` (`username`(32))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `radusergroup`
--

INSERT INTO `radusergroup` (`username`, `groupname`, `priority`) VALUES
('test', 'users', 1),
('Mihan', 'users', 1),
('test123', 'users', 1),
('test888', 'users1', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `SessId` bigint(21) NOT NULL auto_increment,
  `UserName` varchar(64) NOT NULL default '',
  `StartTime` bigint(30) NOT NULL default '0',
  `StopTime` bigint(30) NOT NULL default '0',
  `SessionTime` bigint(10) NOT NULL default '0',
  `InternetIn` bigint(14) NOT NULL default '0',
  `InternetOut` bigint(14) NOT NULL default '0',
  `LocalIn` bigint(14) NOT NULL default '0',
  `LocalOut` bigint(14) NOT NULL default '0',
  `CallingStationId` varchar(50) NOT NULL default '',
  `FramedIpAddress` varchar(50) NOT NULL default '',
  `Interface` varchar(7) NOT NULL default '',
  `Connected` char(2) NOT NULL default '',
  `Speed_in` bigint(21) NOT NULL default '0',
  `Speed_out` bigint(21) NOT NULL default '0',
  PRIMARY KEY  (`SessId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1149 ;

--
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`SessId`, `UserName`, `StartTime`, `StopTime`, `SessionTime`, `InternetIn`, `InternetOut`, `LocalIn`, `LocalOut`, `CallingStationId`, `FramedIpAddress`, `Interface`, `Connected`, `Speed_in`, `Speed_out`) VALUES
(1145, 'test', 1256744417, 1256828622, 84205, 8257380, 998660, 288849952, 4932928, '', '10.10.1.60', 'ng0', '1', 99360, 5963),
(1147, '@DELETED@', 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0, 0),
(1148, '@DELETED@', 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `sessions_1`
--

CREATE TABLE IF NOT EXISTS `sessions_1` (
  `SessId` bigint(21) NOT NULL auto_increment,
  `UserName` varchar(64) NOT NULL default '',
  `StartTime` bigint(30) NOT NULL default '0',
  `StopTime` bigint(30) NOT NULL default '0',
  `SessionTime` bigint(10) NOT NULL default '0',
  `InternetIn` bigint(14) NOT NULL default '0',
  `InternetOut` bigint(14) NOT NULL default '0',
  `LocalIn` bigint(14) NOT NULL default '0',
  `LocalOut` bigint(14) NOT NULL default '0',
  `CallingStationId` varchar(50) NOT NULL default '',
  `FramedIpAddress` varchar(50) NOT NULL default '',
  `Interface` varchar(7) NOT NULL default '',
  `Connected` char(2) NOT NULL default '',
  `Speed_in` bigint(21) NOT NULL default '0',
  `Speed_out` bigint(21) NOT NULL default '0',
  PRIMARY KEY  (`SessId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1118 ;

--
-- Дамп данных таблицы `sessions_1`
--

INSERT INTO `sessions_1` (`SessId`, `UserName`, `StartTime`, `StopTime`, `SessionTime`, `InternetIn`, `InternetOut`, `LocalIn`, `LocalOut`, `CallingStationId`, `FramedIpAddress`, `Interface`, `Connected`, `Speed_in`, `Speed_out`) VALUES
(1116, '@DELETED@', 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0, 0),
(1117, '@DELETED@', 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `sessions_2`
--

CREATE TABLE IF NOT EXISTS `sessions_2` (
  `SessId` bigint(21) NOT NULL auto_increment,
  `UserName` varchar(64) NOT NULL default '',
  `StartTime` bigint(30) NOT NULL default '0',
  `StopTime` bigint(30) NOT NULL default '0',
  `SessionTime` bigint(10) NOT NULL default '0',
  `InternetIn` bigint(14) NOT NULL default '0',
  `InternetOut` bigint(14) NOT NULL default '0',
  `LocalIn` bigint(14) NOT NULL default '0',
  `LocalOut` bigint(14) NOT NULL default '0',
  `CallingStationId` varchar(50) NOT NULL default '',
  `FramedIpAddress` varchar(50) NOT NULL default '',
  `Interface` varchar(7) NOT NULL default '',
  `Connected` char(2) NOT NULL default '',
  `Speed_in` bigint(21) NOT NULL default '0',
  `Speed_out` bigint(21) NOT NULL default '0',
  PRIMARY KEY  (`SessId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1118 ;

--
-- Дамп данных таблицы `sessions_2`
--

INSERT INTO `sessions_2` (`SessId`, `UserName`, `StartTime`, `StopTime`, `SessionTime`, `InternetIn`, `InternetOut`, `LocalIn`, `LocalOut`, `CallingStationId`, `FramedIpAddress`, `Interface`, `Connected`, `Speed_in`, `Speed_out`) VALUES
(1116, '@DELETED@', 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0, 0),
(1117, '@DELETED@', 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `subnets`
--

CREATE TABLE IF NOT EXISTS `subnets` (
  `id` int(10) NOT NULL auto_increment,
  `Subnet_Address` varchar(15) NOT NULL default '',
  `NetMask` varchar(15) NOT NULL,
  `Masklength` int(2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `subnets`
--

INSERT INTO `subnets` (`id`, `Subnet_Address`, `NetMask`, `Masklength`) VALUES
(6, '90.156.208.0', '255.255.255.0', 24),
(8, '10.1.1.1', '255.255.255.255', 32),
(9, '10.2.2.2', '255.255.255.255', 32),
(10, '10.3.3.3', '255.255.255.255', 32),
(11, '90.156.207.0', '255.255.255.0', 24);

-- --------------------------------------------------------

--
-- Структура таблицы `vpnmsgroupreply`
--

CREATE TABLE IF NOT EXISTS `vpnmsgroupreply` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `groupname` varchar(64) NOT NULL default '',
  `allow_tcp_port` text NOT NULL,
  `allow_udp_port` text NOT NULL,
  `limit` bigint(20) NOT NULL default '0',
  `out_limit` int(3) NOT NULL default '20',
  `bandwidth` int(10) unsigned default NULL,
  `limit_type` enum('limited','unlimited') NOT NULL default 'limited',
  `status` enum('working','blocked','local_only') NOT NULL default 'working',
  PRIMARY KEY  (`id`),
  KEY `username` (`groupname`(32))
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `vpnmsgroupreply`
--

INSERT INTO `vpnmsgroupreply` (`id`, `groupname`, `allow_tcp_port`, `allow_udp_port`, `limit`, `out_limit`, `bandwidth`, `limit_type`, `status`) VALUES
(1, 'users', '{ 80 }', '{ 53 }', 68157440, 20, 3, 'limited', 'working'),
(2, 'users1', '{ 80 }', '{ 53 }', 5001708, 20, 4, 'limited', 'working');

-- --------------------------------------------------------

--
-- Структура таблицы `work`
--

CREATE TABLE IF NOT EXISTS `work` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(250) NOT NULL,
  `data` varchar(250) NOT NULL default '',
  `operation` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Дамп данных таблицы `work`
--

INSERT INTO `work` (`id`, `username`, `data`, `operation`) VALUES
(15, 'test', '', 'kill'),
(14, 'test', '', 'kill'),
(13, 'test', '', 'kill'),
(12, 'test', '', 'kill'),
(16, 'test', '', 'kill');
