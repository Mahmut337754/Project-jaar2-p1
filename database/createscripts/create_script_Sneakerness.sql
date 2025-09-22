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
    ,Naam VARCHAR(255) NOT NULL
    ,SpecialeStatus TINYINT(1) NOT NULL DEFAULT 0
    ,VerkooptSoort VARCHAR(100) NOT NULL
    ,StandType VARCHAR(10) NOT NULL
    ,Dagen VARCHAR(20) NOT NULL
    ,Logo VARCHAR(255) NULL
    ,IsActief TINYINT(1) NOT NULL DEFAULT 1
    ,Opmerking TEXT NULL
    ,DatumGewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,DatumAangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
