-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3306
-- Létrehozás ideje: 2026. Nov 05. 19:41
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
-- Tábla szerkezet ehhez a táblához `jarmuvek`
--

DROP TABLE IF EXISTS `jarmuvek`;
CREATE TABLE `jarmuvek` (
  `id` int(11) NOT NULL,
  `marka` varchar(50) NOT NULL,
  `modell` varchar(50) NOT NULL,
  `tipus` varchar(20) NOT NULL,
  `evjarat` int(11) NOT NULL,
  `ar` int(11) NOT NULL,
  `uzemanyag` varchar(30) DEFAULT NULL,
  `futott_km` int(11) DEFAULT NULL,
  `helyseg` varchar(50) DEFAULT NULL,
  `kep_url` varchar(255) DEFAULT NULL,
  `tulaj_id` int(11) DEFAULT NULL,
  `allapot` enum('aktiv','eladva','rejtett','ellenorzes') DEFAULT 'aktiv',
  `feltoltes_datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `jarmuvek`
--
ALTER TABLE `jarmuvek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jarmu_tulaj` (`tulaj_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `jarmuvek`
--
ALTER TABLE `jarmuvek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `jarmuvek`
--
ALTER TABLE `jarmuvek`
  ADD CONSTRAINT `fk_jarmu_tulaj` FOREIGN KEY (`tulaj_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
