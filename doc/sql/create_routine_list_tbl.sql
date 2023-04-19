CREATE TABLE routine_list(
	list_no INT PRIMARY KEY AUTO_INCREMENT
	,routine_no INT NOT NULL 
	,list_title VARCHAR(33) NOT NULL
	,list_contents VARCHAR(50) NOT NULL
	,list_due_time TIME NOT NULL 
	,list_done_flg CHAR(1) DEFAULT '0'
	,list_now_date DATETIME DEFAULT NOW()
	)
;
COMMIT;