DROP PROCEDURE IF EXISTS SP_GetAllAllergenen

DELIMITER $$

CREATE PROCEDURE SP_GetAllAllergenen()
BEGIN
    SELECT Id, Naam, Omschrijving
    FROM Allergeen;
END $$

DELIMITER ;
