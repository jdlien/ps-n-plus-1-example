SELECT * FROM categories;
SELECT * FROM items;

SELECT * FROM categories JOIN items ON categories.id = items.category_id

SELECT * FROM items AS i -- we now refer to items as i
JOIN categories AS c -- we now refer to categories as c
    ON i.category_id = c.id

-- Get the list of users along with all their items (one bigger query)
SELECT u.name, i.name AS item_name FROM users u
INNER JOIN item_user iu ON u.id = iu.user_id
INNER JOIN items i ON iu.item_id = i.id
ORDER BY u.name ASC;

-- Alternative approach: Two smaller queries
-- Users that have items
SELECT * FROM users WHERE id IN (SELECT DISTINCT user_id FROM item_user) ORDER BY id;

-- Users that have no items
SELECT * FROM users WHERE id NOT IN (SELECT DISTINCT user_id FROM item_user) ORDER BY id;

-- All items sorted by user
SELECT iu.user_id, iu.item_id, i.name AS item_name FROM item_user iu
JOIN items i ON iu.item_id = i.id
ORDER BY user_id;


-- You could also do this with an aggregate GROUP BY query. I doubt this is simpler or faster.
SELECT user_id FROM item_user GROUP by user_id;


SELECT
    i.id AS item_id,
    i.name as item_name,
    c.id AS category_id,
    c.name AS category_name
FROM items i, categories c
WHERE i.id IS NULL OR c.id IS NULL;


SELECT
    i.id AS item_id,
    i.name as item_name,
    NULL AS category_id,
    NULL AS category_name
FROM items i
WHERE i.category_id IS NULL
UNION
SELECT
    NULL AS item_id,
    NULL as item_name,
    c.id AS category_id,
    c.name AS category_name
FROM categories c
WHERE 
ORDER BY c.id, i.name;

SELECT * FROM categories CROSS JOIN items;


SELECT * FROM items i
LEFT JOIN categories c ON i.category_id = c.id
UNION ALL
SELECT * FROM items i
RIGHT JOIN categories c ON i.category_id = c.id
WHERE i.id IS NULL;



SELECT
    i.id,
    i.name,
    i.category_id,
    c.name AS category_name
FROM items i
RIGHT JOIN categories c ON i.category_id = c.id;


SELECT
    i.id,
    i.name,
    i.category_id,
    c.name AS category_name
FROM items i
LEFT JOIN categories c ON i.category_id = c.id
UNION ALL
SELECT
    i.id,
    i.name,
    i.category_id,
    c.name AS category_name
FROM items i
RIGHT JOIN categories c ON i.category_id = c.id
-- This prevents duplicate items from showing
-- as we only want categories with no items.
WHERE i.id IS NULL; 


SELECT
    i.id,
    i.name,
    i.category_id,
    c.name AS category_name
FROM items i
RIGHT JOIN categories c ON i.category_id = c.id
WHERE i.id IS NULL; 



SELECT
    i.id,
    i.name,
    i.category_id,
    c.name AS category_name
FROM items i
LEFT JOIN categories c ON i.category_id = c.id
UNION ALL
SELECT
    i.id,
    i.name,
    i.category_id,
    c.name AS category_name
FROM items i
RIGHT JOIN categories c ON i.category_id = c.id
-- This prevents duplicate items from showing
-- as we only want categories with no items.
WHERE i.id IS NULL
OR c.id IS NULL
; 


SELECT * FROM (
SELECT i.id,
          i.name,
          i.category_id,
          c.name AS category_name
   FROM items i
         LEFT JOIN categories c ON i.category_id = c.id
   UNION ALL
   SELECT i.id,
          i.name,
          i.category_id,
          c.name AS category_name
   FROM items i
         RIGHT JOIN categories c ON i.category_id = c.id
   WHERE i.id IS NULL
) AS all_items_all_categories
WHERE id IS NULL OR category_id IS NULL; 