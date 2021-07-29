UPDATE markers SET type = -1;
UPDATE markers SET type = 1 WHERE icon LIKE '%/policestation.png';
UPDATE markers SET type = 2 WHERE icon LIKE '%/H.png';
UPDATE markers SET type = 3 WHERE icon LIKE '%/bank.png';
UPDATE markers SET type = 4 WHERE icon LIKE '%/market.png';
UPDATE markers SET type = 5 WHERE icon LIKE '%/repair.png';
UPDATE markers SET type = 6 WHERE icon LIKE '%/gas.png';
UPDATE markers SET type = 7 WHERE icon LIKE '%/garage.png';
UPDATE markers SET type = 0 WHERE type = -1;
