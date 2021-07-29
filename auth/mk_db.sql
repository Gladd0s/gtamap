--CREATE TABLE markers ( id INTEGER PRIMARY KEY AUTOINCREMENT, x_pos REAL NOT NULL, y_pos REAL NOT NULL, draggable INTEGER, icon TEXT, popup TEXT, type INTEGER, owner INTEGER);

CREATE TABLE regions ( id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, type INTEGER, owner INTEGER, popup TEXT, color TEXT);

CREATE TABLE region_points ( region_name TEXT NOT NULL, indx INTEGER, x_pos REAL NOT NULL, y_pos REAL NOT NULL );