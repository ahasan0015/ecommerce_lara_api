-- ============================================
-- Complete Ecommerce Seed Data Insert
-- ============================================

-- 1️⃣ Categories
INSERT INTO categories (name, slug, created_at, updated_at) VALUES
('Men Fashion','men-fashion',NOW(),NOW()),
('Women Fashion','women-fashion',NOW(),NOW()),
('Shoes','shoes',NOW(),NOW()),
('Accessories','accessories',NOW(),NOW());

-- 2️⃣ Brands
INSERT INTO brands (name, created_at, updated_at) VALUES
('Nike',NOW(),NOW()),
('Adidas',NOW(),NOW()),
('Puma',NOW(),NOW()),
('Zara',NOW(),NOW()),
('H&M',NOW(),NOW());

-- 3️⃣ Product Statuses
INSERT INTO product_statuses (name, created_at, updated_at) VALUES
('Active',NOW(),NOW()),
('Inactive',NOW(),NOW()),
('Out of Stock',NOW(),NOW());

-- 4️⃣ Colors
INSERT INTO colors (name, created_at, updated_at) VALUES
('Red',NOW(),NOW()),
('Blue',NOW(),NOW()),
('Black',NOW(),NOW()),
('White',NOW(),NOW()),
('Green',NOW(),NOW());

-- 5️⃣ Sizes
INSERT INTO sizes (name, created_at, updated_at) VALUES
('S',NOW(),NOW()),
('M',NOW(),NOW()),
('L',NOW(),NOW()),
('XL',NOW(),NOW()),
('XXL',NOW(),NOW());

-- 6️⃣ Variant Statuses
INSERT INTO variant_statuses (name, created_at, updated_at) VALUES
('Available',NOW(),NOW()),
('Out of Stock',NOW(),NOW()),
('Inactive',NOW(),NOW());

-- 7️⃣ Products
INSERT INTO products (name, slug, description, category_id, brand_id, status_id, created_at, updated_at) VALUES
('Nike Air Max T-Shirt','nike-air-max-tshirt','Premium Nike T-shirt',1,1,1,NOW(),NOW()),
('Adidas Sports Hoodie','adidas-sports-hoodie','Comfortable hoodie',1,2,1,NOW(),NOW()),
('Puma Running Shoes','puma-running-shoes','Lightweight running shoes',3,3,1,NOW(),NOW()),
('Zara Women Dress','zara-women-dress','Stylish summer dress',2,4,1,NOW(),NOW());

-- 8️⃣ Product Variants
INSERT INTO product_variants (product_id, color_id, size_id, sku, sale_price, stock, created_at, updated_at) VALUES
(1,1,2,'NIKE-RED-M',1200,15,NOW(),NOW()),
(1,3,3,'NIKE-BLACK-L',1250,10,NOW(),NOW()),
(2,4,3,'ADI-WHITE-L',2200,8,NOW(),NOW()),
(2,2,2,'ADI-BLUE-M',2100,12,NOW(),NOW()),
(3,3,NULL,'PUMA-BLACK-42',4500,6,NOW(),NOW()),
(3,4,NULL,'PUMA-WHITE-41',4400,7,NOW(),NOW()),
(4,5,2,'ZARA-GREEN-M',3000,9,NOW(),NOW());

-- 9️⃣ Product Images
INSERT INTO product_images (variant_id, image_path, is_main, created_at, updated_at) VALUES
(1,'products/variants/nike-red-1.jpg',1,NOW(),NOW()),
(1,'products/variants/nike-red-2.jpg',0,NOW(),NOW()),
(2,'products/variants/nike-black-1.jpg',1,NOW(),NOW()),
(3,'products/variants/adidas-white-1.jpg',1,NOW(),NOW()),
(4,'products/variants/adidas-blue-1.jpg',1,NOW(),NOW()),
(5,'products/variants/puma-black-1.jpg',1,NOW(),NOW()),
(6,'products/variants/puma-white-1.jpg',1,NOW(),NOW()),
(7,'products/variants/zara-green-1.jpg',1,NOW(),NOW());