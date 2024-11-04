 SELECT pp.id, pp.title, SUM(ppc.quantity * (SELECT ph.price FROM price_history AS ph WHERE ph.product_id = ppc.product_id AND ph.updated_at <= !!DATE_PARAM!! ORDER BY ph.updated_at DESC LIMIT 1 )) as sum_price FROM `product_packages` AS pp
 LEFT JOIN product_package_contents AS ppc ON pp.id = ppc.product_package_id
 WHERE pp.id = !!ID_PARAM!!
 GROUP BY pp.id