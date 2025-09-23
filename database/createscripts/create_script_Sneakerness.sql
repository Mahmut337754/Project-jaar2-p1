-- Step: 01
-- Goal: Create new database Sneakerness
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            22/09/2025      Mahmut Bas                    New
-- **********************************************************************************/

CREATE DATABASE IF NOT EXISTS Sneakerness;
USE Sneakerness;

-- Step: 02
-- Goal: Create a new table Allergeen
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            20/09/2025      Mahmut Bas                  New
-- **********************************************************************************/

DROP TABLE IF EXISTS Verkoper;

CREATE TABLE IF NOT EXISTS Verkoper (
    Id INT PRIMARY KEY AUTO_INCREMENT
  , Naam VARCHAR(255) NOT NULL
  , SpecialeStatus BIT(1) NOT NULL DEFAULT b'0'
  , VerkooptSoort VARCHAR(100) NOT NULL
  , StandType VARCHAR(10) NOT NULL
  , Dagen VARCHAR(20) NOT NULL
  , Logo VARCHAR(255)
  , IsActief BIT(1) NOT NULL DEFAULT b'1'
  , Opmerking TEXT
  , DatumGewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  , DatumAangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO Verkoper (Naam, SpecialeStatus, VerkooptSoort, StandType, Dagen, Logo, Opmerking)
VALUES
('SneakerShop NL', 1, 'Sneakers', 'AA+', 'Zaterdag,Zondag', 'logos/sneakershop.png', 'Partner verkoper')
,('StreetWear Co', 0, 'Kleding', 'AA', 'Zaterdag', NULL, 'Geen opmerkingen')
,('Food & Drinks', 0, 'Eten/Drinken', 'A', 'Zondag', NULL, 'Foodtruck aanwezig')
,('KidsCorner Fun', 0, 'Kids corner', 'A', 'Zaterdag,Zondag', NULL, 'Activiteiten voor kinderen')
,('Custom Kicks', 0, 'Customizers', 'AA', 'Zaterdag', 'logos/customkicks.png', 'Schoenen personaliseren ter plekke');

