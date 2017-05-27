
CREATE TABLE transactions(
	t_id INT NOT NULL AUTO_INCREMENT,
    t_time INT(11) NOT NULL,
    t_data INT NOT NULL,
    PRIMARY KEY (t_id) 
)engine MyISAM DEFAULT CHARSET=utf8;


DELIMITER $$
CREATE PROCEDURE prepare_data()
BEGIN
  DECLARE i INT DEFAULT 100;

  WHILE i < 1000 DO
    INSERT INTO transactions (t_time, t_data) 
		VALUES (UNIX_TIMESTAMP(NOW()) + i * 10000, (FLOOR( i + RAND( ) * 5 )));
    SET i = i + 10;
  END WHILE;
END$$
DELIMITER ;


CALL prepare_data();


