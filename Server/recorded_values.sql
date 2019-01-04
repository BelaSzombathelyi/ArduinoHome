START TRANSACTION;
SET time_zone = "+01:00";

CREATE TABLE `recorded_values` (
  `id` int(11) NOT NULL,
  `type` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `unit` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` double NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `recorded_values` ADD PRIMARY KEY (`id`);
ALTER TABLE `recorded_values` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;
