# MiniSome

MiniSome on pieni some- tyylinen verkkosovellus, joka on toteutettu oppimisprojektina.

Projektin tavoitteena on opetella PHP ja MySQL välisen yhteyden perusteita. PHP- frameworkia ei käytetä. Projektin rakenne pidetään yksinkertaisena, jotta taustalla olevat perusasiat olisi helpompi ymmärtää.

## ✨ Ominaisuudet

* Julkaisujen katselu
* Uusien julkaisujen luominen
* Olemassa olevien julkaisujen muokkaaminen
* Julkaisujen poistaminen
* Yksinkertainen URL-osoitteisiin perustuva reititys
* Lomakkeiden validointi
* Virhe- ja onnistumisviestit
* Virheellisen käyttäjän syötteen käsittely
* SQL-injektioiden perussuojaus

## 🧱 Projektin Rakenne

Projektissa käytetään `index.php` tiedostoa yksinkertaisena front kontrollerina.

```
MINISOME/
├── config/
│   └── database.php
│   └── helpers.php
│   └── routes.php
├── includes/
│   └── card.php
│   └── footer.php
│   ├── header.php
├── views/
│   ├── add_post.php
│   ├── delete_post.php
│   ├── edit_post.php
│   ├── home.php
│
├── .htaccess
├── index.php
├── main.css
├── README.fi.md
└── README.md
```

## ⚙️ Asetukset

### Tietokanta

```sql
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 07, 2026 at 09:34 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `minisome`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `author`, `content`, `created_at`) VALUES
(1, 'Keko Pikselinen', 'Kirjoitin juuri ensimmäisen julkaisuni. Siis virallisesti liityin someihmisten joukkoon.', '2026-08-24 21:51:09'),
(2, 'Rinkeli Salamannopea', 'Jos tämä julkaisu saa tykkäyksen, internet toimii. Testataan. UPD: Jos joku oikeasti lukee tämän loppuun asti, lupaan olla muuttamatta sitä enää… ehkä.', '2026-08-24 21:51:39'),
(3, 'Juzu Kvanttinen', 'Aamu alkoi kahvilla, mutta kahvi loppui aivan liian nopeasti. Tiede on toistaiseksi voimaton.', '2026-08-24 22:00:42'),
(4, 'Rinkeli Salamannopea', 'Miksi kaikki sanovat ”katson vain yhden videon nopeasti”, ja yhtäkkiä onkin yö?', '2026-08-24 22:03:16'),
(5, 'Juzu Kvanttinen', 'Joskus paras tapa ratkaista ongelma on ensin teeskennellä, ettei sitä ole olemassa.', '2026-08-24 22:04:45'),
(6, 'Keko Pikselinen', 'Aloitan kokeen: en tee mitään viiteen minuuttiin. Tulokset ovat toistaiseksi vaikuttavia.', '2026-08-24 22:04:54'),
(7, 'Rinkeli Salamannopea', 'Uutinen: löysin jääkaapista ruokaa. Illan suunnitelma on valmis.', '2026-08-24 22:02:21'),
(11, 'Juzu Kvanttinen', 'Heräsin tänään aikaisin ja olin siitä niin ylpeä… kunnes tajusin, että on sunnuntai. 💡 Jos tämä julkaisu saa tykkäyksen, internet toimii. Testataan. UPD: Jos joku oikeasti lukee tämän loppuun asti, lupaan olla muuttamatta sitä enää… ehkä.', '2026-09-03 21:52:23'),
(12, 'Kalle Binaarinen', 'Avasin tänään jääkaapin vain nähdäkseni, onko siellä tapahtunut jotain uutta. Ei ollut. 🧊 Päätin silti pitää ovea auki vielä hetken, ihan varmuuden vuoksi. Hmmm... katsotaan, katsotaan...', '2026-09-03 21:59:17');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
```

## 🚀 Käyttöönotto

Projektiin voidaan tutustua paikallisen kehitysympäristön avulla, kuten WAMP.

1. Kloonaa repositorio [https://github.com/vitavlas/sami-mini-some.git](https://github.com/vitavlas/sami-mini-some.git).
2. Luo MySQL- tietokanta nimeltä `minisome`.
3. Tuo SQL- tiedosto kohdassa **Asetukset → Tietokanta** kuvatulla tavalla.
4. Avaa projekti paikallisen web-palvelimesi kautta.
