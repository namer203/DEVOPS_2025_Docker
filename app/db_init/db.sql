-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 01. jun 2025 ob 09.39
-- Različica strežnika: 10.4.32-MariaDB
-- Različica PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Zbirka podatkov: `igra_app`
--

-- --------------------------------------------------------

--
-- Struktura tabele `mnenja`
--

CREATE TABLE `mnenja` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `mnenje` text NOT NULL,
  `datum` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `mnenja`
--

INSERT INTO `mnenja` (`id`, `user_id`, `mnenje`, `datum`) VALUES
(1, NULL, 'Rad bi dodal novo igro potapljanje ladjic.', '2025-06-01 06:59:02'),
(3, 9, 'Rad bi dodal potapljanje ladjic', '2025-06-01 07:00:39');

-- --------------------------------------------------------

--
-- Struktura tabele `rezultati`
--

CREATE TABLE `rezultati` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rezultat` int(11) NOT NULL,
  `datum` datetime DEFAULT current_timestamp(),
  `igra` varchar(50) NOT NULL DEFAULT 'reakcijaIgra'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `rezultati`
--

INSERT INTO `rezultati` (`id`, `user_id`, `rezultat`, `datum`, `igra`) VALUES
(5, 6, 313, '2025-05-31 21:33:01', 'reakcijaIgra'),
(6, 6, 362, '2025-05-31 22:10:45', 'reakcijaIgra'),
(7, 6, 32, '2025-05-31 22:35:58', 'ujemi'),
(8, 6, 492, '2025-05-31 22:43:33', 'reakcijaIgra'),
(10, 6, 30, '2025-05-31 22:46:43', 'ujemi'),
(11, 6, 328, '2025-05-31 22:51:40', 'timergame'),
(12, 9, 259, '2025-06-01 08:44:49', 'reakcijaIgra'),
(13, 9, 328, '2025-06-01 09:35:02', 'reakcijaIgra');

-- --------------------------------------------------------

--
-- Struktura tabele `uporabniki`
--

CREATE TABLE `uporabniki` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `geslo` varchar(255) NOT NULL,
  `vloga` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `uporabniki`
--

INSERT INTO `uporabniki` (`id`, `username`, `geslo`, `vloga`) VALUES
(6, 'ana', '$2y$10$bMxqjCWh.vV8V2vE6vJI5e35HnZuBCVn0mnmnuUD/dlrR2ird6AoC', 'user'),
(7, 'miha', '$2y$10$0jciLHafeok1OPeOtxH.pex5UiqMVKacA2eibYzkncWVhuJt0Pd9.', 'user'),
(8, 'nika', '$2y$10$poK56YfYxq1ypASllI.1JulbKQ4Yhihv2hjrwB0IdPOdj51IrIRO.', 'user'),
(9, 'admin', '$2y$10$KutfIFSa3.xakFcRt55c4eiJcYPy4Vv5SFUQZo9PLoKD2T2P427ny', 'admin');

--
-- Indeksi zavrženih tabel
--

--
-- Indeksi tabele `mnenja`
--
ALTER TABLE `mnenja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksi tabele `rezultati`
--
ALTER TABLE `rezultati`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rezultati_ibfk_1` (`user_id`);

--
-- Indeksi tabele `uporabniki`
--
ALTER TABLE `uporabniki`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT zavrženih tabel
--

--
-- AUTO_INCREMENT tabele `mnenja`
--
ALTER TABLE `mnenja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT tabele `rezultati`
--
ALTER TABLE `rezultati`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT tabele `uporabniki`
--
ALTER TABLE `uporabniki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Omejitve tabel za povzetek stanja
--

--
-- Omejitve za tabelo `mnenja`
--
ALTER TABLE `mnenja`
  ADD CONSTRAINT `mnenja_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `uporabniki` (`id`) ON DELETE SET NULL;

--
-- Omejitve za tabelo `rezultati`
--
ALTER TABLE `rezultati`
  ADD CONSTRAINT `rezultati_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `uporabniki` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
