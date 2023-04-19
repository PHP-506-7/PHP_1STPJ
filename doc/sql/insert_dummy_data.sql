INSERT INTO routine_info(
	routine_title
	,routine_contents
	,routine_due_time
	)
VALUES
('산책', '집주변 산책하기', TIME(180000))
,('찜질', '따뜻한 물수건 스팀찜질', TIME(190000))
,('마사지', '뭉친 몸 풀어주기', TIME(200000))
,('우유한잔', '잠이 안올 때 따뜻한 우유 한잔', TIME(210000))
,('샤워', '따듯한 물에 샤워', TIME(220000))
,('영양제 섭취', '식후 알맞은 영양제 섭취', TIME(230000))
;

INSERT INTO routine_list(
	routine_no
	,list_title
	,list_contents
	,list_due_time
	) 
SELECT 
	routine_no
	,routine_title
	,routine_contents
	,routine_due_time
FROM routine_info;

COMMIT;