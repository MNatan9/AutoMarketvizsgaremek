-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3306
-- Létrehozás ideje: 2026. Nov 05. 19:49
-- Kiszolgáló verziója: 5.7.24
-- PHP verzió: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `automarket`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jarmu_kepek`
--

DROP TABLE IF EXISTS `jarmu_kepek`;
CREATE TABLE `jarmu_kepek` (
  `id` int(11) NOT NULL,
  `jarmu_id` int(11) NOT NULL,
  `kep_url` varchar(255) NOT NULL,
  `sorrend` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `jarmu_kepek`
--
ALTER TABLE `jarmu_kepek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jarmu_id` (`jarmu_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `jarmu_kepek`
--
ALTER TABLE `jarmu_kepek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `jarmu_kepek`
--
ALTER TABLE `jarmu_kepek`
  ADD CONSTRAINT `jarmu_kepek_ibfk_1` FOREIGN KEY (`jarmu_id`) REFERENCES `jarmuvek` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
