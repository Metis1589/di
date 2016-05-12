/* triggers for cascade delete*/

CREATE TABLE TempTable (TableName varCHAR(50), query varchar(500));

INSERT INTO TempTable (TableName, query) 		
SELECT REFERENCED_TABLE_NAME as TableName, concat('\t\t/*remove ', TABLE_NAME, ' */\n\t\t', 'update ', TABLE_NAME, ' set record_type = \'Deleted\' where ', COLUMN_NAME, ' = NEW.', REFERENCED_COLUMN_NAME, ';') as query
	FROM
	  information_schema.KEY_COLUMN_USAGE
	WHERE
	 constraint_schema = 'dinein_new' and REFERENCED_TABLE_NAME is NOT NULL and TABLE_NAME NOT IN ('corporate_order','order') and REFERENCED_TABLE_NAME NOT IN ('currency', 'country', 'city', 'vat', 'expense_type','company_user_group');

select TableName, 
	concat(
		'DELIMITER $$ \n\n', 
        'USE `dinein_new`$$\n',
        'DROP TRIGGER IF EXISTS ',TableName,'_AFTER_UPDATE $$\n\n',
        'USE `dinein_new`$$\n',
        'CREATE DEFINER = CURRENT_USER TRIGGER ',TableName, '_AFTER_UPDATE AFTER UPDATE ON `',TableName,'` FOR EACH ROW\n',
        'BEGIN',
        '\n\tIF NEW.record_type = \'Deleted\' AND OLD.record_type <> \'Deleted\' THEN\n',
        GROUP_CONCAT(query SEPARATOR '\n'),
        '\n\tEND IF;',
        '\nEND',
        '\n$$'
        ) as tr
from TempTable 
group by TableName;

DROP TABLE TempTable;

/* triggers for default values */

CREATE TABLE TempTable (TableName varCHAR(50), query varchar(500));

INSERT INTO TempTable (TableName, query) 		
SELECT REFERENCED_TABLE_NAME as TableName, concat('\t\t\t/*update ', TABLE_NAME, ' */\n\t\t\t', 'update ', TABLE_NAME, ' set ',REFERENCED_TABLE_NAME,'_id = default_',REFERENCED_TABLE_NAME,'_id where ', COLUMN_NAME, ' = NEW.', REFERENCED_COLUMN_NAME, ';') as query
	FROM
	  information_schema.KEY_COLUMN_USAGE
	WHERE
	 constraint_schema = 'dinein_new' and REFERENCED_TABLE_NAME is NOT NULL and TABLE_NAME NOT IN ('corporate_order','order') and REFERENCED_TABLE_NAME IN ('currency', 'country', 'city', 'vat', 'expense_type','company_user_group');

select TableName, 
	concat(
		'DELIMITER $$ \n\n', 
        'USE `dinein_new`$$\n',
        'DROP TRIGGER IF EXISTS ',TableName,'_AFTER_UPDATE $$\n\n',
        'USE `dinein_new`$$\n',
        'CREATE DEFINER = CURRENT_USER TRIGGER ',TableName, '_AFTER_UPDATE AFTER UPDATE ON `',TableName,'` FOR EACH ROW\n',
        'BEGIN',
        '\n\tIF NEW.record_type = \'Deleted\' AND OLD.record_type <> \'Deleted\' THEN\n',
        '\t\tBEGIN\n',
        '\t\t\tDECLARE default_',TableName,'_id bigint(20);\n',
        '\t\t\tSET default_',TableName,'_id = (select id from ',TableName,' where is_default = 1);\n',
        GROUP_CONCAT(query SEPARATOR '\n'),
        '\n\t\tEND;',
        '\n\tEND IF;\n',
        '\nEND',
        '\n$$'
        ) as tr
from TempTable 
group by TableName;

DROP TABLE TempTable;
