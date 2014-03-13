# Selectie totaluri anuale pentru fiecare client (top customers)

SELECT
  FINAL.*
FROM (
       SELECT
         @i := @i + 1 AS rank,
         t.*
       FROM (
              SELECT
                companii.nume_companie,
                SUM(oferte.valoare_oferta) AS orders_2014
              FROM oferte
                INNER JOIN companii ON oferte.id_companie_oferta = companii.id_companie
              WHERE YEAR(oferte.data_oferta) = 2014
              GROUP BY oferte.id_companie_oferta
              ORDER BY SUM(oferte.valoare_oferta) DESC
            ) AS t, (SELECT
                       @i := 0) AS foo
     ) AS FINAL
ORDER BY rank;


# Selectie totaluri lunare pentru o companie sau toate companiile

SELECT
  @luna1 := ( SELECT SUM(valoare_oferta)   FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 1 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L1,
  @luna2 := ( SELECT SUM(valoare_oferta)   FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 2 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L2,
  @luna3 := ( SELECT SUM(valoare_oferta)   FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 3 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L3,
  @luna4 := ( SELECT SUM(valoare_oferta)   FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 4 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L4,
  @luna5 := ( SELECT SUM(valoare_oferta)   FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 5 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L5,
  @luna6 := ( SELECT SUM(valoare_oferta)   FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 6 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L6,
  @luna7 := ( SELECT SUM(valoare_oferta)   FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 7 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L7,
  @luna8 := ( SELECT SUM(valoare_oferta)   FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 8 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L8,
  @luna9 := ( SELECT SUM(valoare_oferta)   FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 9 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L9,
  @luna10 := ( SELECT SUM(valoare_oferta)  FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 10 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L10,
  @luna11 := ( SELECT SUM(valoare_oferta)  FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 11 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L11,
  @luna12 := ( SELECT SUM(valoare_oferta)  FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 12 AND id_companie_oferta = @comp GROUP BY MONTH(data_oferta) ) AS L12
FROM (SELECT @year := 2014) AS foo, (SELECT @comp := 11) AS bar;

# select

SELECT SUM(valoare_oferta) FROM oferte
WHERE YEAR(data_oferta) = 2014
      AND id_companie_oferta = 11
GROUP BY MONTH(data_oferta);