#!/bin/bash

FROMDB=quotes2017
TODB=quotes2017_201711031235
mysql -uroot -proot <<EOF
BEGIN;

INSERT INTO $TODB.quote(
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
    commissions,
    grinding,
    hardening,
    zinc_plating)
SELECT id + 100,
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
    commissions,
    grinding,
    hardening,
    zinc_plating
FROM $FROMDB.quote
WHERE date > '2017-11-03' AND date < '2100-01-01';

INSERT INTO $TODB.quote_generic_concept (quote_id, \`order\`, description, amount)
SELECT quote_generic_concept.quote_id + 100,
       quote_generic_concept.\`order\`,
       quote_generic_concept.description,
       quote_generic_concept.amount
FROM $FROMDB.quote quote
INNER JOIN $FROMDB.quote_generic_concept quote_generic_concept
        ON quote_generic_concept.quote_id = quote.id
WHERE date > '2017-11-03' AND date < '2100-01-01';

COMMIT;

EOF
