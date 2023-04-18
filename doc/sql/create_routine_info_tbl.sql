USE todo;

CREATE TABLE routine_info (
	routine_no INT PRIMARY KEY AUTO_INCREMENT
	,routine_title VARCHAR(33) NOT NULL
	,routine_contents VARCHAR(50) NOT NULL
	,routine_due_date DATETIME NOT NULL
	,routine_write_date DATETIME DEFAULT NOW()
	,routine_del_flg CHAR(1) DEFAULT '0'
	,routine_del_date DATETIME NULL
);

COMMIT;