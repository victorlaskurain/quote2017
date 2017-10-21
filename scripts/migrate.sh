#!/bin/bash -e

OLDDB=presupuestos
NEWDB=quotes2017
mysql -uroot -proot $NEWDB <<EOF
DELETE FROM $NEWDB.quote_generic_concept;
DELETE FROM $NEWDB.quote;
DELETE FROM $NEWDB.customer;

INSERT INTO $NEWDB.customer(id, name)
SELECT BEZZENB, BEZIZEN
FROM $OLDDB.BEZEROA;

INSERT INTO $NEWDB.customer(id, name)
SELECT DISTINCT
       IF(P.BEZZENB = 0, -1, P.BEZZENB),
       CONCAT('Cliente ', P.BEZZENB)
FROM      $OLDDB.PRESUPUESTOA AS P
LEFT JOIN $OLDDB.BEZEROA      AS B
    USING (BEZZENB)
WHERE B.BEZZENB IS NULL;
UPDATE $NEWDB.customer SET id = 0 WHERE id = -1;

INSERT INTO $NEWDB.quote (
    id,
    customer_id,
    date,
    description,
    shipping,
    number_of_day,
    accepted,
    drill,
    lathe,
    forge,
    saw,
    annealing,
    cementation,
    weight,
    price,
    milling,
    threading,
    zinc_plating,
    hardening,
    grinding)
SELECT
    PRESZENB,
    BEZZENB,
    DATA,
    DESKRIBAPENA,
    GARRAIOAK,
    EGUNEKOZENB,
    ONARTUA,
    TALADROA,
    TORNUA,
    FORJA,
    ZERRA,
    BIGUNK,
    CEMENT,
    PISUA,
    PREZIOA,
    FRESA,
    HARIA,
    KOMISIOAK,
    TENPLEA,
    ARTEZKETA
FROM $OLDDB.PRESUPUESTOA;

INSERT INTO $NEWDB.quote_generic_concept (
    quote_id,
     \`order\`,
     description,
     amount)
SELECT PRESZENB, 10, BEST1, PREZ1
FROM $OLDDB.PRESUPUESTOA
WHERE BEST1 <> '' OR PREZ1 <> 0;
INSERT INTO $NEWDB.quote_generic_concept (
    quote_id,
     \`order\`,
     description,
     amount)
SELECT PRESZENB, 20, BEST2, PREZ2
FROM $OLDDB.PRESUPUESTOA
WHERE BEST2 <> '' OR PREZ2 <> 0;
INSERT INTO $NEWDB.quote_generic_concept (
    quote_id,
     \`order\`,
     description,
     amount)
SELECT PRESZENB, 30, BEST3, PREZ3
FROM $OLDDB.PRESUPUESTOA
WHERE BEST3 <> '' OR PREZ3 <> 0;
EOF
