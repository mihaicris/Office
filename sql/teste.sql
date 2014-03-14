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



SELECT FINAL.*
FROM (SELECT
        @i := @i + 1 AS Rank, results.*
      FROM
        (SELECT @i := 0) AS foo,
        (SELECT @an := 2014) AS an,
        (SELECT @comp := companii.id_companie AS id_companie,
           companii.nume_companie, companii.oras_companie, companii.tara_companie,
                (SELECT SUM(oferte.valoare_oferta) AS GRAND FROM oferte
                WHERE YEAR(data_oferta) = @an-1 AND oferte.id_companie_oferta = @comp) AS FYP,
                (SELECT SUM(oferte.valoare_oferta) AS GRAND FROM oferte
                WHERE YEAR(data_oferta) = @an AND oferte.id_companie_oferta = @comp) AS FY,
                (SELECT SUM(oferte.valoare_oferta) AS Ian FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 1 AND oferte.id_companie_oferta = @comp) AS M1,
                (SELECT SUM(oferte.valoare_oferta) AS Feb FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 2 AND oferte.id_companie_oferta = @comp) AS M2,
                (SELECT SUM(oferte.valoare_oferta) AS Mar FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 3 AND oferte.id_companie_oferta = @comp) AS M3,
                (SELECT SUM(oferte.valoare_oferta) AS Apr FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 4 AND oferte.id_companie_oferta = @comp) AS M4,
                (SELECT SUM(oferte.valoare_oferta) AS Mai FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 5 AND oferte.id_companie_oferta = @comp) AS M5,
                (SELECT SUM(oferte.valoare_oferta) AS Iun FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 6 AND oferte.id_companie_oferta = @comp) AS M6,
                (SELECT SUM(oferte.valoare_oferta) AS Iul FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 7 AND oferte.id_companie_oferta = @comp) AS M7,
                (SELECT SUM(oferte.valoare_oferta) AS Aug FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 8 AND oferte.id_companie_oferta = @comp) AS M8,
                (SELECT SUM(oferte.valoare_oferta) AS Sep FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 9 AND oferte.id_companie_oferta = @comp) AS M9,
                (SELECT SUM(oferte.valoare_oferta) AS Oct FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 10 AND oferte.id_companie_oferta = @comp) AS M10,
                (SELECT SUM(oferte.valoare_oferta) AS Nov FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 11 AND oferte.id_companie_oferta = @comp) AS M11,
                (SELECT SUM(oferte.valoare_oferta) AS Decem FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 12 AND oferte.id_companie_oferta = @comp) AS M12
         FROM oferte
           INNER JOIN companii ON oferte.id_companie_oferta = companii.id_companie
         GROUP BY oferte.id_companie_oferta
         ORDER BY FY DESC) AS results
     ) AS FINAL
ORDER BY Rank;

SELECT * FROM (
    (SELECT @year := 2014 AS FY) AS Init,
    (SELECT SUM(valoare_oferta) AS TOTAL_FYP FROM oferte WHERE YEAR (data_oferta) = @year-1) AS COL1,
    (SELECT SUM(valoare_oferta) AS TOTAL_FY FROM oferte WHERE YEAR (data_oferta) = @year) AS COL2,
    (SELECT SUM(valoare_oferta) AS M1 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 1) AS COL3,
    (SELECT SUM(valoare_oferta) AS M2 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 2) AS COL4,
    (SELECT SUM(valoare_oferta) AS M3 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 3) AS COL5,
    (SELECT SUM(valoare_oferta) AS M4 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 4) AS COL6,
    (SELECT SUM(valoare_oferta) AS M5 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 5) AS COL7,
    (SELECT SUM(valoare_oferta) AS M6 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 6) AS COL8,
    (SELECT SUM(valoare_oferta) AS M7 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 7) AS COL9,
    (SELECT SUM(valoare_oferta) AS M8 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 8) AS COL10,
    (SELECT SUM(valoare_oferta) AS M9 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 9) AS COL11,
    (SELECT SUM(valoare_oferta) AS M10 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 10) AS COL12,
    (SELECT SUM(valoare_oferta) AS M11 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 11) AS COL13,
    (SELECT SUM(valoare_oferta) AS M12 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 12) AS COL14
); 

