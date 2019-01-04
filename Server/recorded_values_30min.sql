SELECT
	`BY30`,
    MIN(`value`) AS `MIN`,
    AVG(`value`) AS `AVG`,
    MAX(`value`) AS `MAX`
FROM (
	SELECT
		*,
		CONCAT(`PREFIX`,`SUFFIX`) AS `BY30`
	FROM (
	    SELECT
	    	*,
	    	A.MIN*60+A.SEC > 1800 AS `SUFFIX`
		FROM (
	    	SELECT 
	        	`id`,
	        	`value`,
	        	`date`, 
				DATE_FORMAT(`date`, "%Y-%m-%d %H_") AS `PREFIX`,
				CONVERT(DATE_FORMAT(`date`, "%i"), UNSIGNED INTEGER) AS `MIN`,
				CONVERT(DATE_FORMAT(`date`, "%s"), UNSIGNED INTEGER) AS `SEC`
			FROM `id8378391_test_database`.`recorded_values`
	    	) AS `A`
	   	) AS `B`
	) AS `C`
GROUP BY `BY30`
ORDER BY `BY30` DESC
