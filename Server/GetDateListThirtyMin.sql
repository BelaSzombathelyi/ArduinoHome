DELIMITER $$
CREATE DEFINER=`id8378391_test_user`@`%` PROCEDURE `GetDateListThirtyMin`()
BEGIN
	SET @current = CURRENT_TIMESTAMP;
	SET @minutes = MINUTE(@current);
	SET @seconds = SECOND(@current);

    SET @current = DATE_ADD(@current, INTERVAL -@minutes MINUTE);
	SET @current = DATE_ADD(@current, INTERVAL -@seconds SECOND);

	IF (@minutes * 60 + @seconds > 1800) THEN
		SET @current = DATE_ADD(@current, INTERVAL 30 MINUTE);
	END IF;
	CREATE TEMPORARY TABLE `id8378391_test_database`.`temp` (
		`from_date` timestamp NOT NULL,
		`to_date` timestamp NOT NULL
	);
    SET @idx = 0;
    SET @from_date = @current;
    REPEAT
		SET @idx = @idx + 1;
		SET @to_date = DATE_ADD(@from_date, INTERVAL 30 MINUTE);
		SET @to_date = DATE_ADD(@to_date, INTERVAL -1 SECOND);
		INSERT INTO `id8378391_test_database`.`temp` (`from_date`, `to_date`) VALUES (@from_date, @to_date);
		SET @from_date = DATE_ADD(@from_date, INTERVAL -30 MINUTE);
	UNTIL (@idx > 72)
	END REPEAT;
	SELECT * FROM `id8378391_test_database`.`temp`;
	DROP TABLE `id8378391_test_database`.`temp`;
END$$
DELIMITER ;