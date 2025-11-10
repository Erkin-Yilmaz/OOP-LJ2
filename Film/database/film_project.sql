-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 10 nov 2025 om 17:11
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `film_project`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `film_project_acteur`
--

CREATE TABLE `film_project_acteur` (
  `acteur_id` int(11) NOT NULL,
  `acteurnaam` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `film_project_acteur`
--

INSERT INTO `film_project_acteur` (`acteur_id`, `acteurnaam`) VALUES
(1, 'Tom Hanks'),
(2, 'Meryl Streep'),
(3, 'Denzel Washington'),
(4, 'Tom Hanks'),
(5, 'Meryl Streep'),
(6, 'Keanu Reeves'),
(7, 'Emily Blunt'),
(8, 'Jan Jansen');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `film_project_acteur_film`
--

CREATE TABLE `film_project_acteur_film` (
  `acteur_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `film_project_acteur_film`
--

INSERT INTO `film_project_acteur_film` (`acteur_id`, `film_id`) VALUES
(1, 1),
(1, 3),
(2, 2),
(3, 2),
(3, 4),
(8, 9);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `film_project_admin`
--

CREATE TABLE `film_project_admin` (
  `admin_id` int(11) NOT NULL,
  `gebruikersnaam` varchar(100) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `film_project_admin`
--

INSERT INTO `film_project_admin` (`admin_id`, `gebruikersnaam`, `wachtwoord`) VALUES
(2, 'testadmin', 'testpasshash');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `film_project_film`
--

CREATE TABLE `film_project_film` (
  `film_id` int(11) NOT NULL,
  `filmnaam` varchar(100) NOT NULL,
  `genre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `film_project_film`
--

INSERT INTO `film_project_film` (`film_id`, `filmnaam`, `genre`) VALUES
(1, 'Forrest Gump', 'Drama'),
(2, 'The Post', 'Historical Drama'),
(3, 'Cast Away', 'Adventure'),
(4, 'Fences', 'Drama'),
(5, 'The Great Escape', 'Action'),
(6, 'A Quiet Place', 'Horror'),
(7, 'Eternal Sunshine', 'Romance'),
(8, 'The Matrix', 'Sci-Fi'),
(9, 'test', 'hallo');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `film_project_acteur`
--
ALTER TABLE `film_project_acteur`
  ADD PRIMARY KEY (`acteur_id`);

--
-- Indexen voor tabel `film_project_acteur_film`
--
ALTER TABLE `film_project_acteur_film`
  ADD PRIMARY KEY (`acteur_id`,`film_id`),
  ADD KEY `film_id` (`film_id`);

--
-- Indexen voor tabel `film_project_admin`
--
ALTER TABLE `film_project_admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `gebruikersnaam` (`gebruikersnaam`);

--
-- Indexen voor tabel `film_project_film`
--
ALTER TABLE `film_project_film`
  ADD PRIMARY KEY (`film_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `film_project_acteur`
--
ALTER TABLE `film_project_acteur`
  MODIFY `acteur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `film_project_admin`
--
ALTER TABLE `film_project_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `film_project_film`
--
ALTER TABLE `film_project_film`
  MODIFY `film_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `film_project_acteur_film`
--
ALTER TABLE `film_project_acteur_film`
  ADD CONSTRAINT `film_project_acteur_film_ibfk_1` FOREIGN KEY (`acteur_id`) REFERENCES `film_project_acteur` (`acteur_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `film_project_acteur_film_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `film_project_film` (`film_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
