<?php
$sql = "SELECT 
        tbl_customer_point.id as id_level, 
        tbl_customer_point.customer_level as customer_level, 
        tbl_customer_point.customer_point as customer_point, 
        tbl_customer_point.customer_discount as customer_discount, 
        COUNT(tbl_customer_point.customer_point) as count_customer 
        FROM tbl_customer_point 
        LEFT JOIN tbl_customer_customer ON tbl_customer_point.customer_point = tbl_customer_customer.customer_point 
        WHERE tbl_customer_point.id_business = '1' 
        GROUP BY tbl_customer_point.customer_point 
        ORDER BY convert(tbl_customer_point.customer_point, decimal) ASC";